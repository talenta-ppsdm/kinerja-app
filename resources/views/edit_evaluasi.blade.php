@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Edit Data Evaluasi</h2>

        <!-- Filter Data -->
        <div class="block block-rounded">
            <div class="block-content">
                <form action="{{ route('evaluasi.update', $skpEvaluasi->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama" class="text-dark">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $skpEvaluasi->masterSkp->nama }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nip" class="text-dark">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" value="{{ $skpEvaluasi->masterSkp->nip }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="unit_organisasi" class="text-dark">Unit Organisasi</label>
                        <select class="form-control" id="select-unor" name="unit_organisasi" onchange="updateModalUnker()">
                            <option value="">-- Pilih Unor --</option>
                            @foreach($listUnor as $unor)
                                <option value="{{ $unor->value }}" 
                                    {{ $skpEvaluasi->masterSkp->unit_organisasi == $unor->value ? 'selected' : '' }}>
                                    {{ $unor->value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="unit_kerja" class="text-dark">Unit Kerja</label>
                        <select class="form-control" id="select-unker" name="unit_kerja">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @if($skpEvaluasi->masterSkp->unit_organisasi)
                                @php
                                    $selectedUnor = \App\Enums\UnitOrganisasiEnum::tryFrom($skpEvaluasi->masterSkp->unit_organisasi);
                                    $unitKerjaOptions = $selectedUnor ? $selectedUnor->getUnitKerja() : [];
                                @endphp
                                @foreach($unitKerjaOptions as $unker)
                                    <option value="{{ $unker }}" 
                                        {{ $skpEvaluasi->masterSkp->unit_kerja == $unker ? 'selected' : '' }}>
                                        {{ $unker }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mb-3">Update</button>
                </form>
            </div>
        </div>
        <!-- END Filter Data -->
    </div>
</div>
@endsection

@push('js')
<script>
    const unkerData = JSON.parse('@json($listUnker)');

    function updateModalUnker() {
        const unorSelect = document.getElementById('select-unor');
        const unkerSelect = document.getElementById('select-unker');
        const selectedUnor = unorSelect.value;

        // Kosongkan dropdown Unit Kerja
        unkerSelect.innerHTML = '<option value="">-- Pilih Unit Kerja --</option>';

        // Jika Unor dipilih dan ada datanya di mapping
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