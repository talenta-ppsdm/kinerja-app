<?php

namespace Database\Seeders;

use App\Models\MasterKriteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MasterKriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterKriteria::insert([
            [
                'nama_kriteria' => 'Tautan tidak dapat dibuka',
            ],
            [
                'nama_kriteria' => 'Bukti dukung kosong'
            ],
            [
                'nama_kriteria' => 'Realisasi berdasarkan bukti dukung tidak proporsional'
            ],
        ]);
    }
}
