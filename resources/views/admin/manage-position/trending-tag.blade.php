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
            display: flex;
            align-items: center;
        }

        .todo-list>li .serial {
            margin-right: 10px;
            font-weight: bold;
            min-width: 25px;
        }

        .todo-list>li .handle {
            margin-right: 10px;
            cursor: move;
        }

        .todo-list>li .text {
            display: inline-block;
            margin-left: 5px;
            font-weight: 600;
            flex-grow: 1;
        }

        .todo-list>li .tools {
            display: none;
            float: right;
            color: #dd4b39;
        }

        .todo-list>li:hover .tools {
            display: block;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage {{ ucwords(str_replace('_', ' ', $positionType->value)) }}
            <small>Position</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manage-position.index') }}"><i class="fa fa-list"></i> Position List</a></li>
            <li class="active">Manage {{ ucwords(str_replace('_', ' ', $positionType->value)) }}</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-9 col-md-offset-1">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Add at Position:</label>
                                    <select class="form-control" id="addAtPosition">
                                        @for ($i = 1; $i <= count($tags) + 1; $i++)
                                            <option value="{{ $i }}"
                                                {{ $i == count($tags) + 1 ? 'selected' : '' }}>Pos {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label>Search Content:</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="contentSearch"
                                            placeholder="Enter Tag ID or Tag name here">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-flat" id="btnAddItem">Add Item</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('manage-position.store') }}" method="POST" id="positionForm">
                        @csrf
                        <input type="hidden" name="position_type" value="{{ $positionType->value }}">
                        <input type="hidden" name="ids" id="ids_input" value="{{ $position->ids ?? '' }}">

                        <div class="box-body">
                            <div class="form-group">
                                <label for="max_content">Max Content:</label>
                                <input type="number" name="max_content" id="max_content" class="form-control"
                                    style="width: 100px;" value="{{ $position->max_content ?? 9 }}">
                            </div>
                            <ul class="todo-list" id="content-list">
                                @foreach ($tags as $index => $tag)
                                    <li data-id="{{ $tag->id }}">
                                        <span class="serial">{{ $index + 1 }}</span>
                                        <span class="handle">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <i class="fa fa-ellipsis-v"></i>
                                        </span>
                                        <span class="text">ID: {{ $tag->id }} | {{ $tag->name }}</span>
                                        <div class="tools">
                                            <i class="fa fa-trash-o remove-item" style="cursor: pointer;"></i>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Save
                                Position</button>
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
                source: "{{ route('manage-position.search-tag') }}",
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
                if (!contentID) return;

                var li = '<li data-id="' + contentID + '">' +
                    '<span class="serial"></span>' +
                    '<span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>' +
                    '<span class="text">' + contentLabel + '</span>' +
                    '<div class="tools"><i class="fa fa-trash-o remove-item" style="cursor: pointer;"></i></div>' +
                    '</li>';

                var pos = parseInt($("#addAtPosition").val());
                if (pos && pos <= $('#content-list li').length) {
                    $('#content-list li').eq(pos - 1).before(li);
                } else {
                    $('#content-list').append(li);
                }

                $("#contentSearch").val('').data('selected-id', null);
                updateIds();
            });

            $(document).on('click', '.remove-item', function() {
                $(this).closest('li').remove();
                updateIds();
            });

            $("#content-list").sortable({
                handle: ".handle",
                update: function() {
                    updateIds();
                }
            });

            function updateIds() {
                var ids = [];
                var count = $('#content-list li').length;
                $('#content-list li').each(function(index) {
                    $(this).find('.serial').text(index + 1);
                    ids.push($(this).data('id'));
                });
                $('#ids_input').val(ids.join(','));

                // Update Position Selector
                var $posSelect = $("#addAtPosition");
                var currentVal = $posSelect.val();
                $posSelect.empty();
                for (var i = 1; i <= count + 1; i++) {
                    $posSelect.append('<option value="' + i + '">Pos ' + i + '</option>');
                }
                if (currentVal <= count + 1) {
                    $posSelect.val(currentVal);
                } else {
                    $posSelect.val(count + 1);
                }
            }
        });
    </script>
@endsection
