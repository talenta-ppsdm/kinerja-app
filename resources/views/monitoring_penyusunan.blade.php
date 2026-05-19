@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Monitoring Penyusunan</h2>
        
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
                                <th style="width: 15%;">Triwulan</th>
                                <th style="width: 15%;">Status SKP</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $no = 0;
                                $groupedPenyusunan = $skpPenyusunan->groupBy(fn($item) => $item->masterSkp->nip);
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
                                            <p class="font-w600 mb-0">{{ $evaluasi->masterSkp->nama ?? '' }}</p>
                                            <small class="text-muted">{{ $evaluasi->masterSkp->nip ?? '' }}</small>
                                        </td>
                                    @endif

                                    <td class="font-w600 font-size-sm">
                                        <p>{{ $penyusunan->masterSkp->jabatan ?? '' }}</p>
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
