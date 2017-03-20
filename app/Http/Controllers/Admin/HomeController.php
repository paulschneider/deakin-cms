<?php
namespace App\Http\Controllers\Admin;

use Cache;
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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $activity = Cache::tags('active_users')->get('activity', []);

        return view('admin.home', compact('activity'));
    }
}
