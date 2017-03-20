@extends('frontend.layouts.page', ['title' => 'Thank you'])

<?php
    GlobalClass::add('body', ['section-thankyou', 'tick-form']);
?>

@section('content')

    <article class="sections">
        <div class="multiple-section one-column-wysiwyg sections-pad-bottom sections-pad-top">

            <div class="container">

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text-center">

                        <div class="trigger"></div>

                        <svg version="1.1" id="tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 37 37" style="enable-background:new 0 0 37 37;" xml:space="preserve">
                            <path class="tick-circ tick-path" style="fill:none;stroke:#000;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" d="M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"/>
                            <polyline class="tick-tick tick-path" style="fill:none;stroke:#000;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;" points="11.6,20 15.9,24.2 26.4,13.8 "/>
                        </svg>

                        <p>Thank you for signing up! <a href="/" class="close">Continue to DeakinDigital</a>.</p>
                    </div>
                </div>

            </div>
        </div>
    </article>

    <script type="text/javascript">
        window.parent.jQuery('.mfp-close').addClass('mfp-close-dark');
    </script>

@endsection
