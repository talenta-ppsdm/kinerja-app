@extends('layouts.app')

@section('content')
    <div class="content" style="margin-left: 105px; padding: 8px;">
        <div class="org-container">
            <h2 class="text-light mb-3">Monitoring Evaluasi</h2>

            <div class="row">
                @php
                    $cards = [
                        ['label' => 'Sangat Baik', 'key' => 'sangat_baik'],
                        ['label' => 'Baik', 'key' => 'baik'],
                        ['label' => 'Butuh Perbaikan', 'key' => 'butuh_perbaikan'],
                        ['label' => 'Kurang', 'key' => 'kurang'],
                        ['label' => 'Sangat Kurang', 'key' => 's_kurang'],
                    ];
                @endphp

                @foreach($cards as $card)
                    <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                        <a class="block block-rounded" href="javascript:void(0)">
                            <div class="block-content block-content-full">
                                <div class="font-size-sm font-w600 text-uppercase text-muted">{{ $card['label'] }}</div>
                                <div class="font-size-h2 font-w400 text-dark">
                                    {{-- Menggunakan null coalescing (??) agar tidak error jika data kosong --}}
                                    {{ number_format(($rekap_predikat[$card['key']] ?? collect())->count(), 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <form action="{{ route('import.evaluasi') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row items-push">
                    <div class="col-md-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="file_excel" data-toggle="custom-file-input" required>
                            <label class="custom-file-label">Pilih file Excel SKP</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-file-import mr-1"></i> Mulai Import
                        </button>
                    </div>
                </div>
            </form>

            <div class="block block-rounded">
                <div class="block-content">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-vcenter">
                            <thead>
                                <tr class="text-center">
                                    <th>No</th>
                                    <th style="width: 30%;">Nama</th>
                                    <th style="width: 20%;">Jabatan</th>
                                    <th style="width: 15%;">Golongan</th>
                                    <th style="width: 20%;">Unit Kerja</th>
                                    <th style="width: 25%;">Pejabat Penilai Kinerja</th>
                                    <th style="width: 15%;">Periode</th>
                                    <th style="width: 15%;">Triwulan I</th>
                                    <th style="width: 15%;">Triwulan II</th>
                                    <th style="width: 15%;">Triwulan III</th>
                                    <th style="width: 15%;">Triwulan IV</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php 
                                    $no = 0;
                                    $groupedEvaluasi = $skpEvaluasi->groupBy(fn($item) => $item->masterSkp->nip);
                                
                                    $getBadgeColor = function($predikat) {
                                        return match($predikat) {
                                            'Sangat Baik', 'Baik' => 'success',
                                            'Cukup', 'Butuh Perbaikan' => 'warning',
                                            'Belum Dinilai' => 'primary',
                                            'Kurang', 'Sangat Kurang' => 'danger',
                                            default => 'secondary',
                                        };
                                    };
                                @endphp
                                @foreach($groupedEvaluasi as $nip => $items)
                                    @php $no++; @endphp

                                    @foreach($items as $index => $evaluasi)
                                    <tr>
                                        @if($index === 0)
                                            <td rowspan="{{ $items->count() }}" class="text-center" style="vertical-align: middle;">
                                                {{ $no }}
                                            </td>
                                            <td rowspan="{{ $items->count() }}" style="vertical-align: middle;">
                                                <p class="font-w600 mb-0">{{ $evaluasi->masterSkp->nama ?? '' }}</p>
                                                <small class="text-muted">{{ $evaluasi->masterSkp->nip ?? '' }}</small>
                                            </td>
                                        @endif
                                        
                                        <td class="font-w600 font-size-sm">
                                            <p>{{ $evaluasi->masterSkp->jabatan ?? '' }}</p>
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            <p>{{ $evaluasi->masterSkp->golongan ?? '' }}</p>
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            <p>{{ $evaluasi->masterSkp->unit_kerja ?? '' }}</p>
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            <p>{{ $evaluasi->masterSkp->ppk ?? '' }}</p>
                                        </td>
                                        <td class="font-w600 font-size-sm">
                                            <p>{{ $evaluasi->masterSkp->periode ?? '' }}</p>
                                        </td>

                                        @foreach(['tw1', 'tw2', 'tw3', 'tw4'] as $tw)
                                        @php $field = "predikat_$tw"; @endphp
                                            <td class="text-center">
                                                @if($evaluasi->$field)
                                                    <span class="badge badge-{{ $getBadgeColor($evaluasi->$field) }}">
                                                        {{ $evaluasi->$field }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endsection