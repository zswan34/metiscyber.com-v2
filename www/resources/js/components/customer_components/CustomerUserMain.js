import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import ReactTooltip from 'react-tooltip'
import EditText from 'react-editext';

import CustomerUserActivity from "./CustomerUserActivity";
import EclipseLoadingComponent from "../loading/EclipseLoadingComponent";


const uid = window.location.pathname.split('/')[2];
const AUTH_USER_URL = '/api/v1/auth/';
const UPDATE_USER_API = '/api/v1/users/';
const USER_API_URL = '/api/v1/customers/';

export class CustomerUserMain extends Component {
    constructor(props) {
        super(props);
        this.state = {
            isLoaded: false,
            hasErrors: false,
            authUser: [],
            permissions: [],
            user: []
        }
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

    fetchUser(uid) {
        fetch(USER_API_URL + uid)
            .then(response => response.json())
            .then(result =>
                this.setState({
                    user: result.data[0],
                    isLoaded: true
                })
            )
            .catch(error => error)
    }

    userHasPermission(permission) {
        let match = false;
        for (let i = 0; i < this.state.permissions.length; i++) {
            if (this.state.permissions[i].name === permission) {
                match = true;
            }
        }
        return match;
    }

    componentDidMount() {
        this.fetchAuthUser();
        this.fetchUser(uid);
        $('[data-toggle="tooltip"]').tooltip()
    }
    updateUser(data) {
        axios.post(UPDATE_USER_API + this.state.user.uid, data)
            .then((res) => {
                if (res.data.success) {
                    this.fetchUser(uid);
                }
            })
    }

    onSaveInput(event, field) {
        let data = {
            field: event,
            value: field
        };
        this.updateUser(data);
    }

    render() {
        if (!this.state.isLoaded) {
            return (
                <EclipseLoadingComponent/>
            )
        } else {
            const user = this.state.user;
            let userType = (user.user_type_name !== null) ? user.user_type_name.toProperCase() : null;
            return (
                <div className={"row"}>
                    <ReactTooltip />
                    <div className="card col-md-4 col-sm-12 account-right">
                        <div className="card-body">
                            <a href="/customers"><i className="lnr lnr-arrow-left"></i> &nbsp; Customers</a>
                            <div className="media mt-2">
                                <img className={"m-auto text-center"} src={user.avatar_url} alt=""
                                     style={{height: '80px', borderRadius: '100%'}}/>
                            </div>
                            <div className="media-body pt-2 ml-3 text-center">
                                <h4 className="mb-1">{user.name}</h4>
                                <div className="text-muted small">
                                    {(user.customer) ? 'Customer' : 'Non-customer | ' + userType}
                                </div>

                                <div className="mt-1">
                                    <span className="btn icon-btn btn-primary btn-sm md-btn-flat border border-primary user-action-btn"
                                          data-tip={"Send mesage"}>
                                        <span className="ion ion-md-mail"></span>
                                    </span>
                                    {user.locked ?
                                        <span className="btn icon-btn btn-primary btn-sm md-btn-flat border border-primary user-action-btn"
                                              data-tip={"Unlock account"}>
                                            <span className="ion ion-md-lock"></span>
                                        </span>
                                        :
                                        <span className="btn icon-btn btn-primary btn-sm md-btn-flat border border-primary user-action-btn"
                                              data-tip={"Lock account"}>
                                            <span className="ion ion-md-unlock"></span>
                                        </span>
                                    }
                                </div>
                            </div>
                        </div>
                        <hr className="border-light m-0"/>
                        <div className="card-body" style={{maxHeight: '400px', overflow: 'auto'}}>
                            <h5>About this customer</h5>
                            <div className="form-group">
                                <label htmlFor="customer-name" className="form-label">Name</label>
                                {(Permissions.hasAny(this.state.permissions, ['edit users', 'edit customers'])) ?
                                    <EditText
                                        type='text'
                                        mainContainerClassName={"react-editext-main"}
                                        saveButtonContent={<i className={'lnr lnr-checkmark-circle text-success'}></i>}
                                        cancelButtonContent={<i className={'lnr lnr-cross-circle text-danger'}></i>}
                                        editButtonContent={<span className={'text-primary'}>edit</span>}
                                        hideIcons={true}
                                        onSave={this.onSaveInput.bind(this, 'name')}
                                        value={(user.name === null) ? 'Not set' : user.name}
                                    />
                                    :  (user.name === null) ? <span className={"d-block"}>Not set</span> :
                                        <span className={"d-block"}>{user.name}</span> }
                            </div>
                            <div className="form-group">
                                <label htmlFor="customer-email" className="form-label">Email</label>
                                {(Permissions.hasAny(this.state.permissions, ['edit users', 'edit customers'])) ?
                                    <EditText
                                        type='text'
                                        mainContainerClassName={"react-editext-main"}
                                        saveButtonContent={<i className={'lnr lnr-checkmark-circle text-success'}></i>}
                                        cancelButtonContent={<i className={'lnr lnr-cross-circle text-danger'}></i>}
                                        editButtonContent={<span className={'text-primary'}>edit</span>}
                                        hideIcons={true}
                                        onSave={this.onSaveInput.bind(this, 'email')}
                                        value={(user.email === null) ? 'Not set' : user.email}
                                    />
                                    :  (user.email === null) ? <span className={"d-block"}>Not set</span> :
                                        <span className={"d-block"}>{user.email}</span> }
                            </div>

                            <div className="form-group">
                                <label htmlFor="customer-phone" className="form-label">Phone</label>
                                {(Permissions.hasAny(this.state.permissions, ['edit users', 'edit customers'])) ?
                                    <EditText
                                        type='text'
                                        mainContainerClassName={"react-editext-main"}
                                        saveButtonContent={<i className={'lnr lnr-checkmark-circle text-success'}></i>}
                                        cancelButtonContent={<i className={'lnr lnr-cross-circle text-danger'}></i>}
                                        editButtonContent={<span className={'text-primary'}>edit</span>}
                                        hideIcons={true}
                                        onSave={this.onSaveInput.bind(this, 'phone')}
                                        value={(user.phone === null) ? 'Not set' : user.phone}
                                    />
                                    :  (user.phone === null) ? <span className={"d-block"}>Not set</span> :
                                        <span className={"d-block"}>{user.phone}</span> }
                            </div>

                            <div className="form-group">
                                <label htmlFor="customer-phone" className="form-label">Assigned To</label>
                                {(this.userHasPermission('edit customer')) ?
                                <select name="assigned_to" id="assigned_to" className="select2 form-control"
                                style={{width: 'auto', paddingRight: '15px'}}>
                                    <option value="{user.assigned_to.uid}">{user.assigned_to.name}</option>
                                </select> :
                                <span className={"d-block"}>{user.assigned_to.name}</span>}
                            </div>

                        </div>
                    </div>
                    <div className="card col-md-8 col-sm-12 account-left p-0">
                        <div className="nav-tabs-top mb-4">
                            <ul className="nav nav-tabs">
                                <li className="nav-item">
                                    <a className="nav-link active" data-toggle="tab" href="#navs-top-account">Account</a>
                                </li>
                                <li className="nav-item">
                                    <a className="nav-link" data-toggle="tab" href="#navs-top-profile">Profile</a>
                                </li>
                                <li className="nav-item">
                                    <a className="nav-link" data-toggle="tab" href="#navs-top-messages">Messages</a>
                                </li>
                            </ul>
                            <div className="tab-content">
                                <div className="tab-pane fade active show" id="navs-top-account">
                                    <div className="card-body">
                                        <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth.
                                            Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan aliquip quis cardigan american apparel, butcher voluptate nisi qui.</p>
                                    </div>
                                </div>
                                <div className="tab-pane fade" id="navs-top-profile">
                                    <div className="card-body">
                                        <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress,
                                            commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit sapiente labore stumptown. Vegan fanny
                                            pack odio cillum wes anderson 8-bit, sustainable jean shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown, tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
                                    </div>
                                </div>
                                <div className="tab-pane fade" id="navs-top-messages">
                                    <div className="card-body">
                                        <p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade. Messenger bag gentrify pitchfork tattooed craft beer, iphone skateboard locavore carles etsy
                                            salvia banksy hoodie helvetica. DIY synth PBR banksy irony. Leggings gentrify squid 8-bit cred pitchfork. Williamsburg banh mi whatever gluten-free, carles pitchfork biodiesel fixie etsy retro mlkshk vice blog. Scenester cred you probably
                                            haven't heard of them, vinyl craft beer blog stumptown. Pitchfork sustainable tofu synth chambray yr.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )
        }
    }
}

if (document.getElementById('customer-account-wrapper')) {
    ReactDOM.render(<CustomerUserMain />, document.getElementById('customer-account-wrapper'));
}