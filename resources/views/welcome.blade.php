@extends('layouts.app')

@section('content')
        <!-- Percobaan Isi Konten Disini -->
        <div class="content" style="margin-left: 105px; padding: 8px;">
            <div class="org-container">
                
                <!-- Baris 1: Pimpinan (Kepala Bidang) -->
                <div class="leader-section">
                    <div class="main-person">
                        <img src="{{ asset('images/ragees.jpg') }}" alt="Ragees Mirakelia">
                        <div class="info">
                            <span class="name-text">RAGEES MIRAKELIA, S.H., M.H.</span>
                            <span class="role-title">KEPALA BIDANG MANAJEMEN TALENTA</span>
                        </div>
                    </div>
                </div>

                <!-- Garis Penghubung Horizontal -->
                <div class="connector-line"></div>

                <!-- Baris 2: Struktur Tim (Cards) -->
                <div class="team-grid">
                    <!-- Card Ravika -->
                    <div class="card-person-special">
                        <div class="card-header">
                            <img src="{{ asset('images/ravika.jpg') }}" alt="Ravika">
                            <div class="ms-3">
                                <span class="name-text">RAVIKA ISLAMY, S.E</span>
                                <span class="role-title">KETUA TIM PENGELOLAAN KINERJA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-grid">
                    <!-- Card Vania -->
                    <div class="card-person">
                        <div class="card-header">
                            <img src="{{ asset('images/vania.jpg') }}" alt="Vania">
                            <div class="ms-3">
                                <span class="name-text">VANIA ZERLINDA, S.KOM.</span>
                                <span class="role-title">ANALIS SUMBER DAYA MANUSIA APARATUR AHLI PERTAMA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Puteri -->
                    <div class="card-person">
                        <div class="card-header">
                            <img src="{{ asset('images/puteri.jpg') }}" alt="Puteri">
                            <div class="ms-3">
                                <span class="name-text">PUTERI AULIA FAHLIA, S.KOM.</span>
                                <span class="role-title">ANALIS SUMBER DAYA MANUSIA APARATUR AHLI PERTAMA</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="team-grid">
                    <!-- Card Hana -->
                    <div class="card-person">
                        <div class="card-header">
                            <img src="{{ asset('images/hana.png') }}" alt="Hana">
                            <div class="ms-3">
                                <span class="name-text">HANA RAFIFAH, S.P.W.K.</span>
                                <span class="role-title">PENATA KELOLA PERUMAHAN AHLI PERTAMA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Zain -->
                    <div class="card-person">
                        <div class="card-header">
                            <img src="{{ asset('images/zain.jpg') }}" alt="Zain">
                            <div class="ms-3">
                                <span class="name-text">ZAIN HASBI ASHIDIQ, S.PD.</span>
                                <span class="role-title">PENATA KELOLA BANGUNAN GEDUNG DAN KAWASAN PERMUKIMAN AHLI PERTAMA</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
@endsection
