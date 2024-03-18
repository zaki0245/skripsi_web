<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Evaluasi;

class SaveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:save-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sahams = Alternatif::all();
        $kriterias = Kriteria::all();

        foreach($sahams as $saham){
            foreach($kriterias as $kriteria){
                $evaluasi = $saham->evaluasi->where('id_kriteria', $kriteria->id)->whereNull('created_at')->first();
                if($evaluasi){
                    Evaluasi::create([
                        'id_alternatif' => $saham->id,
                        'id_kriteria' => $kriteria->id,
                        'nilai' => $evaluasi->nilai
                    ]);
                }
            }
        }
    }
}
