<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Evaluasi;
use App\Http\Controllers\Controller;

class SPKSahamController extends Controller
{
    public function index()
    {
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();

        return view('data', compact('sahams', 'kriterias'));
    }

    public function evaluasi()
    {
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();
        
        return view('evaluasi', compact('sahams', 'kriterias'));
    }

    public function hitung()
{
    $sahams = Alternatif::all();
    $kriterias = Kriteria::all();

    return view('perhitungan', compact('sahams', 'kriterias'));
}
}
