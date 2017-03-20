{!! GlobalClass::add('body', ['multiple-related']) !!}

<div class="ibox float-e-margins collapsed">

    <div class="ibox-title">
        <h5>Related Links <small>Any related links to place at the bottom of the page</small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>

    <?php
        // Get the old values or the values from the entity passed in
        $related = Request::old('related_links', []);

        if (empty($related) && isset($entity)) {
            if ($entity && $entity->revision->related_links->count()) $related = $entity->revision->related_links;
        }
    ?>

    {!! Form::hidden('related_form', 1) !!}

    <div class="ibox-content clearfix">
        <div class="dd multiple-fields">
            <ol class="dd-list col-sm-12 related-multiple-fields">
                <?php $counter = 0; ?>
                @if ( ! empty($related))
                    @foreach ($related as $link)
                        {{-- Make one for each field --}}
                        <li class="dd-item multiple-field slim clearfix">
                            <div class="dd-handle drag"><i class="fa fa-arrows"></i></div>
                            @include('admin.common.related_links_row', ['link' => $link, 'counter' => $counter])
                        </li>
                        <?php $counter++ ?>
                    @endforeach
                @else
                    {{-- If it's empty, still output one so that we can use it as the template --}}
                    <li class="dd-item multiple-field slim clearfix">
                        <div class="dd-handle drag"><i class="fa fa-arrows"></i></div>
                        @include('admin.common.related_links_row', ['counter' => 0])
                    </li>
                @endif
            </ol>
        </div>
    </div>
</div>
