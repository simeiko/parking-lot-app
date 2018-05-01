import React, { Component } from 'react';

/**
 * Render <footer> element with additional buttons.
 *
 * Can be added after <Main> element in the App component.
 * However, the CSS design was tested only on mobile devices.
 *
 * @todo implement in the next version of the App | importance: HIGH
 */
export default class Footer extends Component {
    render() {
        return (
            <footer>
                <button>Parking Rates</button>
                <button>Make Payment</button>
            </footer>
        );
    }
}