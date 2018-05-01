import React, { Component } from 'react';
import Icon from "../components/Icon";

/**
 * Render <header> with <Icon> component.
 *
 * PROPS:   iconType - (required) Icon Type
 */
export default class Header extends Component {
    render() {
        return (
            <header>
                <Icon type={this.props.iconType} />
            </header>
        );
    }
}