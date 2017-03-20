
{!! Form::hidden('related_links['.$counter.'][id]', (isset($link->id) ? $link->id : null)) !!}

    <div class="row related-row">
        {!! Form::hidden('related_links['.$counter.'][icon_id]', (isset($link->icon_id) ? $link->icon_id : null), ['class' => 'related-icon']) !!}
        <label class="control-label col-sm-1">Link</label>
        <div class="col-sm-10">
            <div class="row">
                <div class="col-sm-4">
                    <label class="sr-only">Action</label>
                    {!! Form::select('related_links['.$counter.'][link_id]', Menus::getAllOptions(config('links.all_ignore'), 'External link'), (empty($link->link_id) ? null : $link->link_id), ['class' => 'form-control']) !!}
                </div>
                <div class="col-sm-4">
                    <label class="sr-only">URL</label>
                    {!! Form::text('related_links['.$counter.'][external_url]', (empty($link->external_url) ? null : $link->external_url), ['class' => 'form-control', 'placeholder' => 'http://']) !!}
                </div>
                <div class="col-sm-4">
                    <label class="sr-only">Title</label>
                    {!! Form::text('related_links['.$counter.'][title]', (empty($link->title) ? null : $link->title), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                </div>
            </div>
        </div>
        <div class="col-sm-1 svg">
            @if ( ! empty($link->icon->svg))
                {!! $link->icon->svg !!}
            @endif
        </div>
            <a href="{{ route('admin.icons.iframe', ['type' => 'related-links']) }}" class="btn btn-warning select-icon btn-outline btn-xs "><i class="fa fa-exchange"></i></a>
    </div>
