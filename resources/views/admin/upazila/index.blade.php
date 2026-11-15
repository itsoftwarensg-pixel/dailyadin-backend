@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Upazila
            <small>All Upazila</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="box box-success">
                    <div class="box-header text-center">
                        <h3 class="box-title">All Upazila</h3>
                        <div class="box-tools" style="position: absolute; right: 15px; top: 15px;">
                            <a href="{{ route('upazila.create') }}" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add New Upazila</a>
                        </div>
                        <hr style="margin: 10px 0;">
                        <div>
                            <form method="GET" action="{{ route('upazila.index') }}" class="mb-4">
                                <div style="display: flex; gap: 10px; align-items: center;justify-content: center;">
                                    <!-- Division filter -->
                                    <div class="form-group">
                                        <select class="form-control" name="division_id" id="division" onchange="resetDistrictAndSubmit()">
                                            <option value="">-- Select Division --</option>
                                            @foreach ($divisions as $division)
                                                <option value="{{ $division->id }}" {{ request('division_id') == $division->id ? 'selected' : '' }}>
                                                    {{ $division->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- District filter -->
                                    <div class="form-group">
                                        <select class="form-control" name="district_id" id="district">
                                            <option value="">-- Select District --</option>
                                            @foreach ($allDistricts as $district)
                                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                    {{ $district->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Upazila -->
                                    <div class="form-group">
                                        <input type="text" name="upazila" value="{{ request('upazila') }}" class="form-control" placeholder="Search upazila">
                                    </div>


                                    <!-- Submit -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="{{ route('upazila.index') }}" class="btn btn-default">Reset</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <hr style="margin: 0;">
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <div class="col-sm-12">
                            @foreach ($districts as $district)
                                <h4><strong>{{ $district->division->name }} → {{ $district->name }} <small>({{ $district->upazilas->count() }} Upazilas)</small></strong></h4>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th style="width:180px">Upazila Name</th>
                                        <th style="width:200px">Upazila Name (Bangla)</th>
                                        <th>Alias/Slug</th>
                                        <th style="width:100px;text-align: center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($district->upazilas as $key => $upazila)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $upazila->name }}</td>
                                            <td>{{ $upazila->name_bn }}</td>
                                            <td>{{ $upazila->alias }}</td>
                                            <td style="text-align: center">
                                                <a href="{{ route('upazila.edit', $upazila->id) }}" class="btn btn-sm btn-default">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No upazilas available</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function resetDistrictAndSubmit() {
            // Reset district dropdown
            document.getElementById('district').value = "";
            // Submit form
            document.querySelector('form').submit();
        }
    </script>
@endsection
