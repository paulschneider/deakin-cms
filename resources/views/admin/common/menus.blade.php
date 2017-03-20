{!! GlobalClass::add('body', ['menu-selector']) !!}
<?php $collapsed = (isset($collapsed) && $collapsed == true) ? ' collapsed' : ''; ?>

<?php
    // Dance around potential values.
    // use ->appendMeta() on $page->link below if rmeta values required.

    $link = (isset($entity) && method_exists($entity, 'link')) ? $entity->link : null;
    $slug = ( ! empty($entity->slug)) ? $entity->slug : null;

    if (is_null($slug) && ! empty($link->route)) $slug = $link->route;
?>

<div class="ibox float-e-margins menu-selector{{ $collapsed }}" data-url-addon="{{ empty($link->id) ? null : '/' . $link->id }}">

    <div class="ibox-title">
        <h5>Menu <small>Adds this item to the navigation of the site.</small></h5>
        <div class="ibox-tools">
            <span class="label label-warning">Affects published revision</span>
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>

    <div class="ibox-content">


        <div class="form-group">
            <label class="control-label col-sm-2">Create</label>
            <div class="col-sm-10">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('menu[create]', 1, ( ! empty($link->id) )) !!}
                        Create a menu link
                    </label>
                </div>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2">Visibility</label>
            <div class="col-sm-10">
                <div class="checkbox">
                    <label>
                        {!! Form::checkbox('menu[online]', 1, ( ! empty($link->online) )) !!}
                        Link is shown to public
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2">Link Title</label>
            <div class="col-sm-10">
                {!! Form::input('text', 'menu[title]', (empty($link->title) ? null : $link->title), ['class' => 'form-control']) !!}
            </div>
        </div>

        @if ( ! empty($slug))
            <div class="form-group">
                <label class="control-label col-sm-2">Link URL</label>
                <div class="col-sm-10">
                    <div class="input-group">
                        <div class="input-group-addon">{{ url('') }}/</div>
                        {!! Form::input('text', 'slug', (empty($slug) ? null : $slug), ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        @endif

        {{--
        <div class="form-group">
            <label class="control-label col-sm-2">Link Description</label>
            <div class="col-sm-10">
                {!! Form::input('text', 'menu[meta_description]', (empty($link->meta_description) ? null : $link->meta_description), ['class' => 'form-control']) !!}
            </div>
        </div>
        --}}

        <div class="form-group">
            <label class="control-label col-sm-2">Menu</label>
            <div class="col-sm-10">
                <div class="row">

                    <div class="col-sm-4">
                        {!! Form::select('menu[id]', Menus::getMenus(), (empty($link->menu_id) ? config('links.default_menu') : $link->menu_id), ['class' => 'form-control', 'id' => 'attach-menu-select']) !!}
                    </div>

                    <div class="col-sm-8">
                        <select id="attach-menu-parent" name="menu[parent]" class="form-control">
                        @if ( ! empty($link->menu_id))

                            @foreach (Menus::getOptions($link->menu, $link->id) as $key => $value)
                                <option value="{{ $key }}" @if($key == $link->parent_id) selected="selected" @endif>{{ $value }}</option>
                            @endforeach

                        @elseif (old('menu.id'))

                            @foreach (Menus::getOptions(Menus::getMenu(old('menu.id'))) as $key => $value)
                                <option value="{{ $key }}" @if($key == old('menu.parent')) selected="selected" @endif>{{ $value }}</option>
                            @endforeach

                        @else

                            @foreach (Menus::getOptions(Menus::getMenu(config('links.default_menu'))) as $key => $value)
                                <option value="{{ $key }}" @if($key == old('menu.parent')) selected="selected" @endif>{{ $value }}</option>
                            @endforeach

                        @endif

                        </select>

                        <span class="help-block m-b-none">Select where this link should be nested.</span>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
