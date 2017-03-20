<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::pattern('id', '\d+');
Route::pattern('sid', '\d+');
Route::pattern('hash', '[A-Za-z0-9]+');
Route::pattern('hex', '[a-f0-9]+');
Route::pattern('yyyy_mm_dd', '[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}');
Route::pattern('uuid', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::pattern('base', '[a-zA-Z0-9]+');
Route::pattern('slug', '[a-z0-9-]+');
Route::pattern('username', '[a-z0-9_-]{3,16}');
Route::pattern('page_string', '[A-Za-z0-9-_]+((/[A-Za-z0-9-_]+)?)+?');
Route::pattern('file_string', '[A-Za-z0-9-_]+((/[A-Za-z0-9-_]+)?)+?\.([a-z]+)');

// Only allow the types of forms available
$types = array_keys(config('forms.forms'));
$types = implode('|', $types);
Route::pattern('form_types', $types);

Route::get('activate/{hash}', ['uses' => 'Auth\ActivateController@activate']);
Route::post('activate', ['uses' => 'Auth\ActivateController@postActivate']);
Route::get('logout', 'Auth\LoginController@logout');

Auth::routes();

Route::group(['namespace' => 'FrontEnd'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('search', ['as' => 'search', 'uses' => 'SearchController@index']);

    Route::get('pages/{id}', ['as' => 'frontend.pages.show', 'uses' => 'PagesController@show']);
    Route::get('credentials/{id}', ['as' => 'frontend.credentials.show', 'uses' => 'CredentialsController@show']);

    Route::get('articles', ['as' => 'frontend.articles.index', 'uses' => 'ArticlesController@index']);
    Route::get('articles/{id}', ['as' => 'frontend.articles.show', 'uses' => 'ArticlesController@show']);
    Route::get('articles/{slug}', ['as' => 'frontend.articles.slug', 'uses' => 'DynamicController@dynamic']);

    // Forms
    Route::post('contact', ['as' => 'frontend.contact.save', 'uses' => 'SubmissionsController@contactSubmit']);
    Route::get('contact/thankyou', ['as' => 'frontend.contact.thankyou', 'uses' => 'SubmissionsController@genericThankyou']);

    Route::post('newsletter', ['as' => 'frontend.newsletter.save', 'uses' => 'SubmissionsController@newsletterSubmit']);
    Route::get('newsletter/thankyou', ['as' => 'frontend.newsletter.thankyou', 'uses' => 'SubmissionsController@newsletterThankyou']);

    // Sitemap
    Route::get('sitemap.xml', 'HomeController@sitemap');
    Route::get('footer.html', 'HomeController@footer');

    // Browse Credentials page
    Route::get('browse-credentials', 'CredentialsController@index');
    Route::get('credentials-and-degrees', 'CredentialsController@index');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'acl', 'activity', 'admin.only']], function () {
    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);

    // Pages
    Route::get('pages/index/data', ['as' => 'pages.index.data', 'uses' => 'PagesController@indexData']);
    Route::resource('pages', 'PagesController');
    Route::get('pages/{id}/delete', ['as' => 'pages.delete', 'uses' => 'PagesController@delete']);
    Route::resource('pages.revisions', 'RevisionsPageController');

    // Credentials
    Route::get('credentials/index/data', ['as' => 'credentials.index.data', 'uses' => 'CredentialsController@indexData']);
    Route::resource('credentials', 'CredentialsController');
    Route::get('credentials/{id}/delete', ['as' => 'credentials.delete', 'uses' => 'CredentialsController@delete']);
    Route::resource('credentials.revisions', 'RevisionsCredentialController');

    // Cron Jobs
    Route::resource('cron', 'CronController');
    Route::get('cron/{id}/delete', ['as' => 'cron.delete', 'uses' => 'CronController@delete']);

    // Attachments
    Route::get('attachments/listing/data', ['as' => 'attachments.listing.data', 'uses' => 'AttachmentsController@indexData']);
    Route::get('attachments/iframe', ['as' => 'attachments.iframe', 'uses' => 'AttachmentsController@iframe']);
    Route::get('attachments/iframe/preview/{id?}', ['as' => 'attachments.preview', 'uses' => 'AttachmentsController@iframePreview']);
    Route::get('attachments/tree', ['as' => 'attachments.tree', 'uses' => 'AttachmentsController@ajaxTree']);
    Route::post('attachments/iframe/wysiwyg', ['as' => 'attachments.wysiwyg', 'uses' => 'AttachmentsController@wysiwyg']);
    Route::post('attachments/dropzone', ['as' => 'attachments.dropzone', 'uses' => 'AttachmentsController@dropzone']);
    Route::patch('attachments/modify_selected', ['as' => 'attachments.move', 'uses' => 'AttachmentsController@modifySelected']);
    Route::get('attachments/url/{id}', ['as' => 'attachments.url', 'uses' => 'AttachmentsController@fileUrl']);

    Route::resource('attachments', 'AttachmentsController');
    Route::get('attachments/{id}/delete', ['as' => 'attachments.delete', 'uses' => 'AttachmentsController@delete']);

    // Vocabularies
    Route::resource('vocabularies', 'VocabulariesController');
    Route::get('vocabularies/{id}/delete', ['as' => 'vocabularies.delete', 'uses' => 'VocabulariesController@delete']);

    // Vocabularies Sorting.
    Route::get('vocabularies/{id}/sort', ['as' => 'vocabularies.sort', 'uses' => 'VocabulariesController@sort']);
    Route::patch('vocabularies/{id}/sort', ['as' => 'vocabularies.sort', 'uses' => 'VocabulariesController@sortSubmit']);

    // Terms
    Route::resource('vocabularies.terms', 'TermsController');
    Route::get('vocabularies/{id}/terms/{sid}/delete', ['as' => 'vocabularies.terms.delete', 'uses' => 'TermsController@delete']);

    // Blocks
    Route::get('blocks/create/{type}', ['as' => 'blocks.create.type', 'uses' => 'BlocksController@createType']);
    Route::get('blocks/{id}/delete', ['as' => 'blocks.delete', 'uses' => 'BlocksController@delete']);
    Route::resource('blocks', 'BlocksController');

    // Articles
    Route::get('articles/index/data', ['as' => 'articles.index.data', 'uses' => 'ArticlesController@indexData']);
    Route::get('articles/{id}/delete', ['as' => 'articles.delete', 'uses' => 'ArticlesController@delete']);
    Route::resource('articles', 'ArticlesController');
    Route::resource('articles.revisions', 'RevisionsArticleController');

    // Alias
    Route::get('aliases/index/data', ['as' => 'aliases.index.data', 'uses' => 'AliasesController@indexData']);
    Route::resource('aliases', 'AliasesController');
    Route::get('aliases/{id}/delete', ['as' => 'aliases.delete', 'uses' => 'AliasesController@delete']);

    // Menus
    Route::resource('menus', 'MenusController');
    Route::get('menus/{id}/delete', ['as' => 'menus.delete', 'uses' => 'MenusController@delete']);
    Route::get('menus/{id}/sort', ['as' => 'menus.sort', 'uses' => 'MenusController@sort']);
    Route::patch('menus/{id}/sort', ['as' => 'menus.sort', 'uses' => 'MenusController@sortSubmit']);

    Route::get('menus/{id}/links/index/data', ['as' => 'menus.links.data', 'uses' => 'MenusLinksController@indexData']);
    Route::get('menus/{id}/links/ajax/{sid?}', ['as' => 'menus.links.ajax', 'uses' => 'MenusLinksController@getOptions']);
    Route::resource('menus.links', 'MenusLinksController');
    Route::get('menus/{id}/links/{sid}/delete', ['as' => 'menus.links.delete', 'uses' => 'MenusLinksController@delete']);

    // Banners
    // NOTE: Some are renamed to bananas.
    // This is due to adblock blocking ajax requests with the word banner.
    Route::resource('banners', 'BannersController', ['except' => ['show']]);
    Route::get('banners/{id}/delete', ['as' => 'banners.delete', 'uses' => 'BannersController@delete']);
    Route::get('bananas/{id}/sort', ['as' => 'banners.sort', 'uses' => 'BannersController@sort']);
    Route::patch('bananas/{id}/sort', ['as' => 'banners.sort', 'uses' => 'BannersController@sortSubmit']);
    Route::get('bananas/{id}/images/ajax/{sid?}', ['as' => 'banners.images.ajax', 'uses' => 'BannersImagesController@getOptions']);
    Route::resource('banners.images', 'BannersImagesController');
    Route::get('banners/{id}/images/{sid}/delete', ['as' => 'banners.images.delete', 'uses' => 'BannersImagesController@delete']);
    Route::get('bananas/ajax/{banners}', ['as' => 'banners.ajax', 'uses' => 'BannersImagesController@ajaxImage']);

    // ACL (Assigning Permissions)
    Route::get('acl', ['as' => 'acl.index', 'uses' => 'AclController@index']);
    Route::patch('acl', ['as' => 'acl.update', 'uses' => 'AclController@update']);

    // Users (me)
    Route::get('users/me', ['as' => 'users.me', 'uses' => 'UsersController@me']);
    Route::patch('users/me', ['as' => 'users.me', 'uses' => 'UsersController@updateMe']);

    // Users
    Route::resource('users', 'UsersController');
    Route::get('users/{id}/delete', ['as' => 'users.delete', 'uses' => 'UsersController@delete']);
    Route::get('users/{id}/reset', ['as' => 'users.reset', 'uses' => 'UsersController@resetPassword']);
    Route::get('users/{id}/activate', ['as' => 'users.activate', 'uses' => 'UsersController@resetActivation']);

    // The configurations
    Route::get('configurations', ['as' => 'configurations.index', 'uses' => 'ConfigurationsController@index']);
    Route::get('configurations/site-settings', ['as' => 'configurations.site.settings', 'uses' => 'ConfigurationsController@siteSettings']);
    Route::post('configurations/site-settings', ['as' => 'configurations.site.settings.save', 'uses' => 'ConfigurationsController@saveSiteSettings']);

    Route::get('configurations/admin-settings', ['as' => 'configurations.admin.settings', 'uses' => 'ConfigurationsController@adminSettings']);
    Route::post('configurations/admin-settings', ['as' => 'configurations.admin.settings.save', 'uses' => 'ConfigurationsController@saveAdminSettings']);

    Route::get('configurations/cache', ['as' => 'configurations.cache', 'uses' => 'ConfigurationsController@clearCaches']);

    // Form Submissions
    Route::get('submissions', ['as' => 'submissions.list', 'uses' => 'SubmissionsController@showList']);
    Route::get('submissions/{form_types}', ['as' => 'submissions.type', 'uses' => 'SubmissionsController@index']);
    Route::get('submissions/{form_types}/{submissions}/edit', ['as' => 'submissions.type.edit', 'uses' => 'SubmissionsController@edit']);
    Route::patch('submissions/{form_types}/{submissions}', ['as' => 'submissions.type.update', 'uses' => 'SubmissionsController@update']);
    Route::get('submissions/{form_types}/{submissions}/delete', ['as' => 'submissions.type.delete', 'uses' => 'SubmissionsController@delete']);
    Route::delete('submissions/{form_types}/{submissions}', ['as' => 'submissions.type.destroy', 'uses' => 'SubmissionsController@destroy']);

    // Sections
    Route::get('sections/template/{type}/{counter?}', ['as' => 'sections.template', 'uses' => 'SectionsController@template']);
    Route::get('sections/block-render/{block_id}', ['as' => 'sections.block-render', 'uses' => 'SectionsController@blockRender']);

    // Icons
    Route::get('icons/iframe', ['as' => 'icons.iframe', 'uses' => 'IconsController@iframe']);
    Route::get('icons/fontawesome', ['as' => 'icons.fontawesome', 'uses' => 'IconsController@fontawesome']);
    Route::post('icons/wysiwyg/{icons}', ['as' => 'icons.wysiwyg', 'uses' => 'IconsController@wysiwyg']);
    Route::resource('icons', 'IconsController');
    Route::get('icons/{icons}/delete', ['as' => 'icons.delete', 'uses' => 'IconsController@delete']);
});

Route::post("rampart-lock", "\App\Http\Controllers\Admin\RampartController@index");

// Has to come last.
Route::group(['namespace' => 'FrontEnd'], function () {
    // Dynamic Slugs
    Route::get('{page_string}', ['as' => 'frontend.dynamic.slug', 'uses' => 'DynamicController@dynamic']);
    // // Dynamic slug based URLs
    Route::get('{file_string}', ['uses' => 'DynamicController@attachment']);
});
