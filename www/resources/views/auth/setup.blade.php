@extends("layouts.app-blank")
@section('content')

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="card col-6 col-offset-3 mt-4 mb-3">
                <div class="card-body">
                    <div class="mb-3 text-center">
                        <img style="width: 35%;" src="{{ asset('assets/logos/mc-nb-logo.png') }}" alt="">
                    </div>
                    <h4 class="card-title">Hi {{ auth()->user()->fname() }}!</h4>
                    <form class="my-3" action="{{ route('post-setup') }}" id="setup-form" method="post" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="timezone">Timezone</label>
                            <select id="timezone" type="text" class="form-control select2" name="timezone">
                                @foreach(\App\Timezone::all() as $timezone)
                                    @php
                                        $default = '';
                                        $tz = \App\Timezone::find(auth()->user()->timezone_id);
                                         if ($tz->value === $timezone->value) {
                                            $default = 'selected';
                                        }
                                    @endphp
                                    <option value="{{ $timezone->value }}" {{ $default }}>{{ $timezone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label" for="user-country">Country</label>
                                <select class="form-control select2" name="country" id="user-country"></select>
                            </div>

                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label" for="user-state">State</label>
                                <select class="form-control select2" name="state" id="user-state" disabled></select>
                            </div>

                            <div class="form-group col-md-4 col-sm-12">
                                <label class="form-label" for="user-city">City</label>
                                <select class="form-control select2" name="city" id="user-city" disabled></select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Finish</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section("scripts")
    <script>
    let setupForm = $("#setup-form");
    let country = $("#user-country");
    let state = $("#user-state");
    let city = $("#user-city");

    let countries = [];
    let states = [];
    let cities = [];

    $.get('https://apis.metisnet.io/geodb/countries')
        .then((res) => {
            for(let i = 0; i < res.data.length; i++) {
                let obj = {
                    id: res.data[i].iso2,
                    text: res.data[i].name
                };
                countries.push(obj);
            }
            country.select2({
                data: countries
            })
        });

    country.change((e) => {
        state.empty();
        states = [];
        state.prop('disabled', false);

        city.empty();
        cities = [];
        city.prop('disabled', true);

        let iso2 = $(e.target).val();

        $.get('https://apis.metisnet.io/geodb/countries/' + iso2 + '/states')
            .then((res) => {
                console.log(res);
                for(let i = 0; i < res.data.length; i++) {
                    let obj = {
                        id: res.data[i].iso2,
                        text: res.data[i].name
                    };
                    states.push(obj);
                }
                state.select2({
                    data: states
                })
            });

    });

    state.change((e) => {
        city.empty();
        cities = [];
        city.prop('disabled', false);
        let countryIso2 = country.val();
        let iso2 = $(e.target).val();

        $.get('https://apis.metisnet.io/geodb/countries/' + countryIso2 + '/states/' + iso2 + '/cities')
            .then((res) => {
                console.log(res);
                for(let i = 0; i < res.data.length; i++) {
                    let obj = {
                        id: res.data[i].name,
                        text: res.data[i].name
                    };
                    cities.push(obj);
                }
                city.select2({
                    data: cities
                })
            });
    });
    setupForm.validate({
        rules: {
            timezone: 'required',
            'secondary-email': {
                required: true,
                email: true
            }
        },
        messages: {
            timezone: 'A timezone is required',
            'secondary-email': {
                email: 'Must be a valid email address',
                required: 'Email is required'
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

            axios.post($(form).attr('action'), data)
                .then((res) => {
                    const data = res.data;
                    console.log(data);
                    if (data.success) {
                        window.location.href = '/home';
                    }
                }).catch((err) => {
                    console.log(err);
            })
        }
    });

    $('.select2').each(function() {
        $(this)
            .wrap('<div class="position-relative"></div>')
            .select2({
                placeholder: 'Select value',
                dropdownParent: $(this).parent()
            });
    })
    </script>
@endsection
