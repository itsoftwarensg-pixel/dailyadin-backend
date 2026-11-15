<div class="col-lg-3 col-xs-6">
    <div class="small-box {{ $bgColor }}">
        <div class="inner">
            <h3>{{ $value }}</h3>
            <p>{{ $title }}</p>
        </div>
        <div class="icon">
            <i class="{{ $icon ? $icon : 'ion ion-stats-bars' }}"></i>
        </div>
        <a href="{{ $link }}" class="small-box-footer">
            More info <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
