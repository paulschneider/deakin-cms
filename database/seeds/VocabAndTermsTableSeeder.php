<?php

use Illuminate\Database\Seeder;

class VocabAndTermsTableSeeder extends Seeder
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

    public function run()
    {
        // Uncomment the below to wipe the table clean before populating
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('vocabularies')->truncate();
        DB::table('terms')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');

        $vocabs = [];

        $vocabs[] = [
            'id'          => 1,
            'name'        => 'Attachment Folders',
            'stub'        => 'attachments',
            'description' => 'Controls the structue for the attachment folders.',
        ];

        $vocabs[] = [
            'id'          => 2,
            'name'        => 'Block Categories',
            'stub'        => 'block-categories',
            'description' => 'The categories for the blocks.',
        ];

        $vocabs[] = [
            'id'          => 3,
            'name'        => 'Tags',
            'stub'        => 'tags',
            'description' => 'Misc tagging.',
        ];

        $vocabs[] = [
            'id'          => 4,
            'name'        => 'Article Types',
            'stub'        => 'article_types',
            'description' => 'Blog, News, Events, Media Releases, etc.',
        ];

        // $vocabs[] = array(
        //     'id'          => ,
        //     'name'        => '',
        //     'stub'        => '',
        //     'description' => '',
        // );

        $terms = [];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Uncategorised',
            'stub'          => uniqid(),
            'parent_id'     => null,
        ];

        $terms['pages'] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Images',
            'stub'          => uniqid(),
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Photos',
            'stub'          => uniqid(),
            'parent_id'     => 'pages',
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Logos',
            'stub'          => uniqid(),
            'parent_id'     => 'pages',
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Banners',
            'stub'          => uniqid(),
            'parent_id'     => 'pages',
        ];

        $terms['documents'] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Documents',
            'stub'          => uniqid(),
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'FAQ',
            'stub'          => uniqid(),
            'parent_id'     => 'documents',
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 1,
            'name'          => 'Profiles',
            'stub'          => uniqid(),
            'parent_id'     => 'documents',
        ];

        /**
         * Block categories
         */
        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 2,
            'name'          => 'Default',
            'stub'          => 'default',
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 2,
            'name'          => 'Form',
            'stub'          => 'form',
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 2,
            'name'          => 'Widget',
            'stub'          => 'widget',
            'parent_id'     => null,
        ];

        /**
         * Tags
         */
        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 3,
            'name'          => 'Test',
            'stub'          => 'test',
            'parent_id'     => null,
        ];

        /**
         * Article Types
         */
        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 4,
            'name'          => 'Press Releases',
            'stub'          => 'press',
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 4,
            'name'          => 'News',
            'stub'          => 'news',
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 4,
            'name'          => 'Events',
            'stub'          => 'events',
            'parent_id'     => null,
        ];

        $terms[] = [
            'id'            => static::next(),
            'vocabulary_id' => 4,
            'name'          => 'Media',
            'stub'          => 'media',
            'parent_id'     => null,
        ];

        // Convert parent to stubs.
        foreach ($terms as &$term) {
            if (!empty($term['parent_id'] && !is_numeric($term['parent_id']))) {
                $term['parent_id'] = $terms[$term['parent_id']]['id'];
            }
            $term['created_at'] = new DateTime;
            $term['updated_at'] = new DateTime;
        }

        foreach ($vocabs as &$vocab) {
            $vocab['created_at'] = new DateTime;
            $vocab['updated_at'] = new DateTime;
        }

        $terms = array_values($terms); // Remove custom keys.

        // Uncomment the below to run the seeder
        DB::table('vocabularies')->insert($vocabs);
        DB::table('terms')->insert($terms);
    }
}
