import React, { Component } from 'react';

export default class CreateCustomerCompanyModal extends Component {
    constructor(props) {
        super(props);
    }

    componentDidMount() {}

    render() {
        return (
            <div id="create-customer-company-modal" className="modal" tabIndex="-1" role="dialog">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <form id="create-customer-company" action="">
                            <div className="modal-header">
                                <h5 className="modal-title">Add Company</h5>
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                <div className="row">
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-name" className="form-label">Name</label>
                                        <input type="text" className={'form-control'} name={'customer-company-name'}
                                            id={"customer-company-name"}/>
                                    </div>
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-domain" className="form-label">Domain</label>
                                        <input type="text" className={'form-control'} name={'customer-company-domain'}
                                               id={"customer-company-domain"}/>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-industry" className="form-label">Industry</label>
                                        <input type="text" className={'form-control'} name={'customer-company-industry'}
                                               id={"customer-company-industry"}/>
                                    </div>
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-phone" className="form-label">Phone</label>
                                        <input type="text" className={'form-control'} name={'customer-company-phone'}
                                               id={"customer-company-phone"}/>
                                    </div>
                                </div>
                                <div className="row">
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-owner" className="form-label">Company Owner</label>
                                        <input type="text" className={'form-control'} name={'customer-company-owner'}
                                               id={"customer-company-owner"}/>
                                    </div>
                                    <div className="form-group col-md-6 col-sm-12">
                                        <label htmlFor="customer-company-type" className="form-label">Type</label>
                                        <input type="text" className={'form-control'} name={'customer-company-type'}
                                               id={"customer-company-type"}/>
                                    </div>
                                </div>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-primary">Save Company</button>
                                <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        )
    }
}