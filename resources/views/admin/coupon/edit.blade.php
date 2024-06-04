@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Kode Kupon</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('coupons.index') }}" class="btn btn-primary">Kembali</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="discountForm" name="discountForm">
                <div class= "card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">Kode</label>
                                    <input value="{{ $coupon->code }}" type="text" name="code" id="code"
                                        class="form-control" placeholder="Kode Kupon" autofocus>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Nama</label>
                                    <input value="{{ $coupon->name }}" type="text" name="name" id="name"
                                        class="form-control" placeholder="Nama Kupon">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses">Maksimal Penggunaan</label>
                                    <input value="{{ $coupon->max_uses }}" type="number" name="max_uses" id="max_uses"
                                        class="form-control" placeholder="Maksimal Penggunaan">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses_user">Maksimal Penggunaan User</label>
                                    <input value="{{ $coupon->max_uses_user }}" type="text" name="max_uses_user"
                                        id="max_uses_user" class="form-control" placeholder="Maksimal Penggunaan User">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type">Jenis Kupon</label>
                                    <select name="type" id="type" class="form-control">
                                        <option {{ $coupon->type == 'percent' ? 'selected' : '' }} value="percent">Persen
                                        </option>
                                        <option {{ $coupon->type == 'fixed' ? 'selected' : '' }} value="fixed">Tetap
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount">Jumlah Diskon</label>
                                    <input value="{{ $coupon->discount_amount }}" type="text" name="discount_amount"
                                        id="discount_amount" class="form-control" placeholder="Jumlah Diskon">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount">Jumlah Minimal Diskon</label>
                                    <input value="{{ $coupon->min_amount }}" type="text" name="min_amount"
                                        id="min_amount" class="form-control" placeholder="Jumlah Diskon Minimal">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $coupon->type == 1 ? 'selected' : '' }} value="1">Aktif</option>
                                        <option {{ $coupon->type == 0 ? 'selected' : '' }} value="0">Tidak Aktif
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at">Tanggal Mulai</label>
                                    <input value="{{ $coupon->starts_at }}" autocomplete="off" type="text"
                                        name="starts_at" id="starts_at" class="form-control" placeholder="Tanggal Mulai">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at">Tanggal Selesai</label>
                                    <input value="{{ $coupon->expires_at }}" autocomplete="off" type="text"
                                        name="expires_at" id="expires_at" class="form-control"
                                        placeholder="Tanggal Kadaluarsa">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="description" cols="30" rows="10"
                                        placeholder="Deskripsi">{{ $coupon->description }}</textarea>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('coupons.index') }}" class="btn btn-outline-dark ml-3">Batal</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        var today = new Date();
        var minDate = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate())
            .slice(-2);

        $(document).ready(function() {
            $('#starts_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
                minDate: minDate,
            });
        });
        $(document).ready(function() {
            $('#expires_at').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
                minDate: minDate,
            });
        });

        $("#discountForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);

            $.ajax({
                url: '{{ route('coupons.update', $coupon->id) }}',
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response["status"] == true) {

                        window.location.href = "{{ route('coupons.index') }}";

                        $("#code").removeClass("is-invalid")
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");
                        $("#discount_amount").removeClass("is-invalid")
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                        $("#starts_at").removeClass("is-invalid")
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");

                        $("#expires_at").removeClass("is-invalid")
                            .siblings('p').removeClass('invalid-feedback')
                            .html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['code']) {
                            $("#code").addClass("is-invalid")
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['code']);
                        } else {
                            $("#code").removeClass("is-invalid")
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                        if (errors['discount_amount']) {
                            $("#discount_amount").addClass("is-invalid")
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['discount_amount']);
                        } else {
                            $("#discount_amount").removeClass("is-invalid")
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                        if (errors['starts_at']) {
                            $("#starts_at").addClass("is-invalid")
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['starts_at']);
                        } else {
                            $("#starts_at").removeClass("is-invalid")
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                        if (errors['expires_at']) {
                            $("#expires_at").addClass("is-invalid")
                                .siblings('p').addClass('invalid-feedback')
                                .html(errors['expires_at']);
                        } else {
                            $("#expires_at").removeClass("is-invalid")
                                .siblings('p').removeClass('invalid-feedback')
                                .html("");
                        }
                    }
                },
                error: function(jqXHR, execption) {
                    console.log("Something went wrong");
                }
            })
        });
    </script>
@endsection
