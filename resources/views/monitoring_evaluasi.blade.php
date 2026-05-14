@extends('layouts.app')

@section('content')
    <div class="content" style="margin-left: 105px; padding: 8px;">
        <div class="org-container">
            <h2 class="text-light mb-3">Monitoring Evaluasi</h2>
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
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                    <td class="font-w600 font-size-sm">
                                        <a href="be_pages_generic_profile.html">Alice Moore</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>
@endsection