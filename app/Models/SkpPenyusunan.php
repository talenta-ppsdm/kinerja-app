<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class SkpPenyusunan extends Model
{
    use HasFactory;

    protected $table = 'skp_penyusunan';
    protected $fillable = [
        'id',
        'status_skp',
        'skp_id',
    ];

    public function masterSkp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }
}
