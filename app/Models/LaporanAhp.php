<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanAhp extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data_hasil' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
