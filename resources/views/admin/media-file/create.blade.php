@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Media File
            <small>Add New </small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('media-file.index') }}"><i class="fa fa-dashboard fa-fw"></i>All Media File</a></li>
            <li class="active">Add New File</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Media File</h3>
                    </div>

                    <div class="media-file">
                        <form action="{{ route('media-file.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="upload-container" id="uploadContainer">
                                <i class="fa fa-cloud-upload fa-5x"></i>
                                <p>Drag & Drop file here or <span class="text-aqua">click</span> to select</p>
                                <input type="file" name="file" id="fileInput">
                                <div class="file-preview" id="filePreview"></div>
                                @error('file')<x-error-message :message="$message"/>@enderror
                            </div>


                            <div class="box-footer text-center">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        var uploadContainer = document.getElementById('uploadContainer');
        var fileInput = document.getElementById('fileInput');
        var filePreview = document.getElementById('filePreview');

        // Drag & Drop effects
        uploadContainer.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('dragover');
        });

        uploadContainer.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
        });

        uploadContainer.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            previewFile(e.dataTransfer.files[0]); // only first file
        });

        fileInput.addEventListener('change', function() {
            if(this.files.length > 0){
                previewFile(this.files[0]); // only first file
            }
        });

        function previewFile(file) {
            filePreview.innerHTML = ''; // clear previous
            var ext = file.name.split('.').pop().toLowerCase();
            var color = '#7f8c8d';
            var icon = 'fa fa-file-o';

            // Determine icon and color
            switch(ext) {
                case 'pdf': icon='fa fa-file-pdf-o'; color='#e74c3c'; break;
                case 'doc': case 'docx': icon='fa fa-file-word-o'; color='#3498db'; break;
                case 'xls': case 'xlsx': icon='fa fa-file-excel-o'; color='#27ae60'; break;
                case 'jpg': case 'jpeg': case 'png': case 'webp': case 'svg': icon='fa fa-file-image-o'; color='#f1c40f'; break;
                case 'mp3': icon='fa fa-file-audio-o'; color='#9b59b6'; break;
                case 'mp4': icon='fa fa-file-video-o'; color='#e67e22'; break;
            }

            var span = document.createElement('span');
            span.className = 'file-badge';
            span.style.color = color;
            span.innerHTML = '<i class="' + icon + '"></i> ' + file.name;

            filePreview.appendChild(span);
        }
    </script>

@endsection

