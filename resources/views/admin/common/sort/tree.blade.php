

@foreach ($tree as $branch)
    <li class="dd-item" data-id="{{ $branch[$type]->id }}" data-delta="{{ $delta or '' }}">
        <div class="dd-handle">

            {{ data_get($branch, $type.'.revision.'.$label, $branch[$type]->{$label}) }}

            @if (isset($branch['entity_link']) && $branch['entity_link'] === false)
                @if (isset($branch[$type]->menu_id) && in_array($branch[$type]->menu_id, config('links.no_restructure')))
                    <!-- Admin menu possibly -->
                @else
                    <br><span class="label label-warning"><i class="fa fa-link"></i> This is a link. Do not nest items under here.</span>
                @endif
            @endif

            @if (isset($branch[$type]->online) && ! $branch[$type]->online)
                <br><span class="label label-warning"><i class="fa fa-link"></i> Link is a offline.</span>
            @endif

        </div>
        @if ( ! empty($branch['children']))
            <ol class="dd-list">
                @include('admin.common.sort.tree', ['tree' => $branch['children'], 'type' => $type])
            </ol>
        @endif
    </li>
@endforeach

