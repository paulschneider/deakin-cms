<footer>

    {{-- Optional via JS embed string --}}
    @if (!isset($social) || $social !== false)
        <div class="social-media-container">
            <div class="row">
                <div class="text-center col-md-12">
                    <a href="https://www.linkedin.com/company/deakindigital-pty-ltd" target="_blank" class="social-icon"><img src="{{ asset('assets/images/li.png') }}" /></a>
                    <a href="https://twitter.com/deakindigital" target="_blank" class="social-icon tw"><img src="{{ asset('assets/images/tw.png') }}" /></a>
                    <a href="https://www.youtube.com/channel/UCS6O8q7QVyoqC3EUisv3UYQ" target="_blank" class="social-icon yt"><img src="{{ asset('assets/images/yt.png') }}" /></a>
                    <p>Join the conversation</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Only show on main site, otherwise requires JS --}}
    @if (!isset($partners) || $partners !== false)
        <div class="partners-container container">
            <div class="row">
                <div class="col-sm-12">
                    <ul>
                        <li class="current"><img src="{{ asset('assets/images/partners/ADMA IQ.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/ATO.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/Cisco.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/Deakin-Prime.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/Hudson.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/IBMresearch.png') }}" class="img-responsive"></li>
                        <li><img src="{{ asset('assets/images/partners/NAB.png') }}" class="img-responsive"></li>
                    </ul>
                    <p>Our customers and partners</p>
                </div>
            </div>
        </div>
    @endif

    {{-- <a href="#" class="pull-right to-top"><i class="fa fa-chevron-up"></i>Top</a> --}}

    <div class="footer-container">
        @if ( ! empty($blocks['footer']))
            @foreach ($blocks['footer'] as $block)
                {!! $block !!}
            @endforeach
        @endif

        <div class="container top-content">
            <div class="row">
                <div class="col-md-9">
                    @include('frontend.common.nav-footer', ['items' => $menu_footer->roots()])
                </div>

                <div class="col-md-3">
                    <div class="footer-img-logo">
                        <img class="img-responsive" src="{{ asset('assets/images/deakin-wordly-logo.png') }}" width="230" />
                    </div>

                    {{-- External sites require font-awesome to show this icon --}}
                    <a href="{{ url('newsletter-signup') }}" class="newsletter-signup"><img src="{{ asset('assets/images/icons/envelope.png') }}" width="14" height="11" alt=""> Sign-up to DeakinDigital</a>

                </div>
            </div>
        </div>

        <div class="container bottom-content">
            <div class="row">
                <div class="col-md-7">
                    <small>
                        <ul class="nav nav-pills">
                            <li><a href="{{ url('data-collection-notice') }}">Data Collection Notice</a></li>
                            <li><a href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                            <li><a href="{{ url('terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                            <li><a href="{{ url('copyright-notice') }}">Copyright Notice</a></li>
                        </ul>
                    </small>
                </div>
                <div class="col-md-5 copyright">
                    <p>{!! Variable::get('site.copyright', 'Copyright') !!}</p>
                    @if (!isset($icon) || $icon !== false)
                        <p><a href="http://iconinc.com.au/" target="_blank">Site by Icon</a></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

</footer>
