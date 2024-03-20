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
    <div class="content">
    <div class="row">
        <div class="col-6">
            <h1>Data</h1>
        </div>
        <div class="col-6">
            <form action="{{ route('evaluasi') }}" method="GET" class="float-right">
                <div class="input-group">
                    <select name="id" style="width: 200px" class="form-control">
                        <option value="">Pilih Waktu</option>
                        @foreach ($evaluasi as $eval)
                            <option value="{{ $eval->id }}" {{ $eval->id == $id ? 'selected' : '' }}>{{ date('d M Y', strtotime($eval->created_at)) }}</option>
                        @endforeach
                    </select>
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <button class="btn btn-success float-right mr-2" onclick="printTable()">Print</button>
        </div>
    </div>
    <table id="dataTable" class="table table-striped">
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
                                if($date){
                                    $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                                                ->whereTime('created_at', '>=', $date->toTimeString()) 
                                                ->first();
                                }
                                else{
                                    $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
                                }
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

@php
    $min_cas1 = PHP_INT_MAX;
    $min_cas3 = PHP_INT_MAX;
    $max_cas2 = PHP_INT_MIN;
    $max_cas4 = PHP_INT_MIN;
@endphp

@foreach ($sahams as $saham)
    @foreach ($kriterias as $kriteria)
        @php
            if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
            if ($evaluasi) {
                switch ($kriteria->id) {
                    case 1:
                        if ($evaluasi->nilai <= 15) {
                            $min_cas1 = min($min_cas1, 1);
                        } elseif ($evaluasi->nilai >= 15.01 && $evaluasi->nilai <= 20.99) {
                            $min_cas1 = min($min_cas1, 2);
                        } elseif ($evaluasi->nilai >= 21) {
                            $min_cas1 = min($min_cas1, 3);
                        }
                        break;
                    case 2:
                        if ($evaluasi->nilai <= 10) {
                            $max_cas2 = max($max_cas2, 1);
                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 15.99) {
                            $max_cas2 = max($max_cas2, 2);
                        } elseif ($evaluasi->nilai >= 16) {
                            $max_cas2 = max($max_cas2, 3);
                        }
                        break;
                    case 3:
                        if ($evaluasi->nilai <= 1) {
                            $min_cas3 = min($min_cas3, 1);
                        } elseif ($evaluasi->nilai >= 1.01 && $evaluasi->nilai <= 2.09) {
                            $min_cas3 = min($min_cas3, 2);
                        } elseif ($evaluasi->nilai >= 2.1) {
                            $min_cas3 = min($min_cas3, 3);
                        }
                        break;
                    case 4:
                        if ($evaluasi->nilai <= 10) {
                            $max_cas4 = max($max_cas4, 1);
                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 30.99) {
                            $max_cas4 = max($max_cas4, 2);
                        } elseif ($evaluasi->nilai >= 31) {
                            $max_cas4 = max($max_cas4, 3);
                        }
                        break;
                    default:
                        // do nothing
                }
            }
        @endphp
    @endforeach
