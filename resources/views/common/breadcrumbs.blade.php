
@if (!empty($breadcrumbs))

    <div class="row">
        <ol class="breadcrumb">
            @if( ! isset($hide_home))
                <li><a href="/"><i class="fa fa-home"></i></a></li> <i class="fa fa-chevron-right breadcrumb-separator"></i>
            @endif
            @foreach ($breadcrumbs as $key => $breadcrumb)
                @if ($key !== count($breadcrumbs) -1)
                    <li><a href="{{ $breadcrumb->url }}"{!! $key === 0 ? ' class="section"' : '' !!}>{{ $breadcrumb->title }}</a></li> <i class="fa fa-chevron-right breadcrumb-separator"></i>
                @else
                    <li{!! $key === 0 ? ' class="section"' : '' !!}>{{ $breadcrumb->title }}</li>
                @endif
            @endforeach
        </ol>
    </div>

@endif