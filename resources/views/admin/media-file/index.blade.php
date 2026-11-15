@php use App\Helpers\CustomHelpers; @endphp
@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Media File
            <small>All Media File</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('media-file.create') }}"><i class="fa fa-file-o"></i>Add File</a></li>
            <li class="active">File List</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">All Media File</h3>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($mediaFiles) > 0)
                                <table class="table table-bordered table-hover">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th style="width:80px">Type</th>
                                        <th>File</th>
                                        <th>Copy url</th>
                                    </tr>
                                    @foreach ($mediaFiles as $key => $mediaFile)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                {!! CustomHelpers::fileIcon($mediaFile->type) !!}
                                            </td>
                                            <td>
                                                <a href="{{ $mediaFile->path }}" class="link-black">{{ $mediaFile->path }}</a>
                                            </td>
                                            <td>
                                                <button
                                                    class="btn btn-default btn-sm"
                                                    onclick='copyToClipboard("{{ image_url(config("helper.uploadPath").$mediaFile->path) }}")'
                                                >
                                                    <i class="fa fa-copy"></i>
                                                    Copy url
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$mediaFiles->links()}}
                                </div>
                            @else
                                @include('admin.partial.data-not-found')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Copy markup -->
    <div id="snackbar" style="display:none">Copied!</div>
@endsection

@section('js')
    <script>
        function copyToClipboard(link) {
            console.log(link)
            let dummy = document.createElement('input'),
                text = link;

            document.body.appendChild(dummy);
            dummy.value = text;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);

            let x = document.getElementById("snackbar");
            x.style.display = 'block';
            setTimeout(function() {
                x.style.display = 'none';
            }, 2000);
        }
    </script>
@endsection
