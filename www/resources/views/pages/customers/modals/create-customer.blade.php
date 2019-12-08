<div class="modal fade" id="create-customer-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="create-customer-form" action="{{ route('post-customers-create') }}" method="post" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Customer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="customer-name" class="form-label">Name</label>
                            <input class="form-control" type="text" id="customer-name" name="customer-name" />
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="customer-email" class="form-label">Email</label>
                            <input class="form-control" type="email" id="customer-email" name="customer-email" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="customer-phone" class="form-label">Phone</label>
                            <input class="form-control" type="tel" id="customer-phone" name="customer-phone" />
                        </div>

                        <div class="form-group col-md-6 col-sm-12">
                            <label for="customer-assigned" class="form-label">Assigned To</label>
                            <select class="form-control select2" id="customer-assigned" name="customer-assigned">
                                @foreach(\App\User::all() as $user)
                                    <option value="{{ $user->uid }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label class="switcher my-1">
                                <input type="checkbox" class="switcher-input" name="customer-verification-email">
                                <span class="switcher-indicator">
                                        <span class="switcher-yes"></span>
                                        <span class="switcher-no"></span>
                                    </span>
                                <span class="switcher-label">Send verification email</span>
                            </label>
                            <label class="switcher my-1">
                                <input type="checkbox" class="switcher-input" name="customer-change-password">
                                <span class="switcher-indicator">
                                        <span class="switcher-yes"></span>
                                        <span class="switcher-no"></span>
                                    </span>
                                <span class="switcher-label">Send information request email</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>