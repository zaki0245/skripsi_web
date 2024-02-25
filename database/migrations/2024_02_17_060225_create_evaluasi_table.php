<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluasiTable extends Migration
{
    public function up()
    {
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_alternatif');
            $table->unsignedBigInteger('id_kriteria');
            $table->double('nilai');
            $table->timestamps();

            $table->foreign('id_alternatif')->references('id')->on('alternatif')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id')->on('kriteria')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluasi');
    }
}
