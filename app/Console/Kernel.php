<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Evaluasi;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
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
        })->everyHour();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
