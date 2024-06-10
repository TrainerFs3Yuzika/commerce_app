<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('admin-assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('admin-assets/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/custom.css') }}">
    <!-- Custom CSS for layout -->
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            display: flex;
            width: 100%;
            max-width: 1300px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .login-image {
            flex: 1;
            background: url('{{ asset('https://i.pinimg.com/564x/ef/a6/77/efa677de2afbd457f8d9424bbd027938.jpg') }}') no-repeat center center;
            background-size: cover;
            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;
        }

        .login-form {
            flex: 1;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            text-align: center;
            padding-bottom: 20px;
            font-size: 56px;
        }

        .login-box-msg {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            width: 100%;
            border-radius: 30px;
            padding: 10px;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .invalid-feedback {
            display: block;
        }

        .input-group-text {
            background-color: #fff;
            border-left: 0;
            border-radius: 0 30px 30px 0;
        }

        .input-group-prepend .input-group-text {
            border-radius: 30px 0 0 30px;
        }

        .input-group .form-control {
            border-right: 0;
            border-radius: 30px;
            padding: 10px 20px;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .forgot-password {
            text-align: center;
            display: block;
            margin-top: 10px;
            font-size: 14px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .form-control {
            padding: 10px 20px;
        }

        .input-group-icon {
            border-top-left-radius: 30px;
            border-bottom-left-radius: 30px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
            background-color: #e9ecef;
        }

        /* Style to add asterisk (*) to required input fields */
        .required-field::after {
            content: '*';
            color: red;
            margin-left: 5px;
        }
    </style>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-image"></div>
        <div class="login-form">
            @include('admin.message')
            <div class="login-header">
                <a href="#" class="h3">Selamat Datang</a>
                <p class="login-box-msg">Login Admin</p>

                <form id="loginForm" action="{{ route('admin.authenticate') }}" method="post">
                    @csrf
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-icon">
                                <span class="fas fa-user required-field"></span>
                            </div>
                        </div>
                        <input type="email" value="{{ old('email') }}" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email Anda">
                        @error('email')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text input-group-icon">
                                <span class="fas fa-lock required-field"></span>
                            </div>
                        </div>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Masukkan password Anda">
                        @error('password')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
        </div>
        <!-- jQuery -->
        <script src="{{ asset('admin-assets/plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('admin-assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('admin-assets/js/adminlte.min.js') }}"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="{{ asset('admin-assets/js/demo.js') }}"></script>
        <!-- Custom Script to set focus on the first input field -->
        <script>
            // Set focus to the first input field when the page loads
            document.addEventListener("DOMContentLoaded", function() {
                document.getElementById("email").focus();
            });
        </script>
</body>

</html>
