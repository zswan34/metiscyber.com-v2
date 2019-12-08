<!DOCTYPE html>

<html lang="en" class="material-style">

<head>
    <title>{{ config('app.name') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('assets/logos/favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/logos/favicon.ico') }}" type="image/x-icon">
    <link rel="manifest" href="{{ asset('assets/ico/site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('assets/ico/safari-pinned-tab.svg') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#292929">
    <meta name="theme-color" content="#292929">
    <link href="https://unpkg.com/ionicons@4.5.5/dist/css/ionicons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">

    <!-- Icon fonts -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/ionicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/linearicons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/open-iconic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/pe-icon-7-stroke.css') }}">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/bootstrap-material.css') }}" class="theme-settings-bootstrap-css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/appwork-material.css') }}" class="theme-settings-appwork-css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-corporate-material.css') }}" class="theme-settings-theme-css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/colors-material.css') }}" class="theme-settings-colors-css">
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/uikit.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

    <!-- Load polyfills -->
    <script src="{{ asset('assets/vendor/js/polyfills.js') }}"></script>
    <script>document['documentMode']===10&&document.write('<script src="https://polyfill.io/v3/polyfill.min.js?features=Intl.~locale.en"><\/script>')</script>

    <script src="{{ asset('assets/vendor/js/material-ripple.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/layout-helpers.js') }}"></script>

    <!-- Theme settings -->
    <!-- This file MUST be included after core stylesheets and layout-helpers.js in the <head> section -->
    <script src="{{ asset('assets/vendor/js/theme-settings.js') }}"></script>
    <script>
        /* window.themeSettings = new ThemeSettings({
             cssPath: 'assets/vendor/css/rtl/',
             themesPath: 'assets/vendor/css/rtl/'
         }); */
    </script>

    <!-- Core scripts -->
    <script src="{{ asset('assets/vendor/js/pace.js') }}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Libs -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/authentication.css') }}">
</head>

<body>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>

<!-- Content -->

<div class="authentication-wrapper authentication-1 px-4">
    <div class="authentication-inner py-5">

        <!-- Logo -->
        <div class="d-flex justify-content-center align-items-center">
            <div style="width: 60%;">
                <img class="w-100" src="{{ asset('assets/logos/mc-nb-logo.png') }}" alt="">
            </div>
            <div id="sign-in-error" class="mt-2 bg-danger p-2 rounded-lg shadow-lg" style="display: none;">
                <div class="text-white"><i class="ion ion-md-information-circle"></i>
                    <span id="sign-in-error-text" class="test-white"></span>
                </div>
            </div>
        </div>
        <!-- / Logo -->

        <!-- Form -->
        <form id="sign-in-form" class="my-5" action="{{ route('post-sign-in') }}" method="post" autocomplete="off">
            <div class="form-group">
                <label class="form-label">Email</label>
                <input name="email" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label d-flex justify-content-between align-items-end">
                    <div>Password</div>
                    <a href="{{ route('get-forgot-password') }}" class="d-block small">Forgot password?</a>
                </label>
                <input name="password" type="password" class="form-control">
            </div>
            <div class="d-flex justify-content-between align-items-center m-0">
                <label class="custom-control custom-checkbox m-0">
                    <input type="checkbox" class="custom-control-input" name="remember">
                    <span class="custom-control-label">Remember me</span>
                </label>
                <button type="button" class="btn btn-primary">Sign In</button>
            </div>
        </form>
        <!-- / Form -->

        <div class="text-center text-muted">
            Don't have an account yet? <a href="{{ route('get-create-account') }}">Sign Up</a>
        </div>

    </div>
</div>
<!-- / Content -->

<!-- Core scripts -->
<script src="{{ asset('assets/jquery-validation/dist/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/jquery-validation/dist/additional-methods.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/js/sidenav.js') }}"></script>

<!-- Libs -->
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

<!-- Demo -->
<script src="{{ asset('assets/js/demo.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    let signInForm = $("#sign-in-form");
    let signInError = $("#sign-in-error");
    let signInErrorText = $("#sign-in-error-text");

    signInForm.validate({
        focusInvalid: false,
        rules: {
            email: {
                required: true,
                email: true
            },
            password: 'required'
        },
        messages: {
            email: {
                email: 'Must enter valid email address',
                required: 'An email address is required'
            },
            password: 'Password is required'
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
            signInError.hide();
            axios.post($(form).attr('action'), data)
                .then((res) => {
                    const data = res.data;
                    if (data.success) {
                        window.location.href = data.redirect;
                    } else {
                        signInError.show();
                        signInErrorText.text(data.message)
                    }
                }).catch((err) => {
                if (err.request.status === 500) {
                    signInError.show();
                    signInErrorText.text('An unexpected error occurred. Try refreshing the page.');
                }
            });
        }
    })
</script>
</body>

</html>