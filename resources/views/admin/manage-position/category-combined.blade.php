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

        .input-highlight {
            background-color: #f0fbf2ff !important;
            border: 1px solid #3bffa7ff !important;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Manage Category Positions
            <small>Home & Inner</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ route('manage-position.index') }}"><i class="fa fa-list"></i> Position List</a></li>
            <li class="active">Manage Category Positions</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="categorySelector">Select Category:</label>
                                    <select class="form-control" id="categorySelector">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $categoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <label>Search Content to Add:</label>
                                    <div class="input-group">
                                        <div class="input-group-btn" style="width: 120px;">
                                            <select class="form-control" id="targetPosition">
                                                <option value="home">Home List</option>
                                                <option value="inner">Inner List</option>
                                            </select>
                                        </div>
                                        <div class="input-group-btn" style="width: 120px; border-left: 0;">
                                            <select class="form-control" id="addAtPosition">
                                                <option value="last">Last</option>
                                            </select>
                                        </div>
                                        <input type="text" class="form-control" id="topContentSearch"
                                            placeholder="Enter Content ID or Heading Here">
                                        <span class="input-group-btn">
                                            <button class="btn btn-info btn-flat" id="btnAddItem">Add Item</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Home Positions -->
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Category Home Positions</h3>
                        <div class="box-tools pull-right">
                            <span class="label label-info">Max: {{ $homePosition->max_content ?? 9 }}</span>
                        </div>
                    </div>

                    <form action="{{ route('manage-position.store') }}" method="POST" class="positionForm" id="homeForm">
                        @csrf
                        <input type="hidden" name="position_type"
                            value="{{ \App\Enums\BnManagePositionEnum::CATEGORY_HOME_POSITION->value }}">
                        <input type="hidden" name="position_type_id" value="{{ $categoryId }}">
                        <input type="hidden" name="ids" class="ids_input" value="{{ $homePosition->ids ?? '' }}">

                        <div class="box-body">
                            <div class="form-group">
                                <label>Max Content:</label>
                                <input type="number" name="max_content" class="form-control input-sm" style="width: 80px;"
                                    value="{{ $homePosition->max_content ?? 9 }}">
                            </div>
                            <ul class="todo-list content-list" id="home-list">
                                @foreach ($homeContents as $index => $content)
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
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-save"></i> Save
                                Home</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Inner Positions -->
            <div class="col-md-6">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Category Inner Positions</h3>
                        <div class="box-tools pull-right">
                            <span class="label label-warning">Max: {{ $innerPosition->max_content ?? 9 }}</span>
                        </div>
                    </div>

                    <form action="{{ route('manage-position.store') }}" method="POST" class="positionForm" id="innerForm">
                        @csrf
                        <input type="hidden" name="position_type"
                            value="{{ \App\Enums\BnManagePositionEnum::CATEGORY_INNER_POSITION->value }}">
                        <input type="hidden" name="position_type_id" value="{{ $categoryId }}">
                        <input type="hidden" name="ids" class="ids_input" value="{{ $innerPosition->ids ?? '' }}">

                        <div class="box-body">
                            <div class="form-group">
                                <label>Max Content:</label>
                                <input type="number" name="max_content" class="form-control input-sm"
                                    style="width: 80px;" value="{{ $innerPosition->max_content ?? 9 }}">
                            </div>
                            <ul class="todo-list content-list" id="inner-list">
                                @foreach ($innerContents as $index => $content)
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
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-sm pull-right"><i class="fa fa-save"></i>
                                Save Inner</button>
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
            // Autocomplete
            $("#topContentSearch").autocomplete({
                source: "{{ route('manage-position.search') }}",
                minLength: 2,
                select: function(event, ui) {
                    $(this).data('selected-id', ui.item.id);
                    $(this).data('selected-label', ui.item.label);
                }
            });

            // Add Item
            $("#btnAddItem").click(function(e) {
                e.preventDefault();
                var target = $("#targetPosition").val();
                var $input = $("#topContentSearch");
                var $list = $("#" + target + "-list");

                var contentID = $input.data('selected-id');
                var contentLabel = $input.data('selected-label');

                if (!contentID) {
                    var val = $input.val();
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

                if ($list.find('li[data-id="' + contentID + '"]').length > 0) {
                    alert('This content is already in the list.');
                    return;
                }

                var li = '<li data-id="' + contentID + '">' +
                    '<span class="serial"></span>' +
                    '<span class="handle"><i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i></span>' +
                    '<span class="text">' + contentLabel + '</span>' +
                    '<div class="tools"><i class="fa fa-trash-o remove-item" style="cursor: pointer;"></i></div>' +
                    '</li>';

                var pos = $("#addAtPosition").val();
                if (pos !== 'last') {
                    var idx = parseInt(pos) - 1;
                    if ($list.find('li').eq(idx).length) {
                        $list.find('li').eq(idx).before(li);
                    } else {
                        $list.append(li);
                    }
                } else {
                    $list.append(li);
                }

                updateListAndIds(target);
                updatePositionDropdown();
            });

            $('#targetPosition').on('change', function() {
                updatePositionDropdown();
            });

            function updatePositionDropdown() {
                var target = $("#targetPosition").val();
                var count = $("#" + target + "-list li").length;
                var $posSelect = $("#addAtPosition");
                $posSelect.empty().append('<option value="last">Last</option>');
                for (var i = 1; i <= count + 1; i++) {
                    $posSelect.append('<option value="' + i + '">Pos ' + i + '</option>');
                }
                $posSelect.val('last');
            }

            // Initial call
            updatePositionDropdown();

            // Remove Item
            $(document).on('click', '.remove-item', function() {
                var $list = $(this).closest('ul');
                var target = $list.attr('id').replace('-list', '');
                $(this).closest('li').remove();
                updateListAndIds(target);
                updatePositionDropdown();
            });

            // Sortable
            $(".content-list").sortable({
                cancel: ".tools, .remove-item",
                update: function(event, ui) {
                    var target = $(this).attr('id').replace('-list', '');
                    updateListAndIds(target);
                    updatePositionDropdown();
                }
            });

            function updateListAndIds(target) {
                var $list = $("#" + target + "-list");
                var ids = [];
                $list.find('li').each(function(index) {
                    $(this).find('.serial').text(index + 1);
                    ids.push($(this).data('id'));
                });
                $list.closest('form').find('.ids_input').val(ids.join(','));
            }

            $('.positionForm').submit(function() {
                var target = $(this).find('ul').attr('id').replace('-list', '');
                updateListAndIds(target);
            });

            // Category Selector Change
            $('#categorySelector').on('change', function() {
                var val = $(this).val();
                var currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('category_id', val);
                window.location.href = currentUrl.toString();
            });
        });
    </script>
@endsection
