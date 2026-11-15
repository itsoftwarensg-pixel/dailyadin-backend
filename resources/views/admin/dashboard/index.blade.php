@extends('layout.admin')

@section('content')
    <!--Breadcrumb -->
    <section class="content-header">
        <h1>
            Dashboard
            <small>Control panel</small>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            {{--File location for <x-dashboard-info /> is  ---->  resoureces/views/components/ --}}
            <x-dashboard-info
                bg-color="bg-orange"
                title="Content"
                value="Add New"
                link="{{ route('bn-content.create') }}"
                icon="fa fa-plus"
            />

            <x-dashboard-info
                bg-color="bg-blue"
                title="Content"
                value="View All"
                link="{{ route('bn-content.index') }}"
                icon="fa fa-align-right"
            />

            <x-dashboard-info
                bg-color="bg-aqua"
                title="Total Number of Content"
                value="{{ $totalContentCount ?? 0 }}"
                link="{{ route('bn-content.index') }}"
                icon="fa fa-align-center"
            />

            <x-dashboard-info
                bg-color="bg-green"
                title="Content Upload Today"
                value="{{ $totalUploadTodayCount ?? 0 }}"
                link=""
                icon="ion ion-stats-bars"
            />

            <x-dashboard-info
                bg-color="bg-red"
                title="Total Category"
                value="{{ $totalCategoryCount ?? 0 }}"
                link="{{ route('category.index') }}"
                icon="fa fa-paw"
            />

            <x-dashboard-info
                bg-color="bg-green"
                title="Total Sub Category"
                value="{{ $totalSubCategoryCount ?? 0 }}"
                link="{{ route('sub-category.index') }}"
                icon="fa fa-align-left"
            />

            <x-dashboard-info
                bg-color="bg-orange"
                title="Total Special Category"
                value="{{ $totalSpecialCategoryCount ?? 0 }}"
                link="{{ route('special-category.index') }}"
                icon="fa fa-arrows-alt"
            />

            <x-dashboard-info
                bg-color="bg-blue"
                title="Total Tag"
                value="{{ $totalTagCount ?? 0 }}"
                link=""
                icon="fa fa-tag"
            />

            <x-dashboard-info
                bg-color="bg-green"
                title="Total Photo Album"
                value="{{ $totalPhotoAlbumCount ?? 0 }}"
                link="{{ route('photo-album.index') }}"
                icon="fa fa-image"
            />

            <x-dashboard-info
                bg-color="bg-red"
                title="Total Videos"
                value="{{ $totalVideoCount ?? 0 }}"
                link="{{ route('video.index') }}"
                icon="fa fa-film"
            />

            <x-dashboard-info
                bg-color="bg-blue"
                title="Top Hit Contents"
                value="Life Time"
                link="{{ route('bnContentTopHitList') }}"
                icon="fa fa-wifi"
            />

            <x-dashboard-info
                bg-color="bg-orange"
                title="Top Hit Contents"
                value="Last 7 days"
                link="{{ route('bnContentTopHitList', ['days' => 7]) }}"
                icon="fa fa-fighter-jet"
            />
        </div>
    </section>
@endsection
