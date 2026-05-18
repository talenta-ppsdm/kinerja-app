<?php
namespace App\Enums;

enum PredicateEnum: string
{
    case SANGAT_BAIK = 'Sangat Baik';
    case BAIK = 'Baik';
    case BUTUH_PERBAIKAN = 'Butuh Perbaikan';
    case KURANG = 'Kurang';
    case SANGAT_KURANG = 'Sangat Kurang';
}