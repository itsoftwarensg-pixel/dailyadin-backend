@extends('layout.admin')

@section('content')
    <section class="content-header">
        <h1>
            Ramadan
            <small>{{ $ramadan->hijri_year }}</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                <form method="POST" action="{{ route('ramadan.update') }}">
                    @csrf

                    <!-- OVERVIEW -->
                    <div class="card">
                        <div class="section-title">Overview</div>
                        <div class="grid-2">
                            <div>
                                <label>Title</label>
                                <input type="text" name="title" value="{{ $ramadan->title }}">
                            </div>

                            <div>
                                <label>Name</label>
                                <input type="text" name="name" value="{{ $ramadan->name }}">
                            </div>

                            <div>
                                <label>Hijri Year</label>
                                <input type="number" name="hijri_year" value="{{ $ramadan->hijri_year }}">
                            </div>

                            <div>
                                <label>Ramadan Start Date (DD/MM/YYYY)</label>
                                <input type="date" name="ramadan_start_date"
                                       value="{{ $ramadan->ramadan_start_date->format('Y-m-d') }}">
                            </div>
                        </div>

                        <br>

                        <div>
                            <label>Footnote</label>
                            <textarea name="footnote">{{ $ramadan->footnote }}"</textarea>
                        </div>
                    </div>


                    <!-- RAMADAN DAYS -->
                    <div class="card">
                        <div class="section-title">Ramadan Days</div>
                        <table>
                            <thead>
                            <tr>
                                <th>Day</th>
                                <th>Date</th>
                                <th>Sehri</th>
                                <th>Iftar</th>
                                <th>Fajr</th>
                                <th>Duhr</th>
                                <th>Asr</th>
                                <th>Maghrib</th>
                                <th>Isha</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($ramadan->days as $index => $day)
                                <tr>
                                    <td>
                                        {{ $day['day'] }}
                                        <input type="hidden" name="days[{{ $index }}][day]" value="{{ $day['day'] }}">
                                    </td>

                                    <td>
                                        <input type="date" class="form-control"
                                               name="days[{{ $index }}][enDate]"
                                               value="{{ \Carbon\Carbon::parse($day['enDate'])->format('Y-m-d') }}">
                                    </td>
                                    <td>
                                        <input type="time" name="days[{{ $index }}][sehri_end]" value="{{ $day['sehri_end'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][iftar]" value="{{ $day['iftar'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][fajr]" value="{{ $day['fajr'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][duhr]" value="{{ $day['duhr'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][asr]" value="{{ $day['asr'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][maghrib]" value="{{ $day['maghrib'] }}">
                                    </td>

                                    <td>
                                        <input type="time" name="days[{{ $index }}][isha]" value="{{ $day['isha'] }}">
                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        <label class="toggle-switch">

                                            <!-- Default value when unchecked -->
                                            <input type="hidden" name="days[{{ $index }}][status]" value="2">

                                            <!-- Toggle -->
                                            <input type="checkbox"
                                                   name="days[{{ $index }}][status]"
                                                   value="1"
                                                {{ isset($day['status']) && $day['status'] == 1 ? 'checked' : '' }}>

                                            <span class="toggle-slider"></span>

                                        </label>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(count($ramadan->division_offsets) > 0)
                        <div class="card">
                            <div class="section-title">Division Offsets</div>
                            <div class="row">
                                @foreach($ramadan->division_offsets as $i => $div)
                                    <div class="col-xs-12 col-sm-6 col-md-3">
                                        <div class="well well-sm">
                                            <div style="display:flex; align-items:center; justify-content:space-between;">

                                                <!-- LEFT: Division Name -->
                                                <div>
                                                    <strong>
                                                        {{ $div['division_en_name'] }}
                                                        <br>
                                                        <small>{{ $div['division_bn_name'] }}</small>
                                                    </strong>
                                                </div>

                                                <!-- RIGHT: Input -->
                                                <div style="width:70px;">
                                                    <input type="number"
                                                           class="form-control input-sm"
                                                           name="division_offsets[{{ $i }}][time_offset_minute]"
                                                           value="{{ $div['time_offset_minute'] }}">
                                                </div>

                                                <!-- HIDDEN FIELDS -->
                                                <input type="hidden"
                                                       name="division_offsets[{{ $i }}][division_en_name]"
                                                       value="{{ $div['division_en_name'] }}">

                                                <input type="hidden"
                                                       name="division_offsets[{{ $i }}][division_bn_name]"
                                                       value="{{ $div['division_bn_name'] }}">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- SAVE BUTTON -->
                    <div class="save-wrapper">
                        <button type="submit" class="btn btn-success">
                            💾 Save Changes
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </section>

@endsection

@section('css')
    <style>
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 15px;
            border-left: 3px solid #28a745;
            padding-left: 10px;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: repeat(2,1fr);
            gap: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            text-align: left;
            padding: 10px;
            font-size: 13px;
            color: #777;
            border-bottom: 1px solid #eee;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #f5f5f5;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        input[type="number"] {
            width: 100%;
            padding: 6px 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
        }

        .save-wrapper {
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 10px;
            text-align: right;
            border-top: 1px solid #eee;
        }
    </style>

    <style>
        .toggle-switch {
            position: relative;
            width: 40px;
            height: 20px;
        }

        .toggle-switch input {
            display: none;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            background: #ccc;
            border-radius: 20px;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            transition: 0.3s;
        }

        .toggle-slider:before {
            content: "";
            position: absolute;
            height: 16px;
            width: 16px;
            left: 2px;
            top: 2px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: #28a745;
        }

        .toggle-switch input:checked + .toggle-slider:before {
            transform: translateX(20px);
        }
    </style>
@endsection
