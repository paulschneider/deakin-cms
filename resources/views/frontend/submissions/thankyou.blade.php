@extends('frontend.layouts.page', ['title' => 'Thank you'])

<?php
    GlobalClass::add('body', 'section-thankyou');
?>

@section('content')

    <article class="sections">
        <div class="multiple-section one-column-wysiwyg sections-pad-bottom sections-pad-top">

            <div class="container">

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 text-center">
                        <p>Thank you for your enquiry – A member of our team will contact you within the next few business days to discuss your requirements. </p>
                    </div>
                </div>

            </div>
        </div>
    </article>

@endsection
