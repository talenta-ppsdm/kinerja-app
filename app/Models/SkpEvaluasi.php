<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkpEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'skp_evaluasi';
    protected $fillable = [
        'id',
        'predikat_tw1',
        'predikat_tw2',
        'predikat_tw3',
        'predikat_tw4',
        'skp_id',
    ];

    public function masterSkp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }
}
