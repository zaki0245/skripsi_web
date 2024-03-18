<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rekomendasi Saham</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: -250px;
            background-color: #698270;
            padding-top: 50px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: left 0.3s;
        }
        .sidebar h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 20px;
        }
        .nav-link {
            color: #fff;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
        }
        .content {
            padding: 20px;
            margin-top: 100px;
            padding-top: 20px;
            transition: margin-left 0.3s;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #698270;
            color: #fff;
            width: 100%;
            z-index: 500;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
            position: fixed;
            top: 0;
        }
        #openSidebar {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #698270;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            z-index: 1500;
        }
    </style>
</head>
<body>
    @include('sweetalert::alert')
    <button id="openSidebar">&#9776;</button>
    <div class="sidebar" id="sidebar">
        <h2>Fitur</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('data.index') }}">Data Aktual</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('perhitungan') }}">Manajemen Bobot</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('evaluasi') }}">Evaluasi</a>
            </li>
        </ul>
    </div>
    <div class="header">
        <h1>Sistem Rekomendasi Saham</h1>
    </div>
    <div class="content" id="content">
        <div class="row">
            <div class="col-6">
                <h1>Data Realtime</h1>
            </div>
            <div class="col-6">
                <a href="{{ route('data.save') }}" class="btn btn-primary float-right">Simpan</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Saham</th>
                    @foreach ($kriterias as $kriteria)
                        <th>{{ $kriteria->indikator }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($sahams as $saham)
                    <tr>
                        <td>{{ $saham->saham }}</td>
                        @foreach ($kriterias as $kriteria)
                            <td>
                                @php
                                    $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
                                @endphp
                                @if ($evaluasi)
                                    {{ $evaluasi->nilai }}
                                @else
                                    Tidak tersedia
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <script>
        const openSidebarBtn = document.getElementById('openSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');
        
        openSidebarBtn.addEventListener('click', () => {
            if (sidebar.style.left === '0px') {
                sidebar.style.left = '-250px';
                content.style.marginLeft = '0';
            } else {
                sidebar.style.left = '0';
                content.style.marginLeft = '250px';
            }
        });
    </script>
</body>
</html>
