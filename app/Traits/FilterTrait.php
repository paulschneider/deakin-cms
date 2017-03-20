<?php
namespace App\Traits;

use Token;
use Purifier;

trait FilterTrait
{
    /**
     * Function used to build filtered content on pages and news.
     * @param  string   $attribute The model attribute.
     * @param  string   $config    The purifier config
     * @return string
     */
    public function filter($attribute, $config = 'full_html')
    {
        if (isset($this->{$attribute})) {
            // Purify First
            $content = Purifier::clean($this->{$attribute}, $config);

            $flags = [];

            // Add custom filters
            $content = Token::filter($this->{$attribute}, $flags);

            return $content;
        }

        return null;
    }
}
