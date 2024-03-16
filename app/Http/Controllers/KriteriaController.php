<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Evaluasi;
use App\Http\Controllers\Controller;

class KriteriaController extends Controller
{
    // KriteriaController.php
public function updateBobot(Request $request, $id)
{
    $validatedData = $request->validate([
        'bobot' => 'required|numeric', // Atur validasi sesuai kebutuhan
    ]);

    $kriteria = Kriteria::findOrFail($id);
    $kriteria->bobot = $validatedData['bobot'];
    $kriteria->save();

    return response()->json(['message' => 'Nilai bobot berhasil diperbarui.']);
}

}
