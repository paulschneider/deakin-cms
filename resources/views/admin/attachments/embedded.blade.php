
{{-- Using a piece of unrecognised HTML here seems to fix a ckeditor bug with inserting block elements when the widget is set to inline. --}}

{!! ck_only($options, '<editor class="editor">', null) !!}

    @if ($options['isImage'])

        {!! ck_only($options, '<span class="figure '.$class.'">', '<figure class="'.$class.'">') !!}

            <img src="{{ $attachment->file->url($size) }}" alt="{{ $attachment->alt }}" title="{{ $attachment->title }}" class="img-responsive">

            @if ( ! empty($options['data-attachment-caption']))

                {!! ck_only($options, '<span class="figcaption">', '<figcaption>') !!}
                    {{ $attachment->title }}
                {!! ck_only($options, '</span>', '</figcaption>') !!}
            @endif

        {!! ck_only($options, '</span>', '</figure>') !!}

    @else
        <a href="{{ $attachment->file->url() }}" class="{{ $class }}"><span>{{ $attachment->title }}</span></a>
    @endif


{!! ck_only($options, '</editor>', null) !!}
