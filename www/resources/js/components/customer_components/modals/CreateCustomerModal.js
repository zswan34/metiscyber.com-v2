import React, { Component } from 'react';

const CREATE_CUSTOMER_API = '/api/v1/customers';
const SELF = this;

export default class CreateCustomerModal extends Component {
    constructor(props) {
        super(props);
        this.state = {
            lifeCycleOptions: this.props.lifeCycleOptions,
            leadStatusOptions: this.props.leadStatusOptions,
            lifeCycleLoaded: this.props.lifeCycleLoaded,
            leadStatusLoaded: this.props.leadStatusLoaded,
            usersAssignable: this.props.usersAssignable,
            updateCustomers: this.props.updateCustomers.bind(this)
        }
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
                axios.post(CREATE_CUSTOMER_API, data)
                    .then((res) => {
                        console.log(res);
                        if (res.data.success) {
                            $("#create-customer-modal").modal('hide');
                            this.state.updateCustomers();
                        }
                    })
            }
        })
    }

    componentDidMount() {

    }

    render() {
        if (this.state.leadStatusLoaded && this.state.lifeCycleLoaded) {
            return (
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
            )
        }
    }
}