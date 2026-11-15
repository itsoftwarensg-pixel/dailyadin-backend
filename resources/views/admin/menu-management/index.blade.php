@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Menu
            <small>Menu Management</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <form role="form" action="{{ route('menu.save') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="box-header with-border">
                            <h3 class="box-title">Navbar menu management <small>Don't use any single quotation. Use double quotation</small></h3>
                            <div class="box-tools">
                            </div>
                            <div class="box-tools margin-r-5">
                                <button
                                    type="submit"
                                    class="btn btn-success margin-r-5"
                                    id="showJson"
                                >
                                    Save Changes
                                </button>

                                <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-default">
                                    All Category & Sub Category
                                </button>
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                @error('menuItems')<x-error-message :message="$message"/>@enderror
                                <textarea
                                    class="form-control"
                                    id="menuItems"
                                    name="menuItems"
                                    style="resize: vertical; height: 80vh;"
                                >{{$menu->menuItems ?? ''}}</textarea>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Modal --}}
                <div class="modal fade" id="modal-default">
                    <div class="modal-dialog container" style="width: 1200px!important;height:90vh!important;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">All Category and Sub Category List</h4>
                            </div>
                            <div class="modal-body" style="height:90vh!important;overflow-y: scroll;">
                                <div class="box">
                                    @if (count($categories) > 0)
                                        <table class="table table-condensed table-hover">
                                            <tbody>
                                            <tr>
                                                <th>Category</th>
                                                <th>Sub Category</th>
                                            </tr>
                                            @foreach ($categories as $key => $category)
                                                <tr>
                                                    <td style="width: 20%;">
                                                        <div style="display: flex; gap:10px">
                                                            <span style="width:100px;margin-right:10px;display: inline-block">{{ $category->name }}</span>
                                                            <span style="width:200px;margin-right:10px;display: inline-block">{{ $category->alias }}</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($category->subcategories as $key => $subCategory)
                                                                <li>
                                                                    <div style="display: flex; gap:10px">
                                                                        <span style="width:100px;margin-right:10px;display: inline-block">{{ $subCategory->name }}</span>
                                                                        <span style="width:300px;margin-right:10px;display: inline-block">{{ $category->alias.'/'.$subCategory->alias }}</span>
                                                                    </div>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- /.modal -->

            </div>
        </div>
    </section>
@endsection
