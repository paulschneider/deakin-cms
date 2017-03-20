<?php
namespace App\Http\Controllers\FrontEnd;

use Storage;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home Controller
    |--------------------------------------------------------------------------
    |
    | This controller renders your application's "dashboard" for users that
    | are authenticated. Of course, you are free to change or remove the
    | controller as you wish. It is just here to get your app started!
    |
     */

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        return app('App\Http\Controllers\FrontEnd\DynamicController')->dynamic('home');
    }

    /**
     * Show the application sitemap to the user.
     *
     * @return Response
     */
    public function sitemap()
    {
        $content = Storage::get('sitemap.xml');

        return response($content)->header('Content-Type', 'text/xml');
    }

    /**
     * Return the HTML for the footer.
     * Used in sibling websites.
     *
     * @return html
     */
    public function footer()
    {
        $css = file_get_contents(public_path('assets/css/frontend/footer.css'));
        $css = preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url(' . url('/') . '/$1)', $css);

        $social = \Request::get('social', true);

        if ($social === "false") {
            $social = false;
        }

        $icon = \Request::get('icon', true);

        if ($icon === "false") {
            $icon = false;
        }

        $result = [
            'css'     => $css,
            'content' => view('frontend.common.footer-external', compact('social', 'icon'))->render(),
        ];

        // Use send() to change cache headers()
        response()
            ->json($result)
            ->setMaxAge(290304000)
            ->setPublic()
            ->header('Pragma', 'public')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Request-Headers', 'cache-control')
            ->send();
    }
}
