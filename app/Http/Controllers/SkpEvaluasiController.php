<?php

namespace App\Http\Controllers;

use App\Actions\ImportEvaluasi;
use Illuminate\Http\Request;

class SkpEvaluasiController extends Controller
{
    public function import(Request $request, ImportEvaluasi $importEvaluasi)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls'
        ]);
        try {
            $importEvaluasi->execute($request->file('file_excel')->getRealPath());

            return redirect()->back()->with('success', 'Data SKP berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}
