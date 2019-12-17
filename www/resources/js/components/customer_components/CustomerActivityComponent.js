import React, { Component } from 'react';
import StandardLoadingComponent from "../loading/StandardLoadingComponent";
import EclipseElementLoadingComponent from "../loading/EclipseElementLoadingComponent";

const ACTIVITY_API_URL = '/api/v1/users/';
export default class CustomerActivityComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            hasErrors: false,
            user: this.props.user
        }
    }

    fetchUserActivity() {
        fetch(ACTIVITY_API_URL + this.state.user.uid + '/activity')
            .then(response => response.json())
            .then(result =>
                this.setState({
                    activity: result,
                    isLoaded: true
                })
            )
            .catch(error => error)
    }

    componentDidMount() {
        this.fetchUserActivity();
    }

    render() {
        if (!this.state.isLoaded) {
            return (
                <EclipseElementLoadingComponent/>
            )
        } else {
            return (
                <div className={"row"}>
                    {this.state.activity.map((activity, index) => {
                        return (
                            <div className="card col-6 shadow-lg" key={index}>
                                <div className="media pb-1 mb-3" key={index}>
                                    <div className={"account-activity-category "}>
                                    </div>
                                    <div className="media-body ml-3">
                                        <div className="mb-1">
                                            <strong className="font-weight-semibold">{activity.description}</strong> &nbsp;
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )
                    })}
                </div>
            )
        }
    }
}