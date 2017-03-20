## Icon Laravel skeleton installed.

Laravel skeletons should all be relational paths.


**All developers please read Frontend Assets details bellow**

---

# app/bootstrap/start.php

```
$env = $app->detectEnvironment(function() {

    if (stristr(__FILE__, '/Volumes'))
        putenv('ENV', 'local');

    return (getenv("ENV") ?: null);
});
```

---

# Frontend Assets (Laravel)

Our assets setup is identical across frameworks, they all publish into an `assets/` folder and everything in there follows the same patterns. **The only thing that differs are the root paths to both the source and publish folder**.

For /Laravel the roots are:

* `/app/assets/` (source)
* `/public/assets/` (production)

---

`cd` into laravel project root

The frontend workflow uses **guard, bower and various gems**. If you are missing gems
you can run `bundle` to install required gems listed in the Gemfile (if missing bundle `gem install bundler`).

**missing any vendor assets?**
run the command `bower install` when you first start working on the project or you are missing any vendor assets.


**IMPORTANT**

All frontend assets should be kept in `app/assets/` **NOT in the public/assets folder**.
This includes images and any vendor/thirdparty lib that isn't consumed via sass or entangle.

Files placed in public/assets/ and not in the source (app/assets) will NOT be version controlled.

## Images

**Export all images from Photoshop etc. into this folder**

Guard shell will copy the images folder recursivly into public/assets/images. **This happens on guard start only** - you can setup a watch if desired.

## Vendor

All third party sass/js etc libs should be housed here.

**Wherever possible** try to use the bower.json to get vendor libraries either by actual registered bower lib or via direct git repo pointer.

**Wherever possible** try to consume the assets into sass and entagle respectively.

If not possible and a particular vendor library folder is required at public level set up guard to copy it to public via guard-shell - there are examples in the Guardfile 

**Any vendor folder that has been sourced by direct download and not via the bower workflow must be manually added to version control** at all times try to find a way to get the vendor via bower workflow (any git repo location can be used as a bower pointer regardless of wether it actually uses bower or not).

## CSS

Typically we consume any vendor sass (ie. bootstrap, mmenu) into the screen.scss

### Media Queries

By default we're using [Media Query Combiner](https://github.com/aaronjensen/sass-media_query_combiner) to combine any matching media query block into a single one. 

For most projects this is ideal, However if the project requires loading of css files individually you can disabled the combine by simply not requiring it in the compass `config.rb`

## JS

Typically we consume any vendor js (ie. bootstrap, mmenu) into the main.js
