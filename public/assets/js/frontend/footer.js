var loadFooterExternal = function() {
    var src = document.getElementById('external-footer-js').src;
    var url = src.match(new RegExp('https?://[^/]*'));
    var social = (src.match('social=false') ? false : true);
    var icon = (src.match('icon=false') ? false : true);
    var fonts = (src.match('fonts=false') ? false : true);
    var request = new XMLHttpRequest();
    var loadFrom = url + '/footer.html?social=' + social + '&icon=' + icon;

    if (fonts) {
      // Fonts
      (function(d) {
        var config = {
          kitId: 'muh1abe',
          scriptTimeout: 3000,
          async: true
        },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    }

    request.open('GET', loadFrom, true);

    request.onreadystatechange = function() {
      if (this.readyState === 4) {
        if (this.status >= 200 && this.status < 400) {
          var data = JSON.parse(this.responseText);
          // Insert CSS
          var style = document.createElement('style');
          style.type = 'text/css';
          style.innerHTML = data.css;
          document.getElementsByTagName('head')[0].appendChild(style);
          // Insert HTML
          document.getElementById('external-footer').innerHTML = data.content;
        }
      }
    };

    request.send();
    request = null;

};

loadFooterExternal();
//# sourceMappingURL=footer.js.map
