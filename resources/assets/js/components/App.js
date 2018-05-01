import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import {Config} from "../Config";
import Header from "./Header";
import Main from "./Main";

/**
 * Entry point of the Application.
 * All fetch requests are located here.
 *
 * @todo display Ticket ID to the client for manual ticket processing
 * @todo make an abstract function for all fetch calls ( there is a pattern )
 * @todo store ticket issue time in the local storage, and then display stay duration in real time
 * @todo finish Footer component ( display parking rates & manual payment processing )
 */
export default class App extends Component {
    constructor(props) {
        super(props);

        this.driveIn = this.driveIn.bind(this);
        this.driveOut = this.driveOut.bind(this);

        this.state = {
            iconType: 'loading', // Header Icon Type
            mainContent: 'loading', // Main Content
            mainContentData: null // Main Content Data
        };
    }

    /**
     * Display Drive Out page OR make an initial fetching.
     */
    componentWillMount() {
        if(localStorage.getItem('ticket_id')) {
            this.fetchTicketDetails(localStorage.getItem('ticket_id'));
        } else {
            this.fetchAvailableLots();
        }
    }

    /**
     * Fetch available lots and display corresponding information.
     */
    fetchAvailableLots() {
        fetch(Config.api_link + 'tickets', { method: 'GET', cache: 'no-cache' })
            .then(response => response.json())
            .then(response => {

                if(response.error) {
                    this.changeMainContent('error', response.error);
                    this.changeHeaderIcon('error');
                    return;
                }

                if(response.free_lots > 0) {
                    this.changeMainContent('drive-in', response.free_lots);
                    this.changeHeaderIcon('available');
                } else {
                    this.changeMainContent('not-available');
                    this.changeHeaderIcon('not-available');
                }
            })
            .catch(error => this.changeMainContent('error', error));
    }

    /**
     * Fetch ticket details, and then display corresponding information.
     * @param {int} ticket_id Ticket ID
     */
    fetchTicketDetails(ticket_id) {
        fetch(Config.api_link + 'tickets/' + ticket_id, { method: 'GET' })
            .then(response => response.json())
            .then(response => {

                if(response.error) {
                    this.changeMainContent('error', response.error);
                    this.changeHeaderIcon('error');
                    return;
                }

                this.changeMainContent('drive-out', response);
                this.changeHeaderIcon('available');
            })
            .catch(error => this.changeMainContent('error', error));
    }

    /**
     * Change Main Content.
     *
     * @param {string} contentName Content Name
     * @param {*} data Data to be passed to the Main Component
     */
    changeMainContent(contentName, data = null) {
        this.setState({
            mainContent: contentName,
            mainContentData: data
        });
    }

    /**
     * Change Header Icon.
     * @param {string} type Icon type
     */
    changeHeaderIcon(type) {
        this.setState({
            iconType: type
        });
    }

    /**
     * Make a POST request to the API.
     * Save Ticket ID to the local storage.
     */
    driveIn() {
        fetch(Config.api_link + 'tickets', { method: 'POST' })
            .then(response => response.json())
            .then(response => {

                if(response.error) {
                    this.changeMainContent('error', response.error);
                    this.changeHeaderIcon('error');
                    return;
                }

                localStorage.setItem('ticket_id', response.ticket_id);
                this.changeMainContent('drive-out', {stay_duration: 0, stay_cost: 0} );
                this.changeHeaderIcon('available');
            })
            .catch(error => this.changeMainContent('error', error));
    }

    /**
     * Make a POST request to the Payment API.
     * Sends the Ticket ID and credit_card info.
     *
     * @param e Onclick event
     * @returns {boolean} False on fail
     */
    driveOut(e) {
        const credit_card = document.getElementById('credit_card').value;
        const ticket_id = localStorage.getItem('ticket_id');

        const request = {
            method: 'POST',
            body: `credit_card=${credit_card}`,
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            }
        };

        e.preventDefault();

        if(!/^\d{16}$/.test(credit_card)) {
            alert('Credit card must be a 16-digit string.');
            return false;
        }

        fetch(Config.api_link + 'payments/' + ticket_id, request)
            .then(response => response.json())
            .then(response => {

                if(response.error) {
                    this.changeMainContent('error', response.error);
                    this.changeHeaderIcon('error');
                    return;
                }

                this.changeMainContent('success');
                this.changeHeaderIcon('success');
                localStorage.removeItem('ticket_id')
            })
            .catch(error => this.changeMainContent('error', error));
    }

    /**
     * Returns this.state.layout.
     */
    render() {
        return (
            <div id="container">
                <Header iconType={this.state.iconType}/>

                <Main
                    content={this.state.mainContent}
                    data={this.state.mainContentData}
                    driveIn={this.driveIn}
                    driveOut={this.driveOut}/>
            </div>
        );
    }
}

ReactDOM.render(<App />, document.getElementById('root'));