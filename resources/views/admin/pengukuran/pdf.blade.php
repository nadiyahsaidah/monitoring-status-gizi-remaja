<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengukuran</title>
    <style>
        /* Gaya CSS sesuaikan sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Laporan Pengukuran {{$start_date}}- {{ $end_date}}</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Tanggal Pengukuran</th>
                <th>Usia</th>
                <th>BB</th>
                <th>TB</th>
                <th>Status Gizi</th>
                <th>LILA</th>
                <th>Tensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengukurans as $key => $pengukuran)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $pengukuran->remaja->user->username }}</td>
                <td>{{ $pengukuran->remaja->user->nama }}</td>
                <td>{{ $pengukuran->remaja->jenis_kelamin }}</td>
                <td>{{ $pengukuran->remaja->tanggal_lahir }}</td>
                <td>{{ $pengukuran->tanggal_pengukuran }}</td>
                <td>{{ \Carbon\Carbon::parse($pengukuran->remaja->tanggal_lahir)->age }}</td>
                <td>{{ $pengukuran->bb }}</td>
                <td>{{ $pengukuran->tb }}</td>
                <td>{{ $pengukuran->status_gizi }}</td>
                <td>{{ $pengukuran->lila }}</td>
                <td>{{ $pengukuran->tensi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
