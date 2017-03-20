<?php

use Illuminate\Database\Seeder;

class MenuAndLinksTableSeeder extends Seeder
{
    private static $i = 1;

    private static function next()
    {
        return static::$i++;
    }

    private static function current()
    {
        return static::$i - 1;
    }

    private static function previous($before = 1)
    {
        return static::$i - $before;
    }

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('menus')->truncate();
        DB::table('menus_links')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $menus = [];

        $menus[] = [
            'id'    => 1,
            'title' => 'Admin Menu',
            'stub'  => 'admin',
        ];

        $menus[] = [
            'id'    => 2,
            'title' => 'Main Menu',
            'stub'  => 'main',
        ];

        $menus[] = [
            'id'    => 3,
            'title' => 'Footer',
            'stub'  => 'footer',
        ];

        $links = [];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Admin',
            'route'     => 'admin',
            'parent_id' => null,
            'icon'      => 'th-large',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Pages',
            'route'     => 'admin/pages',
            'parent_id' => null,
            'icon'      => 'edit',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'News + Blog',
            'route'     => 'admin/articles',
            'parent_id' => null,
            'icon'      => 'newspaper-o',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Files',
            'route'     => 'admin/attachments',
            'parent_id' => null,
            'icon'      => 'picture-o',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Blocks',
            'route'     => 'admin/blocks',
            'parent_id' => null,
            'icon'      => 'cubes',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Menus',
            'route'     => 'admin/menus',
            'parent_id' => null,
            'icon'      => 'indent',
        ];

        $links['config'] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Configuration',
            'route'     => 'admin/configurations',
            'parent_id' => null,
            'icon'      => 'cogs',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'ACL',
            'route'     => 'admin/acl',
            'parent_id' => 'config',
            'icon'      => 'lock',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Banners',
            'route'     => 'admin/banners',
            'parent_id' => 'config',
            'icon'      => 'photo',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Users',
            'route'     => 'admin/users',
            'parent_id' => 'config',
            'icon'      => 'user',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Vocabularies',
            'route'     => 'admin/vocabularies',
            'parent_id' => 'config',
            'icon'      => 'table',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Form Submissions',
            'route'     => 'admin/submissions',
            'parent_id' => null,
            'icon'      => 'check-square-o',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Contact Form',
            'route'     => 'admin/submissions/contact',
            'parent_id' => static::previous(2),
            'icon'      => 'check-square-o',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Scheduled Tasks',
            'route'     => 'admin/cron',
            'parent_id' => 'config',
            'icon'      => 'clock-o',
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 1,
            'title'     => 'Icons',
            'route'     => 'admin/icons',
            'parent_id' => 'config',
            'icon'      => 'arrow-circle-right',
        ];

        // $links[] = [
        //     'id'        => static::next(),
        //     'menu_id'   => 2,
        //     'title'     => 'Home',
        //     'route'     => '',
        //     'parent_id' => null,
        // ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 3,
            'title'     => 'Privary Policy and Collection Statement',
            'route'     => '',
            'parent_id' => null,
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 3,
            'title'     => 'Disclaimer',
            'route'     => '',
            'parent_id' => null,
        ];

        $links[] = [
            'id'        => static::next(),
            'menu_id'   => 3,
            'title'     => 'Site by Icon',
            'route'     => '',
            'parent_id' => null,
        ];

        // Convert parent to stubs.
        foreach ($links as $k => $link) {
            if (!empty($link['parent_id'] && !is_numeric($link['parent_id']))) {
                $links[$k]['parent_id'] = $links[$link['parent_id']]['id'];
            }
            $links[$k]['created_at'] = new DateTime;
            $links[$k]['updated_at'] = new DateTime;
        }

        foreach ($menus as &$menu) {
            $menu['created_at'] = new DateTime;
            $menu['updated_at'] = new DateTime;
        }

        $links = array_values($links); // Remove custom keys.

        foreach ($links as $k => $link) {
            $link['weight'] = $k;
        }

        // Uncomment the below to run the seeder
        DB::table('menus')->insert($menus);

        foreach ($links as $link) {
            $icon = null;

            if (!empty($link['icon'])) {
                $icon = $link['icon'];
                unset($link['icon']);
            }

            DB::table('menus_links')->insert($link);

            if (!empty($icon)) {
                $inserted = DB::getPdo()->lastInsertId();

                DB::table('metas')->insert([
                    'metable_type' => "App\Models\MenuLink",
                    'metable_id'   => $inserted,
                    'key'          => 'meta_icon',
                    'value'        => $icon,
                    'created_at'   => new DateTime,
                    'updated_at'   => new DateTime,
                ]);
            }
        }

        Cache::tags("menus")->flush();
        Cache::tags("acl")->flush();
        Cache::tags("urls")->flush();
    }
}
