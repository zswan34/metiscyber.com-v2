@extends("layouts.app-blank")
@section("content")

    <div class="authentication-wrapper authentication-2 px-4">
        <div class="authentication-inner py-5">
            <div class="d-flex justify-content-center">
                <!-- Form -->
                <form class="card card-sm">
                    <div class="p-4 p-sm-5">

                        <a class="text-blue" href="{{ route('get-sign-in') }}"><i class="lnr lnr-arrow-left"></i> Go Back</a>

                        <!-- Logo -->
                        <div class="d-flex justify-content-center align-items-center pb-2 mb-4">
                            <div class="ui-w-140">
                                <div class="w-100 position-relative" >
                                    <img class="bg-overlay w-100" src="{{ asset('assets/logos/mc-nb-logo.png') }}" alt="MetisNet">
                                </div>
                            </div>
                        </div>
                        <!-- / Logo -->

                        <h5 class="text-center text-muted font-weight-normal mb-4">Reset Your Password</h5>

                        <hr class="mt-0 mb-4">

                        <p>
                            Enter your email address and we will send you a link to reset your password.
                        </p>
                        <form id="forgot-password-form" action="{{ route('post-forgot-password') }}" method="post">
                            <div class="form-group">
                                <input name="email" type="text" class="form-control" placeholder="Enter your email address">
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Send password reset email</button>
                        </form>
                    </div>
                </form>
                <!-- / Form -->
            </div>
        </div>
    </div>

@endsection
@section("scripts")
    <script>
        let forgotPasswordForm = $("#forgot-password-form");

        forgotPasswordForm.validate({
            rules: {
                email: {
                    email: true,
                    required: true
                }
            },
            submitHandler: (form) => {
                console.log(form);
            }
        })
    </script>
@endsection