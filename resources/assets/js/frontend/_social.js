(function($) {
  CMS.social = function() {
    $('body .social').social({});
  };
}(jQuery));

/**
 *  Social media share plugin
 *  (Facebook, Twitter, Linkedin, Email)
 */
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, document, undefined ) {

  "use strict";

    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = "social",
      defaults = {
        // set true to enable or false to disable the social media plugin
        facebook: true,
        twitter: true,
        linkedin: true,
        email: true
      };

    // The actual plugin constructor
    function Plugin ( element, options ) {
      this.element = element;
      // jQuery has an extend method which merges the contents of two or
      // more objects, storing the result in the first object. The first object
      // is generally empty as we don't want to alter the default options for
      // future instances of the plugin
      this.settings = $.extend( {}, defaults, options );
      this._defaults = defaults;
      this._name = pluginName;
      this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
      /**
       * Initialise social plugin
       */
      init: function () {
        this.pageTitle = ((typeof $('meta[name=title]').attr('content') === "undefined") ? document.title : $('meta[name=title]').attr('content'));
        this.url = window.location.href;
        this.left = ($(window).width()/2)-(675/2);
        this.top = ($(window).height()/2)-(475/2);

        if (this.settings.facebook) this.initFacebook();
        if (this.settings.twitter) this.initTwitter();
        if (this.settings.linkedin) this.initLinkedin();
        if (this.settings.email) this.initEmail();

        this.delegateEvents();
      },
      /**
       * Initalise Facebook
       */
      initFacebook: function() {
        $('div.social').append('<a class="facebook-share-btn">Facebook</a>');
      },
      /**
       * Initialise Twitter
       */
      initTwitter: function() {
        $('div.social').append('<a class="twitter-share-btn">Twitter</a>');
      },
      /**
       * Initialise Linkedin
       */
      initLinkedin: function() {
        $('div.social').append('<a class="linkedin-share-btn">Linkedin</a>');
      },
      /**
       * Initialise Email
       */
      initEmail: function() {
        $('div.social').append('<a class="email-share-btn">Email</a>');
      },
      /**
       * Handle facebook share on click button
       */
      facebookShareHandler: function () {
        var that = this,
            facebook_url = encodeURI('http://www.facebook.com/sharer.php?u='+this.url);

        $(document).on("click", '.facebook-share-btn', function(e) {
          e.preventDefault();
          window.open(facebook_url,"Share on Facebook","menubar=1,resizable=1,width=675,height=475,top="+that.top+", left="+that.left);
        });
      },
      /**
       * Handle twitter share on click button
       */
      twitterShareHandler: function () {
        var that = this,
            twitter_url = encodeURI('https://twitter.com/share?url='+this.url+'&text='+this.pageTitle);

        $(document).on("click", '.twitter-share-btn', function(e) {
          e.preventDefault();
          window.open(twitter_url,"Share a link on Twitter","menubar=1,resizable=1,width=675,height=475,top="+that.top+", left="+that.left);
        });
      },
      /**
       * Handle linkedin share on click button
       */
      linkedinShareHandler: function () {
        var that = this,
            linkedin_url = encodeURI('http://www.linkedin.com/shareArticle?mini=true&url='+this.url+'&title='+this.pageTitle);

        $(document).on("click", '.linkedin-share-btn', function(e) {
          e.preventDefault();
          window.open(linkedin_url,"Share news on Linkedin","menubar=1,resizable=1,width=675,height=475,top="+that.top+", left="+that.left);
        });
      },
      /**
       * Handle email share on click button
       */
      emailShareHandler: function () {
        var subject = this.pageTitle + ' - Check this out!',
            body = this.url;

        $(document).on("click", '.email-share-btn', function(e) {
          e.preventDefault();
          window.location.href = "mailto:?subject="+subject+"&body="+body;
        });
      },
      /**
       * Handle series of events for social plugin
       * e.g. on click facebook, twitter, linkedin, email buttons
       */
      delegateEvents: function () {
        this.facebookShareHandler();
        this.twitterShareHandler();
        this.linkedinShareHandler();
        this.emailShareHandler();
      }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function ( options ) {
      return this.each(function() {
        if ( !$.data( this, "plugin_" + pluginName ) ) {
          $.data( this, "plugin_" + pluginName, new Plugin( this, options ) );
        }
      });
    };

})( jQuery, window, document );
