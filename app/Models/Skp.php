<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skp extends Model
{
    use HasFactory;

    protected $table = 'skp';
    protected $fillable = [
        'nama',
        'nip',
        'status_pegawai',
        'jabatan',
        'golongan',
        'unit_organisasi',
        'unit_kerja',
        'eselon',
        'tagging_atasan',
        'ppk',
        'periode',  
        'tahun',
    ];
}
