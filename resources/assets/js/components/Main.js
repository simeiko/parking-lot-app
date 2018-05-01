import React, { Component } from 'react';

/**
 * Main component is used to render (only) the <main> and its content.
 * The content list is located in the buildContent() method.
 *
 * Depending on the content, this component may accept "data", "driveIn", and "driveOut" props.
 */
export default class Main extends Component {
    render() {
        return (
            <main>
                {this.buildContent()}
            </main>
        );
    }

    /**
     * Conditional return based on this.props.content.
     *
     * Returns content to be placed inside <main> tag for rendering.
     */
    buildContent() {
        switch (this.props.content) {
            case "loading":
                return this.getHeader('Loading...');
            case "error":
                return this.getHeader(this.props.data);
            case "drive-in":
                return this.getDriveInContent(this.props.data);
            case "drive-out":
                return this.getDriveOutContent(this.props.data.stay_duration, this.props.data.stay_cost);
            case "not-available":
                return this.getHeader("All parking lots are occupied.");
            case "success":
                return this.getHeader('Payment processed successfully!');
            default:
                return null; // todo: fallback
        }
    }

    /*** Content Functions ***/

    /**
     * Returns <h3> header with a string inside.
     * @param {string} text
     */
    getHeader(text) {
        return <h3>{text}</h3>;
    }

    /**
     * Returns content for "drive-in" this.props.content type.
     *
     * @param {int} amountOfLots Amount of available parking lots
     */
    getDriveInContent(amountOfLots) {
        if(amountOfLots === 1) {
            return(
                <div>
                    <h3>There is only 1 available parking lot.</h3>
                    <button id="drive-btn" onClick={() => this.props.driveIn()}>Drive In</button>
                </div>
            );
        } else {
            return(
                <div>
                    <h3>There are {amountOfLots} available parking lots.</h3>
                    <button id="drive-btn" onClick={() => this.props.driveIn()}>Drive In</button>
                </div>
            );
        }
    }

    /**
     * Display Drive Out content.
     *
     * @param {int} duration Stay duration in minutes
     * @param {number} cost Stay cost
     */
    getDriveOutContent(duration, cost) {
        const minutes = duration === 1 ? 'minute' : 'minutes';

        return (
            <div>
                <h3>You've stayed for {duration} {minutes}: ${cost.toFixed(2)}</h3>
                <form>
                    <label htmlFor="credit_card">Enter your credit card:</label>
                    <input id="credit_card" name="credit_card" type="string"
                           pattern="^\d{16}$" minLength="16" maxLength="16" required />
                    <button id="drive-btn" onClick={(e) => this.props.driveOut(e)}>Drive Out</button>
                </form>
            </div>
        );
    }
}