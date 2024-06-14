@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Akun Saya</a></li>
                    <li class="breadcrumb-item">Ganti Kata Sandi</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Ganti kata sandi</h2>
                        </div>
                        <form action="" method="POST" id="changePasswordForm" name="changePasswordForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Password Lama</label>
                                        <input type="password" name="old_password" id="old_password"
                                            placeholder="Password Lama" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Kata Sandi Baru</label>
                                        <input type="password" name="new_password" id="new_password"
                                            placeholder="Kata Sandi Baru" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Konfirmasi Sandi</label>
                                        <input type="password" name="confirm_password" id="confirm_password"
                                            placeholder="Konfirmasi Sandi" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button id="submit" name="submit" type="submit"
                                            class="btn btn-dark">Save</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script type="text/javascript">
        $('#changePasswordForm').submit(function(e) {
            e.preventDefault();
            $("#submit").prop('disable', false);

            $.ajax({
                url: '{{ route('account.processChangePassword') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Password berhasil diubah.',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "{{ route('account.changePassword') }}";
                            }
                        });
                    } else {
                        if (response.message) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            var errors = response.errors;

                            if (errors.old_password) {
                                $("#old_password").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.old_password)
                            } else {
                                $("#old_password").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("")
                            }

                            if (errors.new_password) {
                                $("#new_password").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.new_password)
                            } else {
                                $("#new_password").removeClass('is-invalid').siblings('p').removeClass(
                                    'invalid-feedback').html("")
                            }

                            if (errors.confirm_password) {
                                $("#confirm_password").addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback').html(errors.confirm_password)
                            } else {
                                $("#confirm_password").removeClass('is-invalid').siblings('p')
                                    .removeClass(
                                        'invalid-feedback').html("")
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Please check the form for errors.',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong! Please try again later.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    </script>
@endsection
