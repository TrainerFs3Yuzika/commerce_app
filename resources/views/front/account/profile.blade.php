@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">Akun saya</a></li>
                    <li class="breadcrumb-item">Pengaturan</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Informasi pribadi</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="d-flex justify-content-center input-group">
                                        <img src="{{ asset('uploads/profile_images/' . (Auth::user()->profile_image ? Auth::user()->profile_image : 'user-default.png')) }}"
                                            class="rounded-circle img-fluid" style="width: 200px; border: 2px solid gray;"
                                            id="profileImage" />
                                    </div>
                                    <div class="mb-3">
                                        <label for="name">Nama</label>
                                        <input value="{{ $user->name }}" type="text" name="name" id="name"
                                            placeholder="Masukkan nama anda" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $user->email }}" type="text" name="email" id="email"
                                            placeholder="Masukkan email anda" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Nomor Telepon</label>
                                        <input value="{{ $user->phone }}" type="text" name="phone" id="phone"
                                            placeholder="Masukkan nomor anda" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profile_image">Foto Profil</label>
                                        <input type="file" name="profile_image" id="profile_image" class="form-control"
                                            accept="image/jpeg, image/png, image/jpg">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Ubah</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Alamat</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Nama depan</label>
                                        <input value="{{ !empty($address) ? $address->first_name : '' }}" type="text"
                                            name="first_name" id="first_name" placeholder="Masukkan Nama Depan Anda"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name">Nama Belakang</label>
                                        <input value="{{ !empty($address) ? $address->last_name : '' }}" type="text"
                                            name="last_name" id="last_name" placeholder="Masukkan Nama Belakang Anda"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ !empty($address) ? $address->email : '' }}"" type="text"
                                            name="email" id="email" placeholder="Masukkan Email Anda"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Nomor Telepon</label>
                                        <input value="{{ !empty($address) ? $address->mobile : '' }}" name="mobile"
                                            id="mobile" placeholder="Masukkan No Ponsel Anda." class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Negara</label>
                                        <select name="country_id" id="country_id" class="form-control">
                                            <option value="">Pilih Negara</option>
                                            @if ($countries->isNotEmpty())
                                                @foreach ($countries as $country)
                                                    <option
                                                        {{ !empty($address) && $address->country_id == $country->id ? 'selected' : '' }}
                                                        value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Alamat</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control"
                                            placeholder="Masukkan Alamat Anda">{{ !empty($address) ? $address->address : '' }}
                                        </textarea>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Kecamatan</label>
                                        <input value="{{ !empty($address) ? $address->apartment : '' }}" type="text"
                                            name="apartment" id="apartment" placeholder="Apartment"
                                            class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Kota</label>
                                        <input value="{{ !empty($address) ? $address->city : '' }}" type="text"
                                            name="city" id="city" placeholder="Kota" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Provinsi</label>
                                        <input value="{{ !empty($address) ? $address->state : '' }}" type="text"
                                            name="state" id="state" placeholder="Negara" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone">Kode Pos</label>
                                        <input value="{{ !empty($address) ? $address->zip : '' }}" type="text"
                                            name="zip" id="zip" placeholder="Kode Pos" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button class="btn btn-dark">Ubah</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $("#profile_image").change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#profileImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        });

        // Handle error profile form
        function handleProfileFormErrors(errors) {
            if (errors.name) {
                $("#profileForm #name")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.name)
                    .addClass('invalid-feedback');
            } else {
                $("#profileForm #name")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.email) {
                $("#profileForm #email")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.email)
                    .addClass('invalid-feedback');
            } else {
                $("#profileForm #email")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.phone) {
                $("#profileForm #phone")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.phone)
                    .addClass('invalid-feedback');
            } else {
                $("#profileForm #phone")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }
        }

        // Form profil
        $("#profileForm").submit(function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Apakah anda ingin memperbarui profil ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ubah!',
                cancelButtonText: 'Tidak, Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);

                    $.ajax({
                        url: '{{ route('account.updateProfile') }}',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: 'Profil berhasil diperbarui',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('account.profile') }}';
                                });
                            } else {
                                handleProfileFormErrors(response.errors);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal memperbarui profil',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });

        // Handle error form alamat
        function handleAddressFormErrors(errors) {
            if (errors.first_name) {
                $("#first_name")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.first_name)
                    .addClass('invalid-feedback');
            } else {
                $("#first_name")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.last_name) {
                $("#last_name")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.last_name)
                    .addClass('invalid-feedback');
            } else {
                $("#last_name")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.email) {
                $("#addressForm #email")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.email)
                    .addClass('invalid-feedback');
            } else {
                $("#addressForm #email")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.mobile) {
                $("#mobile")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.mobile)
                    .addClass('invalid-feedback');
            } else {
                $("#mobile")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.country_id) {
                $("#country_id")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.country_id)
                    .addClass('invalid-feedback');
            } else {
                $("#country_id")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.address) {
                $("#address")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.address)
                    .addClass('invalid-feedback');
            } else {
                $("#address")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.apartment) {
                $("#apartment")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.apartment)
                    .addClass('invalid-feedback');
            } else {
                $("#apartment")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.city) {
                $("#city")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.city)
                    .addClass('invalid-feedback');
            } else {
                $("#city")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.state) {
                $("#state")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.state)
                    .addClass('invalid-feedback');
            } else {
                $("#state")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }

            if (errors.zip) {
                $("#zip")
                    .addClass('is-invalid')
                    .siblings('p')
                    .html(errors.zip)
                    .addClass('invalid-feedback');
            } else {
                $("#zip")
                    .removeClass('is-invalid')
                    .siblings('p')
                    .html('')
                    .removeClass('invalid-feedback');
            }
        }

        // Form alamat
        $("#addressForm").submit(function(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Apakah akan ingin memperbarui alamat ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, perbarui!',
                cancelButtonText: 'Tidak, Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('account.updateAddress') }}',
                        type: 'post',
                        data: $(this).serializeArray(),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == true) {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: 'Alamat berhasil diperbarui',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.href =
                                        '{{ route('account.profile') }}';
                                });
                            } else {
                                handleAddressFormErrors(response.errors);
                            }
                        },
                        error: function(xhr, status, error) {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Gagal memperbarui alamat',
                                icon: 'error'
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
