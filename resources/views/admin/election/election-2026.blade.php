@extends('layout.admin')

@section('content')

    <section class="content-header">
        <h1>
            Election
            <small>{{ $dataset->title }}</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="box box-success">

                    <div class="box-header with-border text-center">
                        <h3 class="box-title">
                            <i class="fa fa-bar-chart"></i> {{ $dataset->title }}
                        </h3>
                    </div>

                    <div class="box-body">

                        @if(session('success'))
                            <div class="alert alert-success text-center">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('nationalElection2026Update') }}">
                            @csrf

                            {{-- Referendum --}}
                            @if(isset($data['referendum']))
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>{{ $data['referendum']['title_bn'] }}</strong>
                                    </div>

                                    <div class="panel-body">
                                        @foreach($data['referendum']['items'] as $index => $item)
                                            <div class="row form-group">
                                                <div class="col-xs-6">
                                                    <label class="control-label">
                                                        {{ $item['label_bn'] }}
                                                        <small class="text-muted">({{ $item['label_en'] }})</small>
                                                    </label>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <input type="hidden" name="data[referendum][title_bn]" value="{{ $data['referendum']['title_bn'] }}">
                                                        <input type="hidden" name="data[referendum][title_en]" value="{{ $data['referendum']['title_en'] }}">
                                                        <input type="hidden" name="data[referendum][items][{{$index}}][key]" value="{{ $item['key'] }}">
                                                        <input type="hidden" name="data[referendum][items][{{$index}}][label_bn]" value="{{ $item['label_bn'] }}">
                                                        <input type="hidden" name="data[referendum][items][{{$index}}][label_en]" value="{{ $item['label_en'] }}">

                                                        <input type="text"
                                                               class="form-control percent-input"
                                                               name="data[referendum][items][{{$index}}][value]"
                                                               value="{{ $item['value'] }}">

{{--                                                        <span class="input-group-addon">%</span>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Result --}}
                            @if(isset($data['result']))
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <strong>{{ $data['result']['title_bn'] }}</strong>
                                    </div>

                                    <div class="panel-body">
                                        @foreach($data['result']['items'] as $index => $item)
                                            <div class="row form-group">
                                                <div class="col-xs-6">
                                                    <label class="control-label">
                                                        {{ $item['label_bn'] }}
                                                        <small class="text-muted">({{ $item['label_en'] }})</small>
                                                    </label>
                                                </div>

                                                <div class="col-xs-6">
                                                    <div class="input-group">
                                                        <input type="hidden" name="data[result][title_bn]" value="{{ $data['result']['title_bn'] }}">
                                                        <input type="hidden" name="data[result][title_en]" value="{{ $data['result']['title_en'] }}">
                                                        <input type="hidden" name="data[result][items][{{$index}}][key]" value="{{ $item['key'] }}">
                                                        <input type="hidden" name="data[result][items][{{$index}}][label_bn]" value="{{ $item['label_bn'] }}">
                                                        <input type="hidden" name="data[result][items][{{$index}}][label_en]" value="{{ $item['label_en'] }}">

                                                        <input type="text"
                                                               class="form-control percent-input"
                                                               name="data[result][items][{{$index}}][value]"
                                                               value="{{ $item['value'] }}">

{{--                                                        <span class="input-group-addon">%</span>--}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fa fa-save"></i> Update Election Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
