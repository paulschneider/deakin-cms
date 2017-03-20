{!! GlobalClass::add('body', ['banner-selector']) !!}


<div class="ibox float-e-margins banner-selector collapsed">

    <div class="ibox-title">
        <h5>Banners <small>Changes the main banner image at the top of the site.</small></h5>
        <div class="ibox-tools">
            <a class="collapse-link">
                <i class="fa fa-chevron-up"></i>
            </a>
        </div>
    </div>

    <div class="ibox-content">

        <div class="form-group">
            <label class="control-label col-sm-2">Group</label>
            <div class="col-sm-10">
                {!! Form::select('meta_banner[id]', Banners::getGroups(), (empty($entity->meta_banner) ? config('banners.default_banner') : $entity->meta_banner['id']), ['class' => 'form-control', 'id' => 'attach-banner-select']) !!}
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2">Image</label>
            <div class="col-sm-10">
                <select id="attach-banner-parent" name="meta_banner[image]" class="form-control">
                @if ( ! empty($entity->meta_banner))

                    @foreach (Banners::getOptions(Banners::get($entity->meta_banner['id'])) as $key => $value)
                        <option value="{{ $key }}" @if($key == $entity->meta_banner['image']) selected="selected" @endif>{{ $value }}</option>
                    @endforeach

                @elseif (old('meta_banner'))

                    @foreach (Banners::getOptions(Banners::get(old('meta_banner.id'))) as $key => $value)
                        <option value="{{ $key }}" @if($key == old('meta_banner.image')) selected="selected" @endif>{{ $value }}</option>
                    @endforeach

                @else

                    @foreach (Banners::getOptions(Banners::get(config('banners.default_banner'))) as $key => $value)
                        <option value="{{ $key }}" @if($key == old('meta_banner.image')) selected="selected" @endif>{{ $value }}</option>
                    @endforeach

                @endif

                </select>
            </div>
        </div>

    </div>

</div>

