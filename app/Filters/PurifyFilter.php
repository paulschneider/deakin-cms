<?php

namespace App\Filters;

/*
 * Laravel 5 HTMLPurifier package
 *
 * @version   2.0.0
 * @contact me@mewebstudio.com
 * @web http://www.mewebstudio.com
 * @date      2014-04-02
 * @author    Muharrem ERİN
 * @copyright Copyright (c) 2015 MeWebStudio
 * @license   MIT
 */
use HTMLPurifier;
use HTMLPurifier_Config;
use Mews\Purifier\Purifier;

class PurifyFilter extends Purifier
{
    private function setUp()
    {
        if (!$this->config->has('purifier')) {
            if (!$this->config->has('mews.purifier')) {
                throw new Exception('Configuration parameters not loaded!');
            }
            $this->config->set('purifier', $this->config->get('mews.purifier'));
        }

        $this->checkCacheDirectory();
    }

    /**
     * Check/Create cache directory
     */
    private function checkCacheDirectory()
    {
        $cachePath = $this->config->get('purifier.cachePath');

        if ($cachePath) {
            if (!$this->files->isDirectory($cachePath)) {
                $this->files->makeDirectory($cachePath);
            }
        }
    }

    /**
     * @param  $dirty
     * @param  null     $config
     * @return mixed
     */
    public function clean($dirty, $config = null)
    {
        if (is_array($dirty)) {
            return array_map(function ($item) use ($config) {
                return $this->clean($item, $config);
            }, $dirty);
        } else {
            //the htmlpurifier use replace instead merge, so we merge

            $configName  = $config;
            $configArray = $this->getConfig($config);

            $config = HTMLPurifier_Config::createDefault();

            $config->loadArray($configArray);

            // ADDON
            $config->set('HTML.DefinitionID', 'laravel-iconinc-' . $configName);
            $config->set('HTML.DefinitionRev', $this->config->get('purifier.version'));

            if ($def = $config->maybeGetRawHTMLDefinition()) {
                $extras = config('purifier.custom_definition');

                foreach ($extras as $tag => $options) {
                    if (is_array($options)) {
                        foreach ($options as $attr => $type) {
                            $def->addAttribute($tag, $attr, $type);
                        }
                    }
                }

                // HTML5 Support
                $def->addElement('section', 'Block', 'Flow', 'Common');
                $def->addElement('nav', 'Block', 'Flow', 'Common');
                $def->addElement('article', 'Block', 'Flow', 'Common');
                $def->addElement('aside', 'Block', 'Flow', 'Common');
                $def->addElement('header', 'Block', 'Flow', 'Common');
                $def->addElement('footer', 'Block', 'Flow', 'Common');
                $def->addElement('address', 'Block', 'Flow', 'Common');
                $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
                $def->addElement('figcaption', 'Inline', 'Flow', 'Common');
                $def->addElement('sub', 'Inline', 'Inline', 'Common');
                $def->addElement('sup', 'Inline', 'Inline', 'Common');
                $def->addElement('mark', 'Inline', 'Inline', 'Common');
            }

            // Allow configuration to be modified
            if (!$this->config->get('purifier.finalize')) {
                $config->autoFinalize = false;
            }

            $purifier = new HTMLPurifier($config);

            return $purifier->purify($dirty);
        }
    }
}
