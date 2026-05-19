@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Monitoring Evaluasi</h2>

        <!-- Filter Data -->
        <div class="block block-rounded">
            <div class="block-content">
                <form action="{{ route('evaluasi.index') }}" method="GET">
                    <div class="row items-push">
                        <div class="col-md-5">
                            <label class="text-dark">Unit Organisasi</label>
                            <select id="select-unor" name="unit_organisasi" class="form-control" onchange="updateUnker()">
                                <option value="">-- Pilih Unor --</option>
                                @foreach($listUnor as $unor)
                                    <option value="{{ $unor->value }}">{{ $unor->value }}</option>
                                @endforeach
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="text-dark">Unit Kerja</label>
                            <select id="select-unker" name="unit_kerja" class="form-control">
                                <option value="">-- Pilih Unit Kerja --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="text-dark">Triwulan</label>
                            <select id="select-triwulan" name="triwulan" class="form-control" onchange="updatePredikat()">
                                <option value="">-- Pilih Triwulan --</option>
                                <option value="1">Triwulan I</option>
                                <option value="2">Triwulan II</option>
                                <option value="3">Triwulan III</option>
                                <option value="4">Triwulan IV</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="text-dark">Predikat</label>
                            <select id="select-predikat" name="predikat" class="form-control">
                                <option value="">-- Pilih Predikat --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="text-dark">Pencarian</label>
                            <input type="text" class="form-control" name="search" placeholder="Masukkan nama, NIP, ....">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <label class="text-dark"></label>
                                <i class="fa mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Filter Data -->

        <!-- Predicate Recapitulation -->
        <div class="row">
            @php
                $cards = [
                    ['label' => 'Sangat Baik', 'key' => 'sangat_baik'],
                    ['label' => 'Baik', 'key' => 'baik'],
                    ['label' => 'Butuh Perbaikan', 'key' => 'butuh_perbaikan'],
                    ['label' => 'Kurang', 'key' => 'kurang'],
                    ['label' => 'Sangat Kurang', 'key' => 'sangat_kurang'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                    <a class="block block-rounded" href="javascript:void(0)">
                        <div class="block-content block-content-full">
                            <div class="font-size-sm font-w600 text-uppercase text-muted">{{ $card['label'] }}</div>
                            <div class="font-size-h2 font-w400 text-dark">
                                {{ number_format(($rekap_predikat[$card['key']] ?? collect())->count(), 0, ',', '.') }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <!-- END Predicate Recapitulation -->

        <!-- Import Evaluation Data -->
        <form action="{{ route('import.evaluasi') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row items-push">
                <div class="col-md-9">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_excel" id="file_excel" data-toggle="custom-file-input">
                        <label class="custom-file-label" for="file_excel" id="file-label">Pilih file Excel SKP</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-file-import mr-1"></i> Mulai Import
                    </button>
                </div>
            </div>
        </form>
        <!-- END Import Evaluation Data -->

        <!-- Evaluation Data Table -->
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
                                <th style="width: 15%;">Action</th>
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

                                    <td class=" text-center font-size-sm">
                                        <a href="{{ route('evaluasi.edit', $evaluasi->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Evaluation Data Table -->
    </div>  
</div>
@endsection

@push('js')
<script>
    const unkerData = JSON.parse('@json($listUnker)');
    function updateUnker() {
        const unorSelect = document.getElementById('select-unor');
        const unkerSelect = document.getElementById('select-unker');
        const selectedUnor = unorSelect.value;

        unkerSelect.innerHTML = '<option value="">-- Pilih Unit Kerja --</option>';

        if (selectedUnor && unkerData[selectedUnor]) {
            unkerData[selectedUnor].forEach(unker => {
                const option = document.createElement('option');
                option.value = unker;
                option.text = unker;
                unkerSelect.add(option);
            });
        }
    }

    const predicateData = JSON.parse('@json($listPredicate)');
    function updatePredikat() {
        const triwulanSelect = document.getElementById('select-triwulan');
        const predikatSelect = document.getElementById('select-predikat');
        const selectedTriwulan = triwulanSelect.value;

        predikatSelect.innerHTML = '<option value="">-- Pilih Predikat --</option>'

        if (selectedTriwulan) {
            predicateData.forEach(predikat => {
                const option = document.createElement('option');
                option.value = predikat;
                option.text = predikat;
                predikatSelect.add(option);
            });
        }
    }

    document.getElementById('file_excel').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endpush