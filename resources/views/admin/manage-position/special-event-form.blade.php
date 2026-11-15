@extends('layout.admin')

@section('css')
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .todo-list>li {
            background: #f4f4f4;
            border-left: 2px solid #e6e7e8;
            color: #444;
            padding: 10px;
            margin-bottom: 5px;
            cursor: move;
        }

        .todo-list>li .text {
            display: inline-block;
            margin-left: 5px;
            font-weight: 600;
        }

        .todo-list>li .tools {
            display: none;
            float: right;
            color: #dd4b39;
        }

        .todo-list>li:hover .tools {
            display: block;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
            z-index: 2;
        }

        .slider:after {
            content: 'OFF';
            color: white;
            display: block;
            position: absolute;
            transform: translate(-50%, -50%);
            top: 50%;
            left: 65%;
            font-size: 10px;
            font-family: Verdana, sans-serif;
            font-weight: bold;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        input:checked+.slider:after {
            content: 'ON';
            left: 35%;
        }

        .slider-label {
            margin-left: 10px;
            font-weight: bold;
        }

        .serial {
            display: inline-block;
            width: 30px;
            text-align: center;
            font-weight: bold;
            color: #666;
        }

        .handle {
            cursor: move;
            margin-right: 10px;
            color: #999;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            {{ $specialEvent ? 'Edit' : 'Create' }} Special Event
            <small>Form</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manage-position.special-event') }}"><i class="fa fa-list"></i> Special Events</a></li>
            <li class="active">{{ $specialEvent ? 'Edit' : 'Create' }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="box box-primary no-border">
                    <form action="{{ route('manage-position.store') }}" method="POST" id="positionForm">
                        @csrf
                        <input type="hidden" name="position_type"
                            value="{{ \App\Enums\BnManagePositionEnum::SPECIAL_EVENT_POSITION->value }}">
                        <input type="hidden" name="event_id" value="{{ $specialEvent->id ?? '' }}">
                        <input type="hidden" name="news_ids" id="ids_input" value="{{ $specialEvent->news_ids ?? '' }}">

                        <div class="box-header with-border">
                            <div class="pull-right">
                                <span class="slider-label"
                                    style="margin-right: 10px; vertical-align: middle;">Active/Inactive</span>
                                <label class="toggle-switch" style="vertical-align: middle;">
                                    <input type="checkbox" name="active_status"
                                        {{ old('active_status', $specialEvent->active_status ?? 2) == 1 ? 'checked' : '' }}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>

                        <div class="box-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    {{ session('error') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-sm-3">
                                    <select class="form-control" id="addAtPosition">
                                        @for ($i = 1; $i <= count($contents) + 1; $i++)
                                            <option value="{{ $i }}"
                                                {{ $i == count($contents) + 1 ? 'selected' : '' }}>Position
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="contentSearch"
                                            placeholder="Enter Content ID or Heading Here">
                                        <span class="input-group-btn">
                                            <button class="btn btn-add-item" id="btnAddItem" type="button">Add
                                                Item</button>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-15" style="margin-top: 15px;">
                                <label>Event Name <span class="text-danger">*</span></label>
                                <input type="text" name="event_name" class="form-control" placeholder="বিশেষ নিউজ"
                                    value="{{ old('event_name', $specialEvent->event_name ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label>Event URL</label>
                                <input type="text" name="event_url" class="form-control"
                                    placeholder="https://matribhumirkhobor.com/"
                                    value="{{ old('event_url', $specialEvent->event_url ?? '') }}">
                            </div>

                            <ul class="todo-list" id="content-list">
                                @foreach ($contents as $index => $content)
                                    <li data-id="{{ $content->id }}">
                                        <span class="serial">{{ $index + 1 }}</span>
                                        <span class="handle">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <i class="fa fa-ellipsis-v"></i>
                                        </span>
                                        <span class="text">ID: {{ $content->id }} | {{ $content->heading }}</span>
                                        <div class="tools">
                                            <i class="fa fa-trash-o remove-item" style="cursor: pointer;"></i>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="box-footer clearfix no-border">
                            <a href="{{ route('manage-position.special-event') }}" class="btn btn-default">
                                <i class="fa fa-arrow-left"></i> Back to List
                            </a>
                            <button type="submit" class="btn btn-success pull-right">
                                <i class="fa fa-save"></i> {{ $specialEvent ? 'Update' : 'Create' }} Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(function() {
            $("#contentSearch").autocomplete({
                source: "{{ route('manage-position.search') }}",
                minLength: 2,
                select: function(event, ui) {
                    $(this).data('selected-id', ui.item.id);
                    $(this).data('selected-label', ui.item.label);
                }
            });

            $("#btnAddItem").click(function(e) {
                e.preventDefault();
                var contentID = $("#contentSearch").data('selected-id');
                var contentLabel = $("#contentSearch").data('selected-label');

                if (!contentID) {
                    var val = $("#contentSearch").val();
                    if (val && val.indexOf('|') !== -1) {
                        var parts = val.split('|');
                        contentID = parts[0].trim();
                        contentLabel = parts[0].trim() + ' | ' + parts[1].trim();
                    }
                }

                if (!contentID) {
                    alert('Please choose a valid content from the suggestions.');
                    return;
                }

                if ($('#content-list li[data-id="' + contentID + '"]').length > 0) {
                    alert('This content is already in the list.');
                    return;
                }

                var li = '<li data-id="' + contentID + '">' +
                    '<span class="serial"></span>' +
                    '<span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>' +
                    '<span class="text">ID: ' + contentLabel + '</span>' +
                    '<div class="tools"><i class="fa fa-trash-o remove-item" style="cursor: pointer;"></i></div>' +
                    '</li>';

                var pos = parseInt($("#addAtPosition").val());
                if (pos && pos <= $('#content-list li').length) {
                    $('#content-list li').eq(pos - 1).before(li);
                } else {
                    $('#content-list').append(li);
                }

                $("#contentSearch").val('').data('selected-id', null).data('selected-label', null);
                updateListAndIds();
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('li').remove();
                updateListAndIds();
            });

            $("#content-list").sortable({
                handle: ".handle",
                update: function(event, ui) {
                    updateListAndIds();
                }
            });

            function updateListAndIds() {
                var ids = [];
                var count = $('#content-list li').length;

                $('#content-list li').each(function(index) {
                    $(this).find('.serial').text(index + 1);
                    ids.push($(this).data('id'));
                });
                $('#ids_input').val(ids.join(','));

                var $posSelect = $("#addAtPosition");
                var currentVal = $posSelect.val();
                $posSelect.empty();
                for (var i = 1; i <= count + 1; i++) {
                    $posSelect.append('<option value="' + i + '">Position ' + i + '</option>');
                }
                if (currentVal <= count + 1) {
                    $posSelect.val(currentVal);
                } else {
                    $posSelect.val(count + 1);
                }
            }

            $('#positionForm').submit(function() {
                updateListAndIds();
            });
        });
    </script>
@endsection
