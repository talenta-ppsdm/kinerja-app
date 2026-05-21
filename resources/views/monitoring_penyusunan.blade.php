@extends('layouts.app')

@section('content')
<div class="content" style="margin-left: 105px; padding: 8px;">
    <div class="org-container">
        <h2 class="text-light mb-3">Monitoring Penyusunan</h2>
        
        <!-- Import Evaluation Data -->
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
        <!-- END Import Evaluation Data -->
    </div>  
</div>
@endsection
