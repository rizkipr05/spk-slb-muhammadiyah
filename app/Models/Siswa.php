<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
