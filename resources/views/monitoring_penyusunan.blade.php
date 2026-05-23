@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Monitoring Penyusunan</h2>

        <!-- Filter Data -->
        <div class="block block-rounded">
            <div class="block-content">
                <form action="{{ route('penyusunan.index') }}" method="GET">
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
                        <div class="col-md-3">
                            <label class="text-dark">Status SKP</label>
                            <select id="select-status-skp" name="status_skp" class="form-control">
                                <option value="">-- Pilih Status SKP --</option>
                                @foreach($listStatusSkp as $statusSkp)
                                    <option value="{{ $statusSkp->value }}">{{ $statusSkp->value }}</option>
                                @endforeach
                            </select>
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
                    ['label' => 'Disetujui', 'key' => 'disetujui'],
                    ['label' => 'Belum Menyusun', 'key' => 'belum_menyusun'],
                    ['label' => 'Draft', 'key' => 'draft'],
                ];
            @endphp

            @foreach($cards as $card)
                <div class="col-6 col-md-4 col-lg-6 col-xl-4">
                    <a class="block block-rounded" href="javascript:void(0)">
                        <div class="block-content block-content-full">
                            <div class="font-size-sm font-w600 text-uppercase text-muted">{{ $card['label'] }}</div>
                            <div class="font-size-h2 font-w400 text-dark">
                                {{ number_format(($rekapStatusSkp[$card['key']] ?? collect())->count(), 0, ',', '.') }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <!-- END Predicate Recapitulation -->
        
        <!-- Import Penyusunan Data -->
        <form action="{{ route('import.penyusunan') }}" method="POST" enctype="multipart/form-data">
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
        <!-- END Import Penyusunan Data -->

        <!-- Penyusunan Data Table -->
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
                                <th style="width: 15%;">Status SKP</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 0;
                                $groupedPenyusunan = $skpPenyusunan->groupBy(fn($item) => $item->masterSkp->nip);

                                $getBedgeColor = function($statusSkp){
                                    return match($statusSkp) {
                                        'Disetujui' => 'success',
                                        'Draft' => 'secondary',
                                        'Belum Menyusun' => 'warning',
                                        default => 'primary'
                                    };
                                };
                            @endphp
                            @foreach($groupedPenyusunan as $nip => $items)
                                @php $no++; @endphp

                                @foreach($items as $index => $penyusunan)
                                <tr>
                                    @if($index === 0)
                                    <td rowspan="{{ $items->count() }}" class="text-center" style="vertical-align: middle;">
                                        {{ $no }}
                                    </td>
                                    <td rowspan="{{ $items->count() }}" style="vertical-align: middle;">
                                        <p class="font-w600 mb-0">{{ $penyusunan->masterSkp->nama ?? '' }}</p>
                                        <small class="text-muted">{{ $penyusunan->masterSkp->nip ?? '' }}</small>
                                    </td>
                                    @endif

                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->jabatan ?? '' }}</p>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->golongan ?? '' }}</p>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->unit_kerja ?? '' }}</p>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->ppk ?? '' }}</p>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->periode ?? '' }}</p>
                                    </td>

                                    <td class="font-w600 font-size-sm">
                                        @if($penyusunan->status_skp)
                                        <span class="badge badge-{{$getBedgeColor($penyusunan->status_skp)}}">
                                            {{$penyusunan->status_skp}}
                                        </span>
                                        @endif
                                    </td>
                                    <td class=" text-center font-size-sm">
                                        <a href="{{ route('penyusunan.edit', $penyusunan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END Penyusunan Data Table -->
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
</script>
@endpush
