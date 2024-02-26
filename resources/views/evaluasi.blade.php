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
            margin-left: 0;
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
    <button id="openSidebar">&#9776;</button>
    <div class="sidebar" id="sidebar">
        <h2>Fitur</h2>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('data.index') }}">Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('perhitungan') }}">Perhitungan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('evaluasi') }}">Evaluasi</a>
            </li>
        </ul>
    </div>
    <div class="header">
        <h1>Sistem Rekomendasi Saham</h1>
    </div>
    <div class="content">
        <h1>Perangkingan</h1>
        @php
    $totalNilaiPerSaham = [];
    foreach ($sahams as $saham) {
        $totalNilai = 0;
        foreach ($kriterias as $kriteria) {
            $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            if ($evaluasi) {
                $nilai = $evaluasi->nilai;
                switch ($kriteria->id) {
                    case 1:
                        if ($nilai <= 15) {
                            $nilai = 1.0;
                        } elseif ($nilai >= 15.01 && $nilai <= 20.99) {
                            $nilai = 2.0;
                        } elseif ($nilai >= 21) {
                            $nilai = 3.0;
                        }
                        break;
                    case 2:
                        if ($nilai <= 10) {
                            $nilai = 1.0;
                        } elseif ($nilai >= 10.01 && $nilai <= 15.99) {
                            $nilai = 2.0;
                        } elseif ($nilai >= 16) {
                            $nilai = 3.0;
                        }
                        break;
                    case 3:
                        if ($nilai <= 1) {
                            $nilai = 1.0;
                        } elseif ($nilai >= 1.01 && $nilai <= 2.09) {
                            $nilai = 2.0;
                        } elseif ($nilai >= 2.1) {
                            $nilai = 3.0;
                        }
                        break;
                    case 4:
                        if ($nilai <= 10) {
                            $nilai = 1.0;
                        } elseif ($nilai >= 10.01 && $nilai <= 30.99) {
                            $nilai = 2.0;
                        } elseif ($nilai >= 31) {
                            $nilai = 3.0;
                        }
                        break;
                }
                if ($kriteria->atribut == 'Benefit') {
                    $nilai = (double)($nilai / 3);
                } elseif ($kriteria->atribut == 'Cost') {
                    $nilai = (double)(1 / $nilai);
                }
                $nilai *= $kriteria->bobot;
                $totalNilai += $nilai;
            }
        }
        $totalNilaiPerSaham[$saham->saham] = $totalNilai;
    }
    arsort($totalNilaiPerSaham);
    $ranking = 1;
@endphp
<table class="table table-striped">
    <thead>
        <tr>
            <th>Ranking</th>
            <th>Saham</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($totalNilaiPerSaham as $saham => $totalNilai)
            <tr>
                <td>{{ $ranking++ }}</td>
                <td>{{ $saham }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
    </div>
    <script>
        const openSidebarBtn = document.getElementById('openSidebar');
        const sidebar = document.getElementById('sidebar');
        const content = document.querySelector('.content');

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