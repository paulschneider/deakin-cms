@if (Session::has('flash_notification.message'))
    <div class="alert alert-dismissable alert-{{ Session::get('flash_notification.level') }}">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        {!! Filter::filter(Session::get('flash_notification.message'), ['purifier' => 'basic_html']) !!}
    </div>
@endif

@if (isset($errors))
    @if ($errors->any())
        <div class="alert alert-dismissable alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{!! Filter::filter($error, ['purifier' => 'basic_html']) !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endif