@forelse ($contents as $content)
    <div class="content-item">
        <div class="row">
            <div class="col-md-1">
                <strong>ID: {{ $content->id }}</strong>
            </div>
            <div class="col-md-5">
                <a href="{{ $content->frontend_url }}" target="_blank">
                    {{ $content->heading }}
                </a>
            </div>
            <div class="col-md-2">
                <span class="label label-info">{{ $content->category->name ?? 'N/A' }}</span>
            </div>
            <div class="col-md-2">
                <small class="text-muted">
                    <i class="fa fa-clock-o"></i>
                    {{ \App\Helpers\BengaliDateHelper::formatBengaliDateTime($content->created_at) }}
                </small>
            </div>
            <div class="col-md-2">
                @if ($content->show_content == 1)
                    <span class="label label-success">প্রকাশিত</span>
                @else
                    <span class="label label-warning">অপ্রকাশিত</span>
                @endif
            </div>
        </div>
    </div>
@empty
    <div class="text-center text-muted" style="padding: 20px;">
        <i class="fa fa-inbox fa-3x"></i>
        <p>কোনো তথ্য পাওয়া যায়নি।</p>
    </div>
@endforelse
