@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Monitoring Penyusunan</h2>
        
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
                                        <a href="" class="btn btn-sm btn-warning">Edit</a>
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