@endforeach

    <h1>Bobot Nilai</h1>
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
                        if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
                            if ($evaluasi) {
                                switch ($kriteria->id) {
                                    case 1:
                                        if ($evaluasi->nilai <= 15) {
                                            echo 1;
                                        } elseif ($evaluasi->nilai >= 15.01 && $evaluasi->nilai <= 20.99) {
                                            echo 2;
                                        } elseif ($evaluasi->nilai >= 21) {
                                            echo 3;
                                        }
                                        break;
                                    case 2:
                                        if ($evaluasi->nilai <= 10) {
                                            echo 1;
                                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 15.99) {
                                            echo 2;
                                        } elseif ($evaluasi->nilai >= 16) {
                                            echo 3;
                                        }
                                        break;
                                    case 3:
                                        if ($evaluasi->nilai <= 1) {
                                            echo 1;
                                        } elseif ($evaluasi->nilai >= 1.01 && $evaluasi->nilai <= 2.09) {
                                            echo 2;
                                        } elseif ($evaluasi->nilai >= 2.1) {
                                            echo 3;
                                        }
                                        break;
                                    case 4:
                                        if ($evaluasi->nilai <= 10) {
                                            echo 1;
                                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 30.99) {
                                            echo 2;
                                        } elseif ($evaluasi->nilai >= 31) {
                                            echo 3;
                                        }
                                        break;
                                    default:
                                        echo "Tidak tersedia";
                                }
                            } else {
                                echo "Tidak tersedia";
                            }
                        @endphp
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
</br>
    <h1>Normalisasi</h1>
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
                        if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString())
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
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
                                    default:
                                        $nilai = "Tidak tersedia";
                                }
                                if ($kriteria->atribut == 'Benefit') {
                                if ($kriteria->id == 2) {
                                    $nilai = (double)($nilai / $max_cas2);
                                } elseif ($kriteria->id == 4) {
                                    $nilai = (double)($nilai / $max_cas4);
                                }
                            } elseif ($kriteria->atribut == 'Cost') {
                                if ($kriteria->id == 1) {
                                    $nilai = (double)($min_cas1 / $nilai);
                                } elseif ($kriteria->id == 3) {
                                    $nilai = (double)($min_cas3 / $nilai);
                                }
                            }
                            echo $nilai;
                        } else {
                            echo "Tidak tersedia";
                        }
                        @endphp
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
                        </br>
                        <h1>Pengkalian</h1>
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
                        if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
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
                                    default:
                                        $nilai = "Tidak tersedia";
                                }
                                if ($kriteria->atribut == 'Benefit') {
                                if ($kriteria->id == 2) {
                                    $nilai = (double)($nilai / $max_cas2);
                                } elseif ($kriteria->id == 4) {
                                    $nilai = (double)($nilai / $max_cas4);
                                }
                            } elseif ($kriteria->atribut == 'Cost') {
                                if ($kriteria->id == 1) {
                                    $nilai = (double)($min_cas1 / $nilai);
                                } elseif ($kriteria->id == 3) {
                                    $nilai = (double)($min_cas3 / $nilai);
                                }
                            }
                                $nilai *= $kriteria->bobot;
                                echo $nilai;
                            } else {
                                echo "Tidak tersedia";
                            }
                        @endphp
                    </td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>
                        </br>
                        <h1>Penambahan</h1>
                        <table class="table table-striped">
    <thead>
        <tr>
            <th>Saham</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sahams as $saham)
            <tr>
                <td>{{ $saham->saham }}</td>
                <td>
                    @php
                        $totalNilai = 0;
                    @endphp
                    @foreach ($kriterias as $kriteria)
                        @php
                        if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
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
                                    default:
                                        $nilai = "Tidak tersedia";
                                }
                                if ($kriteria->atribut == 'Benefit') {
                                if ($kriteria->id == 2) {
                                    $nilai = (double)($nilai / $max_cas2);
                                } elseif ($kriteria->id == 4) {
                                    $nilai = (double)($nilai / $max_cas4);
                                }
                            } elseif ($kriteria->atribut == 'Cost') {
                                if ($kriteria->id == 1) {
                                    $nilai = (double)($min_cas1 / $nilai);
                                } elseif ($kriteria->id == 3) {
                                    $nilai = (double)($min_cas3 / $nilai);
                                }
                            }
                                $nilai *= $kriteria->bobot;
                                $totalNilai += $nilai;
                            }
                        @endphp
                    @endforeach
                    {{ number_format($totalNilai, 3, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
    @php
    $min_cas1 = PHP_INT_MAX;
    $min_cas3 = PHP_INT_MAX;
    $max_cas2 = PHP_INT_MIN;
    $max_cas4 = PHP_INT_MIN;
@endphp

    @foreach ($sahams as $saham)
    @foreach ($kriterias as $kriteria)
        @php
        if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
            if ($evaluasi) {
                switch ($kriteria->id) {
                    case 1:
                        if ($evaluasi->nilai <= 15) {
                            $min_cas1 = min($min_cas1, 1);
                        } elseif ($evaluasi->nilai >= 15.01 && $evaluasi->nilai <= 20.99) {
                            $min_cas1 = min($min_cas1, 2);
                        } elseif ($evaluasi->nilai >= 21) {
                            $min_cas1 = min($min_cas1, 3);
                        }
                        break;
                    case 2:
                        if ($evaluasi->nilai <= 10) {
                            $max_cas2 = max($max_cas2, 1);
                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 15.99) {
                            $max_cas2 = max($max_cas2, 2);
                        } elseif ($evaluasi->nilai >= 16) {
                            $max_cas2 = max($max_cas2, 3);
                        }
                        break;
                    case 3:
                        if ($evaluasi->nilai <= 1) {
                            $min_cas3 = min($min_cas3, 1);
                        } elseif ($evaluasi->nilai >= 1.01 && $evaluasi->nilai <= 2.09) {
                            $min_cas3 = min($min_cas3, 2);
                        } elseif ($evaluasi->nilai >= 2.1) {
                            $min_cas3 = min($min_cas3, 3);
                        }
                        break;
                    case 4:
                        if ($evaluasi->nilai <= 10) {
                            $max_cas4 = max($max_cas4, 1);
                        } elseif ($evaluasi->nilai >= 10.01 && $evaluasi->nilai <= 30.99) {
                            $max_cas4 = max($max_cas4, 2);
                        } elseif ($evaluasi->nilai >= 31) {
                            $max_cas4 = max($max_cas4, 3);
                        }
                        break;
                    default:
                        // do nothing
                }
            }
        @endphp
    @endforeach
@endforeach
<h1>Perangkingan</h1>
@php
    $totalNilaiPerSaham = [];
    foreach ($sahams as $saham) {
        $totalNilai = 0;
        foreach ($kriterias as $kriteria) {
            if($date){
                $evaluasi = App\Models\Evaluasi::where(['id_kriteria' => $kriteria->id, 'id_alternatif' => $saham->id])->whereDate('created_at', $date->toDateString()) 
                            ->whereTime('created_at', '>=', $date->toTimeString()) 
                            ->first();
            }
            else{
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->first();
            }
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
                    if ($kriteria->id == 2) {
                        $nilai = (double)($nilai / $max_cas2);
                    } elseif ($kriteria->id == 4) {
                        $nilai = (double)($nilai / $max_cas4);
                    }
                } elseif ($kriteria->atribut == 'Cost') {
                    if ($kriteria->id == 1) {
                        $nilai = (double)($min_cas1 / $nilai);
                    } elseif ($kriteria->id == 3) {
                        $nilai = (double)($min_cas3 / $nilai);
                    }
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

@php
    // Ambil 3 besar saham dari array asosiatif
    $topThree = array_slice($totalNilaiPerSaham, 0, 3, true);
@endphp

<div style="background-color: #f2f2f2; padding: 20px; border-radius: 10px;">
    <h2 style="color: #007bff;">Rekomendasi Saham Bluechip Terbaik</h2>
    <p>Setelah melakukan perhitungan berdasarkan fundamentalnya, berikut adalah rekomendasi saham bluechip terbaik untuk saat ini:</p>
    <ul style="list-style-type: none; padding-left: 0;">
        @foreach ($topThree as $saham => $totalNilai)
            <li style="margin-bottom: 20px;">
                <div style="display: flex; align-items: center;">
                    <div style="width: 30px; height: 30px; background-color: #007bff; color: #fff; border-radius: 50%; text-align: center; font-weight: bold; font-size: 18px; line-height: 30px; margin-right: 10px;">{{ $loop->iteration }}</div>
                    <div>
                        <h4 style="margin-bottom: 5px;">{{ $saham }}</h4>
                        <p style="margin: 0;">Total Nilai: {{ $totalNilai }}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    <p style="margin-top: 20px;">Pertimbangkan untuk memasukkan salah satu atau lebih dari saham-saham di atas ke dalam portofolio investasi Anda.</p>
    </div>
    </div>
    <script>
    function printTable() {
        var printContents = document.getElementById("dataTable").outerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        var style = '<style type="text/css">';
        style += '@page { size: landscape; }';
        style += '</style>';
        document.body.innerHTML = style + printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }

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