@extends("layouts.app")
@section("content")
    <h4 class="d-flex justify-content-between align-items-center w-100 font-weight-bold py-3 mb-4">
        <div>Customers</div>
        <button type="button" class="btn d-block btn-primary rounded-pill waves-effect"
                data-toggle="modal" data-target="#create-customer-modal">
            <span class="ion ion-md-add"></span>&nbsp; Add Customer</button>
    </h4>
    <!-- Filters -->
    <div class="ui-bordered px-4 pt-4 mb-4">
        <div class="form-row align-items-center">
            <div class="col-md mb-4">
                <label class="form-label">Verified</label>
                <select class="custom-select">
                    <option>Any</option>
                    <option>Yes</option>
                    <option>No</option>
                </select>
            </div>
            <div class="col-md mb-4">
                <label class="form-label">Role</label>
                <select class="custom-select">
                    <option>Any</option>
                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                        <option value="{{ str_replace(' ', '-', $role->name) }}">{{ ucwords($role->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md mb-4">
                <label class="form-label">Status</label>
                <select class="custom-select">
                    <option>Any</option>
                    <option>Active</option>
                    <option>Banned</option>
                    <option>Deleted</option>
                </select>
            </div>
            <div class="col-md mb-4">
                <label class="form-label">Latest activity</label>
                <input type="text" id="user-list-latest-activity" class="form-control" placeholder="Any">
            </div>
            <div class="col-md col-xl-2 mb-4">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="button" class="btn btn-secondary btn-block">Show</button>
            </div>
        </div>
    </div>
    <!-- / Filters -->

    <div class="card">
        <div class="card-datatable table-responsive">
            <table id="customer-table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    @include("pages.customers.modals.create-customer")
@endsection
@section('scripts')
    <script>
        let createCustomerForm = $("#create-customer-form");
        let createCustomerModal = $("#create-customer-modal");
        let assignedTo = $("#customer-assigned");

        assignedTo.select2({
            placeholder: 'Assigned to a user',
            allowClear: true
        });

        let customerTable = $("#customer-table");
        let table = customerTable.DataTable({
            serverSide: true,
            ajax: {
                url: "/api/v1/customers",
                type: 'GET'
            },
            columns: [
                {name: 'name', data: 'name',
                    render: (data, type, row) => {
                        return `<div class="d-inline-block" style="width: 30px; height: 30px;">
                            <img src="${row.avatar_url}" alt="" class="w-100" style="border-radius: 100%">
                        </div>
                        <a class="ml-2" href="${row.url}">${data.toProperCase()}</a>`;
                    }
                },
                {name: 'email', data: 'email'},
                {name: 'phone', data: 'phone',
                    render: (data) => {
                        return (data === null) ? 'Phone not provided' : data;
                    }
                },
                {name: 'verified', data: 'email_verified_at',
                    render: (data) => {
                        return (data === null) ? 'Email not verified' :
                            '<i class="lnr lnr-checkmark-circle text-success"></i> Verified';
                    }
                }
            ],
            createdRow: (row) => {
                let firstCell = $(row).find('td').first();
                firstCell.css('padding', '8px 15px');
            }
        });

        createCustomerModal.on('hidden.bs.modal', function () {
            $(this).find("input,textarea,select").val('').end();

        });

        createCustomerForm.validate({
            rules: {
                'customer-name': 'required',
                'customer-email': {
                    email: true,
                    required: true
                }
            },
            submitHandler: (form) => {
                let data = $(form).serialize();
                axios.post($(form).attr('action'), data)
                    .then((res) => {
                        if (res.data.success) {
                            $("#create-customer-modal").modal('hide');
                            table.ajax.reload();
                        }
                    })
            }
        });
    </script>
@endsection