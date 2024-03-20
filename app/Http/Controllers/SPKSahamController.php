<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Evaluasi;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;

class SPKSahamController extends Controller
{
    public function index()
    {
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();

        return view('data', compact('sahams', 'kriterias'));
    }

    public function save(){
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();
    
        foreach($sahams as $saham){
            foreach($kriterias as $kriteria){
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->whereNull('created_at')->first();
                if($evaluasi){
                    Evaluasi::create([
                        'id_alternatif' => $saham->id,
                        'id_kriteria' => $kriteria->id,
                        'nilai' => $evaluasi->nilai,
                        'created_at' => now() 
                    ]);
                }
            }
        }
    
        Alert::success('Success', 'Data berhasil disimpan!');
        return redirect()->back();
    }
    

    public function evaluasi(Request $request)
    {
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();

        $alternatif_id = Alternatif::first()->id;
        $kriteria_id = Kriteria::first()->id;
        if($alternatif_id){
            $evaluasi = Evaluasi::where(['id_alternatif' => $alternatif_id, 'id_kriteria' => $kriteria_id])->whereNotNull('created_at')->get();
        }
        else{
            $alternatif_id = 0;
            $kriteria_id = 0;
            $evaluasi = Evaluasi::where(['id_alternatif' => $alternatif_id, 'id_kriteria' => $kriteria_id])->whereNotNull('created_at')->get();
        }

        $date = '';
        $id = '';
        if($request->id){
            $ev = Evaluasi::whereId($request->id)->first();
            $date = $ev->created_at;
            $id = $request->id;
        }
        
        return view('evaluasi', compact('sahams', 'kriterias', 'evaluasi', 'date', 'id'));
    }

    public function hitung()
{
    $sahams = Alternatif::all();
    $kriterias = Kriteria::all();

    return view('perhitungan', compact('sahams', 'kriterias'));
}
}
