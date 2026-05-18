@extends('layouts.app')

@section('content')
    <div class="content" style="margin-left: 105px; padding: 16px;">
        <div class="org-container" background-color: #244253;>
            
            <div style="margin-bottom: 25px; border-bottom: 2px solid #cbd1a1; padding-bottom: 15px;">
                <h1 style="color: #cbd1a1; margin: 0; font-size: 1.8rem; font-weight: bold;">
                    <i data-feather="folder" style="vertical-align: middle; margin-right: 10px;"></i> Tautan Pintasan Sistem
                </h1>
                <p style="color: #ecf0f1; margin: 8px 0 0 0; font-size: 0.9rem;">
                    Akses cepat menuju bahan, dokumen, paparan, serta portal Aplikasi myPKP di Tim Pengelolaan Kinerja Pusat Pengembangan Sumber Daya Manusia
                </p>
            </div>

            <div class="shortcut-grid">
                
                <a href="URL_SHARE_ONEDRIVE_ANDA" target="_blank" rel="noopener noreferrer" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="hard-drive" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>OneDrive Pengelolaan Kinerja</h3>
                        <p>Berkas, dokumen, dan informasi internal pengelolaan kinerja.</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://my.pkp.go.id/" target="_blank" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="globe" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>Portal Resmi MyPKP</h3>
                        <p>Tautan eksternal menuju website utama instansi pusat.</p>
                    </div>
                    <i data-feather="external-link" class="arrow-icon"></i>
                </a>

                <a href="https://docs.google.com/spreadsheets/d/1vdXu9lqN6WlPoSxpuV48IGHXZvE06CdJ/edit?gid=87762561#gid=87762561" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="file-text" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>Spreadsheet Rencana Strategis</h3>
                        <p>Matriks Renstra Kementerian Perumahan dan Kawasan Permukiman</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://www.canva.com/design/DAG2ejqbqDc/0KE7LKAlADvKZeNzexC8vg/edit" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="save" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>Paparan Penyusunan SKP 2026</h3>
                        <p>Salah satu paparan penyusunan sasaran kinerja pegawai tahun 2026</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://www.canva.com/design/DAHDhvmnIys/qZRX6XbSnp87DhS22t8oKg/edit" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="save" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>Paparan Pengelolaan Kinerja Pegawai 2026</h3>
                        <p>Paparan Pengelolaan Kinerja Kementerian Perumahan dan Kawasan Permukiman Tahun 2026</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://www.canva.com/design/DAHDhvmnIys/qZRX6XbSnp87DhS22t8oKg/edit" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="save" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>Paparan Laporan Kinerja 2026</h3>
                        <p>Paparan Laporan Kinerja Eselon I, Staf Ahli, Staf Khusus, dan Tenaga Ahli Tahun 2026</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://kemenpkp-my.sharepoint.com/:b:/g/personal/ravikaislamy_pkp_go_id/IQDwu0UxgUXHRqufEiBMxPb6AcYQ34jV0QkTltO_nYrKKtc?e=tdAYLx" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="file-text" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>PERMEN PANRB NOMOR 6 TAHUN 2022</h3>
                        <p>Peraturan Menteri PANRB Nomor 6 Tahun 2022 Tentang Pengelolaan Kinerja</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://kemenpkp-my.sharepoint.com/:b:/g/personal/ravikaislamy_pkp_go_id/IQD9muoKmEN2TZMOr6C5qUlDAVK14LCRjhUa29DaLygABf8?e=iF7eFJ" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="file-text" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>SE PANRB NOMOR 3 TAHUN 2023</h3>
                        <p>Surat Edaran PANRB Nomor 3 Tahun 2023 Tentang Tata Cara Penetapan Predikat kinerja</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

                <a href="https://kemenpkp-my.sharepoint.com/:b:/g/personal/ravikaislamy_pkp_go_id/IQAax8ABuc1cTK7kR-fnnGJQAaxGNKdz8wlWqJZTs9FrhxU?e=xwCZwL" class="shortcut-card">
                    <div class="shortcut-icon-wrapper">
                        <i data-feather="file-text" class="shortcut-icon"></i>
                    </div>
                    <div class="shortcut-info">
                        <h3>SE SEKRETARIAT JENDERAL NOMOR 10 TAHUN 2025</h3>
                        <p>Surat Edaran Sekretariat Jenderal Nomor 10 Tahun 2025 Tentang Pengelolaan Kinerja</p>
                    </div>
                    <i data-feather="arrow-right" class="arrow-icon"></i>
                </a>

            </div>

        </div>
    </div>
@endsection