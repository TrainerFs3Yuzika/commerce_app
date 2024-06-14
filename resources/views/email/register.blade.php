<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Verifikasi Akun</title>
</head>
<body>
    <p>
        Halo <b>{{ $details['name'] }}</b>!
    </p>

    <p>
        Anda telah melakukan registrasi akun dengan menggunakan email ini.
    </p>

    <p>
        Berikut adalah data Anda:
    </p>

    <table>
        <tr>
            <td>Username</td>
            <td>:</td>
            <td>{{ $details['name'] }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $details['email'] }}</td>
        </tr>
        <tr>
            <td>Website</td>
            <td>:</td>
            <td>{{ $details['website'] }}</td>
        </tr>
        <tr>
            <td>Tanggal Registrasi</td>
            <td>:</td>
            <td>{{ $details['datetime'] }}</td>
        </tr>
    </table>

    <center>
        <h3>
            Buka link di bawah untuk melakukan verifikasi akun.
        </h3>

        <a href="{{ $details['verification_url'] }}" style="color: blue">{{ $details['verification_url'] }}</a>
    </center>

    <p>
        Terima kasih telah melakukan registrasi.
    </p>
</body>
</html>
