<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = ['indikator', 'bobot', 'atribut'];

    public function evaluasis()
    {
        return $this->hasMany(Evaluasi::class, 'id_kriteria');
    }
}
