<?php

namespace App\Repositories;

use Cache;
use Sunra\PhpSimple\HtmlDomParser;

class IconsRepository extends BasicRepository
{
    protected $cache_tags = ['icons'];

    protected $nullable = ['attachment_id'];

    /**
     * Specify the Model class name for the BasicRepository
     *
     * @return string
     */
    public function model()
    {
        return 'App\Models\Icon';
    }

    /**
     * Inject the icons into the source
     *
     * @param  string   $source  The html source
     * @param  array    $options The options array
     * @return string
     */
    public function injectIcons($source, $options)
    {
        $self = &$this;
        $html = HtmlDomParser::str_get_html($source);
        // HTML can be empty
        if (!$html) {
            return $source;
        }

        $elements = $html->find('span[data-icon-id]');

        // Cache query.
        $icons = Cache::tags('icons')->remember('icons:all', config('cache.icons_cache'), function () use ($self) {
            return $self->all(['with' => ['image']]);
        });

        foreach ($elements as $element) {
            if ($id = $element->attr['data-icon-id']) {
                // FA Icons
                if (preg_match('/^fa-/', $id)) {
                    $awesome = '<i class="fa ' . $id . '"></i>';
                    $icon    = collect([(object) ['svg' => $awesome]]);
                } else {
                    $icon = $icons->filter(function ($value) use ($id) {
                        return $value->id == $id;
                    });
                }
                if (count($icon)) {
                    $icon = $icon->first();
                    // Some have icon wrappers in them
                    if ($wrappers = $element->find('span[class="icon-wrapper"]')) {
                        foreach ($wrappers as $wrapper) {
                            $wrapper->innertext = $icon->svg;
                        }
                    } else {
                        $element->innertext = $icon->svg;
                    }

                    // if there is a fallback image, add it to the element
                    if (!empty($icon->image)) {
                        $element->setAttribute('data-icon-image', $icon->image->file->url());
                    }
                }
            }
        }

        // Insert font awesome element
        $awesome_elements = $html->find('span[data-icon]');
        foreach ($awesome_elements as $awesome) {
            $awesome->innertext = '<i class="fa ' . $awesome->attr['data-icon'] . '"></i>';
        }

        $source = $html->save();

        return $source;
    }
}
