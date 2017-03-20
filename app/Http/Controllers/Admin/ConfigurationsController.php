<?php
namespace App\Http\Controllers\Admin;

use Flash;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteConfigurationFormRequest;
use App\Http\Requests\Admin\AdminConfigurationFormRequest;

class ConfigurationsController extends Controller
{
    /**
     * Get the list of the configurations
     *
     * @return Response
     */
    public function index()
    {
        $routes = [
            'admin.configurations.site.settings'  => 'Site settings',
            'admin.configurations.admin.settings' => 'Admin settings',
        ];

        return view()->make('admin.configurations.index', compact('routes'));
    }

    /**
     * Save the site settings
     *
     * @return Response
     */
    public function siteSettings()
    {
        return view('admin.configurations.site-settings');
    }

    /**
     * Save the site settings
     *
     * @param  SiteConfigurationFormRequest $request The request object
     * @return Response
     */
    public function saveSiteSettings(SiteConfigurationFormRequest $request)
    {
        return $this->saveSettings($request);
    }

    /**
     * Admin settings from
     *
     * @return Reponse
     */
    public function adminSettings()
    {
        return view('admin.configurations.admin-settings');
    }

    /**
     * Save the admin configurations form
     *
     * @param  AdminConfigurationFormRequest $request The request object
     * @return Response
     */
    public function saveAdminSettings(AdminConfigurationFormRequest $request)
    {
        return $this->saveSettings($request);
    }

    /**
     * Save settings
     *
     * @param  mixed      $request The request class
     * @return Response
     */
    protected function saveSettings($request)
    {
        $values = $request->all();
        foreach ($values as $key => $value) {
            $key = str_replace('__', '.', $key);
            \Variable::set($key, $value);
        }

        \Flash::success('Settings saved.');

        return redirect()->back();
    }

    /**
     * Callback to flush caches.
     * @return [type] [description]
     */
    public function clearCaches()
    {
        Flash::success('Cache cleared.');

        if (function_exists('opcache_reset')) {
            opcache_reset();
        }

        \Cache::flush();

        return redirect()->back();
    }
}
