import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import SearchResults from 'react-filter-search';
import EclipseElementLoadingComponent from "../loading/EclipseElementLoadingComponent";
import CreateCustomerModal from "./modals/CreateCustomerModal";
import EclipseLoadingComponent from "../loading/EclipseLoadingComponent";

let self = '';

const LEAD_STATUS_OPTIONS = '/api/v1/lead-statuses?format=react-select';
const LIFE_CYCLE_OPTIONS = '/api/v1/life-cycles?format=react-select';
const AUTH_USER_URL = '/api/v1/auth/';
const CUSTOMER_API_URL = '/api/v1/customers/';
const USER_ASSIGNABLE = '/api/v1/users?filter=assignable';

export default class CustomerMainAlt extends Component {
    constructor(props) {
        super(props);
        this.state = {
            users: [],
            authUser: [],
            permissions: [],
            isLoaded: false,
            userIsLoading: false,
            hasErrors: false,
            value: '',
            selectedUser: [],
            lifeCycleOptions: [],
            leadStatusOptions: [],
            lifeCycleLoaded: false,
            leadStatusLoaded: false,
            usersAssignable: [],
            fetchingCustomers: true
        };
        self = this;
    }

    fetchAuthUser() {
        fetch(AUTH_USER_URL)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    authUser: result.auth,
                    permissions: result.auth.permissions
                })
            )
            .catch(error => error)
    }

    fetchAssignableUsers() {
        fetch(USER_ASSIGNABLE)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    usersAssignable: result.data,
                    isLoaded: true
                })
            )
            .catch(error => error)
    }

    fetchCustomers() {
        fetch(CUSTOMER_API_URL)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    users: result.data,
                    fetchingCustomers: false,
                    filteredCustomers: result.data,
                    isLoaded: true
                })
            )
            .catch(error => error)
    }

    fetchUser(uid) {
        fetch(CUSTOMER_API_URL + uid)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    selectedUser: result.data,
                    userIsLoading: false
                })
            )
            .catch(error => error)
    }

    fetchLifeCycleOptions() {
        fetch(LIFE_CYCLE_OPTIONS)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    lifeCycleOptions: result,
                    lifeCycleLoaded: true
                })
            )
            .catch(error => error)
    }

    fetchLeadStatusOptions() {
        fetch(LEAD_STATUS_OPTIONS)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    leadStatusOptions: result,
                    leadStatusLoaded: true
                })
            )
            .catch(error => error)
    }

    userAssignableSelect() {
        return (
            <select className={"form-control select2"} name={"customer-assigned-to"}>
                {this.state.usersAssignable.map((user, index) => {
                    return (
                        <option value={user.id} key={index}>{user.name.toProperCase() + ' - ' + user.sid}</option>
                    )
                })}
            </select>
        )
    }

    lifeCycleSelectElement() {
        return (
            <select className={"form-control select2"} name={"customer-life-cycle"}>
                {this.state.lifeCycleOptions.map((lifeCycle, index) => {
                    return (
                        <option value={lifeCycle.value} key={index}>{lifeCycle.label}</option>
                    )
                })}
            </select>
        )
    }

    leadStatusSelectElement() {
        return (
            <select className={"form-control select2"} name={"customer-lead-status"}>
                {this.state.leadStatusOptions.map((leadStatus, index) => {
                    return (
                        <option value={leadStatus.value} key={index}>{leadStatus.label}</option>
                    )
                })}
            </select>
        )
    }

    onSaveCreate(event) {
        event.preventDefault();
        $(event.target).validate({
            rules: {
                'customer-name': 'required',
                'customer-email': {
                    email: true,
                    required: true,
                    remote: {
                        url: "/api/v1/remote/check/users/email",
                        type: "post",
                        data: {
                            'customer-email': () => {
                                return $("#customer-email").val()
                            }
                        }
                    }
                }
            },
            messages: {
                'customer-email': {
                    remote: jQuery.validator.format("{0} is already taken.")
                }
            },
            errorPlacement: function errorPlacement(error, element) {
                let $parent = $(element).parents('.form-group');

                // Do not duplicate errors
                if ($parent.find('.jquery-validation-error').length) { return; }

                $parent.append(
                    error.addClass('jquery-validation-error small form-text invalid-feedback')
                );
            },
            highlight: function(element) {
                let $el = $(element);
                let $parent = $el.parents('.form-group');

                $el.addClass('is-invalid');

                // Select2 and Tagsinput
                if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
                    $el.parent().addClass('is-invalid');
                }
            },
            unhighlight: function(element) {
                $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
            },
            submitHandler: (form) => {
                let data = $(form).serialize();
                axios.post(CUSTOMER_API_URL, data)
                    .then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            $("#create-customer-modal").modal('hide');
                            self.fetchCustomers();
                        }
                    })
            }
        })
    }


    componentDidMount() {
        this.fetchLeadStatusOptions();
        this.fetchLifeCycleOptions();
        this.fetchAssignableUsers();
        this.fetchAuthUser();
        this.fetchCustomers();
    }

    handleChange(event) {
        const { value } = event.target;
        this.setState({ value });
    };

    renderUser(event) {
        let elm = $(event.target);
        let uid = elm.data('uid');
        elm.parent().find('div').each(function() {
            $(this).removeClass('selected');
        });
        elm.addClass('selected');
        this.setState({
            userIsLoading: true
        });
        this.fetchUser(uid);
    }

    handleSelectedUser() {
        if (this.state.userIsLoading) {
            return (
                <EclipseElementLoadingComponent/>
            )
        } else {
            if (this.state.selectedUser.length < 1) {
                return (
                    <div className="no-user-selected">
                        Select a user
                    </div>
                )
            }
            let user = this.state.selectedUser[0];
            if (user.phone === null) {
                user.phone = 'Not available';
            }
            if (user.user_type_name === null) {
                user.user_type_name = 'not set';
            }
            return (
                <div className={"mt-3"}>
                    <div className="row">
                        <div className="col-md-4 col-sm-6">
                            <h4>{user.name}</h4>
                            <div>
                                Image
                            </div>
                            <div className="form-group">
                                <label htmlFor="account-name" className="form-label">Name</label>
                                <span className={'d-block'}>{user.name}</span>
                            </div>
                            <div className="form-group">
                                <label htmlFor="account-email" className="form-label">Email</label>
                                <span className={'d-block'}>{user.email}</span>
                            </div>
                            <div className="form-group">
                                <label htmlFor="account-phone" className="form-label">Phone</label>
                                <span className={'d-block'}>{user.phone}</span>
                            </div>
                            <div className="form-group">
                                <label htmlFor="account-type" className="form-label">Type</label>
                                <span className={'d-block'}>{user.user_type_name.toProperCase()}</span>
                            </div>
                        </div>
                    </div>
                </div>
            )
        }
    }

    render() {
        const { users, value } = this.state;
        if (!this.state.isLoaded) {
            return (
                <EclipseLoadingComponent/>
            )
        } else {
            return (
                <div>
                    <h4 className="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
                        <div>Customers</div>
                        <button type="button" className="btn d-block btn-primary rounded-pill waves-effect"
                                data-toggle="modal" data-target="#create-customer-modal">
                            <span className="ion ion-md-add"></span>&nbsp; Add Customer
                        </button>
                    </h4>
                    <div className="row">
                        <div className="col-sm-6 col-md-8">
                            <div className="form-group" style={{maxWidth: '200px'}}>
                                <label htmlFor="filter-Customers" className="form-label">Search</label>
                                <input className={"form-control"} type="search" id={"filter-Customers"}
                                       name={"filter-Customers"} placeholder={'Enter a name...'}
                                       value={value}
                                       onChange={this.handleChange.bind(this)}/>
                            </div>
                            <div className="account-list">
                                {(this.state.fetchingCustomers) ?
                                    (
                                        <EclipseElementLoadingComponent/>
                                    ) : (
                                        <SearchResults
                                            value={value}
                                            data={users}
                                            renderResults={results => (
                                                <div>
                                                    {results.map((user, index) => {
                                                        return (
                                                            <div data-uid={user.uid} className="account-list-item d-flex"
                                                                 onClick={this.renderUser.bind(this)} key={index}>
                                                                <div className="account-img-container">
                                                                    <div className="account-img">
                                                                        <img src={user.avatar_url}
                                                                             className="d-block ui-w-40 rounded-circle"/>
                                                                    </div>
                                                                </div>
                                                                <div className="account-info">
                                                                    <div className="account-info-name">{user.name}</div>
                                                                    <div className={"account-info-sid"}>@{user.sid}</div>
                                                                </div>
                                                            </div>
                                                        )

                                                    })}
                                                </div>
                                            )}
                                        />
                                    )
                                }
                            </div>

                        </div>
                        <div className="col-sm-6 col-md-4">

                            {this.handleSelectedUser()}

                        </div>
                    </div>
                    <div id="create-customer-modal" className="modal" tabIndex="-1" role="dialog">
                    <div className="modal-dialog" role="document">
                    <div className="modal-content">
                    <form onSubmit={this.onSaveCreate.bind(event)} id="create-customer-form"
                    autoComplete="off">
                    <div className="modal-header">
                    <h5 className="modal-title">Add Customer</h5>
                    <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                    <div className="modal-body">
                    <div className="row">
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-name" className="form-label">Name</label>
                    <input className="form-control" type="text" id="customer-name"
                    name="customer-name" placeholder={"Name"}/>
                    </div>
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-email" className="form-label">Email</label>
                    <input className="form-control" type="email" id="customer-email"
                    name="customer-email" placeholder={"Email"}/>
                    </div>
                    </div>
                    <div className="row">
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-phone" className="form-label">Phone</label>
                    <input className="form-control" type="tel" id="customer-phone"
                    name="customer-phone" placeholder={"Phone"}/>
                    </div>
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-assigned" className="form-label">Assign to</label>
                    {this.userAssignableSelect()}
                    </div>
                    </div>

                    <div className="row">
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-lead-status" className="form-label">Status</label>
                    {this.leadStatusSelectElement()}
                    </div>
                    <div className="form-group col-md-6 col-sm-12">
                    <label htmlFor="customer-life-cycle" className="form-label">Life
                    Cycle</label>
                    {this.lifeCycleSelectElement()}
                    </div>
                    </div>
                    <div className="row">
                    <div className="form-group col">
                    <label className="switcher my-1">
                    <input type="checkbox" className="switcher-input"
                    name="customer-skip-email-verification"/>
                    <span className="switcher-indicator">
                    <span className="switcher-yes"></span>
                    <span className="switcher-no"></span>
                    </span>
                    <span className="switcher-label">Skip Email Verification</span>
                    </label>
                    <label className="switcher my-1">
                    <input type="checkbox" className="switcher-input"
                    name="customer-change-password"/>
                    <span className="switcher-indicator">
                    <span className="switcher-yes"></span>
                    <span className="switcher-no"></span>
                    </span>
                    <span className="switcher-label">Send information request email</span>
                    </label>
                    </div>
                    </div>
                    </div>
                    <div className="modal-footer">
                    <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id={"submit-btn"} type="submit" className="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                    </div>
                    </div>
                    </div>

                </div>
            )
        }
    }
}


if (document.getElementById('customer-list-component')) {
    ReactDOM.render(<CustomerMainAlt />, document.getElementById('customer-list-component'));
}