<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        // Currently a placeholder interface matching the task
        return view('kepsek.laporan.index');
    }
}
