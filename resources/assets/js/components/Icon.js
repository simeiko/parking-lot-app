import React, { Component } from 'react';

/**
 * Conditional Icon render.
 * Uses Google Material Icons.
 *
 * PROPS:   type - ( loading/error/available/not-available/success ) type of icon; default is "available".
 */
export default class Icon extends Component {
    render() {
        switch (this.props.type) {
            case "loading":
                return <i className="material-icons loading">&#xE863;</i>; // Sync
            case "error":
                return <i className="material-icons error">&#xE629;</i>; // Sync failed
            case "not-available":
                return <i className="material-icons not-available">&#xE033;</i>; // Not available
            case "success":
                return <i className="material-icons">&#xE876;</i>;
            case "available":
            case "car":
            default:
                return <i className="material-icons car">&#xE531;</i>; // Car Icon
        }
    }
}