@extends('frontend.layouts.page', ['title' => 'Deakin Professional Practice Credentials'])

<?php
    GlobalClass::add('body', 'section-browse-credentials');
?>

@section('content')
<!-- Notes: This will need to be replaced with & integrated with the CMS in the future! -->
    <article class="sections">
        <div class="multiple-section one-column-wysiwyg sections-pad-bottom sections-pad-top">
            <div class="container">
                <div class="col-sm-10 col-sm-offset-1 creds practice">
                    <h2>Professional Practice Credentials</h2>
                    <a href="{{ url('self-management-credential') }}" target="_parent" class="credential-icon text-center">
                        <img src="{{ asset('assets/images/credential-self-management.jpg') }}" alt="Self Management" />
                    </a>
                    <a href="{{ url('digital-literacy-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-digital-literacy.jpg') }}" alt="Digital Literacy" />
                    </a>
                    <a href="{{ url('teamwork-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-teamwork.jpg') }}" alt="Teamwork" />
                    </a>
                    <a href="{{ url('communication-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-communication.jpg') }}" alt="Communication" />
                    </a>
{{--                     <a href="{{ url('collaboration-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-collaboration.jpg') }}" alt="Collaboration" />
                    </a> --}}
                    <a href="{{ url('emotional-judgement-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-emotional-judgement.jpg') }}" alt="Emotional Judgement" />
                    </a>
                    <a href="{{ url('innovation-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-innovation.jpg') }}" alt="Innovation" />
                    </a>
                    <a href="{{ url('critical-thinking-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-critical-thinking.jpg') }}" alt="Critical Thinking" />
                    </a>
                    <a href="{{ url('problem-solving-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-problem-solving.jpg') }}" alt="Problem Solving" />
                    </a>
                    <a href="{{ url('global-citizenship-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-global-citizenship.jpg') }}" alt="Global Citizenship" />
                    </a>

{{--                     <a href="{{ url('cultural-engagement-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-cultural-engagement.jpg') }}" alt="Cultural Engagement" />
                    </a> --}}

                    <a href="{{ url('professional-ethics-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-professional-ethics.jpg') }}" alt="Professional Ethics" />
                    </a>
                </div>

                <div class="col-sm-10 col-sm-offset-1 creds professional-credential">
                    <h2>Professional Expertise Credentials</h2>

                    <a href="{{ url('digital-marketing-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-digital-marketing.jpg') }}" alt="Digital Marketing" />
                    </a>
                    <a href="{{ url('data-driven-marketing-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-data-driven.jpg') }}" alt="Data Driven Marketing" />
                    </a>
                    <a href="{{ url('content-marketing-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-content-marketing.jpg') }}" alt="Content Marketing" />
                    </a>
                    <a href="{{ url('creative-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-creative.jpg') }}" alt="Creative" />
                    </a>
                    <a href="{{ url('data-analytics-credential') }}" target="_parent" class="credential-icon  text-center">
                        <img src="{{ asset('assets/images/credential-data-analytics.jpg') }}" alt="Data Analytics" />
                    </a>
                </div>

                <div class="col-sm-10 col-sm-offset-1 creds qualifications">
                    <h2>Professional Practice Qualifications</h2>
                    <a href="{{ url('degrees') }}" target="_parent" class="col-sm-12 credential-degree">
                        <div class="row grey">
                            <div class="col-sm-4 col-md-2 text-center">
                                <img src="{{ asset('assets/images/deakin-university.png') }}" class="deakin-worldly-logo" width="150" />
                            </div>
                            <div class="col-sm-8 col-md-10 text-center">
                                <h3>Professional Practice Credentials from DeakinDigital can contribute towards Professional Practice Qualifications.</h3>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </article>

@endsection
