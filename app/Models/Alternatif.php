<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    protected $table = 'alternatif';

    protected $fillable = ['saham'];

    public function evaluasi()
    {
        return $this->hasMany(Evaluasi::class, 'id_alternatif');
    }
}
