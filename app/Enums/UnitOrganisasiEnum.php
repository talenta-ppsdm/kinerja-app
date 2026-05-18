<?php
namespace App\Enums;

enum UnitOrganisasiEnum: string
{
    case SEKJEN = 'Sekretariat Jenderal';
    case DITJEN_KP = 'Direktorat Jenderal Kawasan Permukiman';
    case DITJEN_DESA = 'Direktorat Jenderal Perumahan Perdesaan';
    case DITJEN_KOTA = 'Direktorat Jenderal Perumahan Kota';
    case DITJEN_TKPR = 'Direktorat Jenderal Tata Kelola dan Pengendalian Risiko';
    case ITJEN = 'Inspektorat Jenderal';

    public function getUnitKerja(): array
    {
        return match($this) {
            self::SEKJEN => [
                'Biro Perencanaan dan Kerja Sama',
                'Biro Sumber Daya Manusia, Organisasi, dan Tata Laksana',
                'Biro Keuangan',
                'Biro Pengadaan Barang dan Jasa',
                'Biro Umum',
                'Biro Hukum',
                'Biro Komunikasi Publik',
                'Pusat Data dan Informasi',
                'Pusat Pengembangan Sumber Daya Manusia'
            ],
            self::DITJEN_KP => [
                'Sekretariat Direktorat Jenderal Kawasan Permukiman',
                'Direktorat Sistem dan Strategi Penyelenggaraan Kawasan Permukiman',
                'Direktorat Penyiapan Lahan dan Prasarana, Sarana, dan Utilitas Kawasan Permukiman',
                'Direktorat Pengembangan Kawasan Permukiman',
                'Direktorat Pembinaan Usaha Kawasan Permukiman',
                'Direktorat Bina Teknik Perumahan dan Kawasan Permukiman',
            ],
            self::DITJEN_DESA => [
                'Sekretariat Direktorat Jenderal Perumahan Perdesaan',
                'Direktorat Sistem dan Strategi Pembangunan Perumahan Perdesaan',
                'Direktorat Penyiapan Lahan, Perizinan, dan Penghunian Perumahan Perdesaan',
                'Direktorat Pembiayaan Perumahan Perdesaan',
                'Direktorat Pembangunan Perumahan Perdesaan',
                'Direktorat Peningkatan Kualitas Perumahan Perdesaan',
            ],
            self::DITJEN_KOTA => [
                'Sekretariat Direktorat Jenderal Perumahan Kota',
                'Direktorat Sistem dan Strategi Pembangunan Perumahan Kota',
                'Direktorat Penyiapan Lahan, Perizinan, dan Penghunian Perumahan Kota',
                'Direktorat Pembiayaan Perumahan Kota',
                'Direktorat Pembangunan Perumahan Kota',
                'Direktorat Peningkatan Kualitas Perumahan Perkotaan',
            ],
            self::DITJEN_TKPR => [
                'Sekretariat Direktorat Jenderal Tata Kelola dan Pengendalian Risiko',
                'Direktorat Sistem dan Strategi Tata Kelola dan Pengendalian Risiko',
                'Direktorat Penyusunan Sistem Pembiayaan Perumahan dan Kawasan Permukiman',
                'Direktorat Sistem Efisiensi dan Kemitraan Penyelenggaraan Pembangunan',
                'Direktorat Keterbukaan Publik, Transparansi, dan Akuntabilitas',
                'Direktorat Pengendalian Risiko dan Pencegahan Korupsi',
            ],
            self::ITJEN => [
                'Sekretariat Inspektorat Jenderal',
                'Inspektorat 1', 
                'Inspektorat 2',
                'Inspektorat 3',
                'Inspektorat Bidang Investigasi',
            ],
            default => [],
        };
    }

    public static function getUnitOrganisasi(string $unitKerja)
    {
        foreach (self::cases() as $case) {
            if (in_array($unitKerja, $case->getUnitKerja())) {
                return $case;
            }
        }
        return null;
    }
}