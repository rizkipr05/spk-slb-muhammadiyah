<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the students assigned to the authenticated teacher.
     */
    public function index(): View
    {
        $siswas = Siswa::where('guru_id', auth()->id())
            ->with('penilaians')
            ->latest()
            ->get();

        return view('guru.siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        return view('guru.siswa.create');
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nisn' => 'nullable|string|unique:siswas,nisn',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'jenis_kebutuhan_khusus' => 'nullable|string',
        ]);

        $validated['guru_id'] = auth()->id();

        Siswa::create($validated);

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Siswa $siswa): View
    {
        if ($siswa->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data siswa ini.');
        }

        return view('guru.siswa.edit', compact('siswa'));
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        if ($siswa->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah data siswa ini.');
        }

        $validated = $request->validate([
            'nisn' => 'nullable|string|unique:siswas,nisn,'.$siswa->id,
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'jenis_kebutuhan_khusus' => 'nullable|string',
        ]);

        $siswa->update($validated);

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy(Siswa $siswa): RedirectResponse
    {
        if ($siswa->guru_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus data siswa ini.');
        }

        $siswa->penilaians()->delete();
        $siswa->delete();

        return redirect()->route('guru.siswa.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
