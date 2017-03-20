<?php GlobalJs::add('header', '//www.google.com/recaptcha/api.js', '0'); ?>
<?php GlobalClass::add('body', 'has-recaptcha'); ?>
<div class="form-group recaptcha">
    <div class="text-center">
        <input type="input" class="g-recaptcha-required" required>
        <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_PUBLIC_KEY') }}" data-callback="reCaptchaCallback" data-expired-callback="reCaptchaExpired"></div>
    </div>
</div>