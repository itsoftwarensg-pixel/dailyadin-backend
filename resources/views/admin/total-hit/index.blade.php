@php
    use App\Helpers\BengaliDateHelper;
@endphp

@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Content
            <small>Top Hit Contents</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <div style="display: flex; justify-content: space-between; align-items: center">
                                <h3 class="box-title">Top Hit Contents</h3>
                            </div>
                            <div style="display: flex; gap:10px; align-items: center">
                                <form method="GET" action="{{ route('bnContentTopHitList') }}" id="categorySearchForm" class="form-inline">
                                    <div class="form-group">
                                        <select class="form-control" name="category_id" id="category" onchange="searchByCategory()">
                                            <option value="">All Category</option>
                                            @foreach ($categories as $key => $category)
                                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <a class="btn btn-default" href="{{ route('bnContentTopHitList') }}">Reset</a>
                                </form>

                                {{-- Filter by last 7days --}}
                                <form action="{{ route('bnContentTopHitList') }}" method="GET">
                                    <input type="hidden" name="days" value="7">
                                    <button type="submit" class="btn btn-primary">Last 7 Days</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @if (count($bnContents) > 0)
                                <table class="table table-hover">
                                    <tbody>
                                    <tr>
                                        <th>ID</th>
                                        <th>Category</th>
                                        <th>Sub Category</th>
                                        <th style="width:35%">Content Heading</th>
                                        <th>Total Hit</th>
                                        <th>প্রকাশিত</th>
                                    </tr>
                                    @foreach ($bnContents as $key => $bnContent)
                                        <tr>
                                            <td style="width: 70px"><span class="text-red">{{ $bnContent->id }}</span>
                                            </td>
                                            <td>{{ $bnContent->category->name }}</td>
                                            <td>{{ optional($bnContent->subCategory)->name ?? 'N/A' }}</td>
                                            <td>{{ $bnContent->heading }}</td>
                                            <td>{{ $bnContent->hits ?? 0 }}</td>
                                            <td>
                                                <strong
                                                    class="text-light-blue">{{ $bnContent->created_at? BengaliDateHelper::formatBengaliDateTime($bnContent->created_at) : ''}}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="box-footer text-center">
                                    {{-- Pagination --}}
                                    {{$bnContents->links()}}
                                </div>
                            @else
                                @include('admin.partial.data-not-found')
                            @endif
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function searchByCategory() {
            document.querySelector('#categorySearchForm').submit();
        }
    </script>
@endsection
