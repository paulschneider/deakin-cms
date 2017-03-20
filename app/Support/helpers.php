<?php

if (!function_exists('hasMorePages')) {
    function hasMorePages(Illuminate\Pagination\LengthAwarePaginator $articles)
    {
        return $articles->currentPage() < $articles->lastPage();
    }
}

// Shorter DIRECTORY_SEPARATOR
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Explore, shorten and return last bit,
if (!function_exists('get_short_name')) {
    function get_short_name($string, $deli = '\\', $lower = true)
    {
        if (is_object($string)) {
            $string = get_class($string);
        }

        $string = explode($deli, $string);
        $string = end($string);

        if ($lower) {
            $string = strtolower($string);
        }

        return $string;
    }
}

// CKEditor Templates
if (!function_exists('ck_only')) {
    function ck_only($options, $true, $false)
    {
        return (!empty($options['_ckeditor'])) ? $true : $false;
    }
}

// Truncate html
if (!function_exists('truncate_html')) {
    function truncate_html($string, $length, $elipsis = '&hellip;', $isHTML = true, $attachment = '')
    {
        $string  = trim($string);
        $elipsis = (strlen(strip_tags($string)) > $length) ? $elipsis : '';
        $counter = 0;
        $tags    = [];
        $ignores = ['br', 'img', 'hr'];

        if ($isHTML) {
            preg_match_all('/<[^>]+>([^<]*)/', $string, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
            foreach ($matches as $match) {
                if ($match[0][1] - $counter >= $length) {
                    break;
                }
                $tag = substr(strtok($match[0][0], " \t\n\r\0\x0B>"), 1);
                if ($tag[0] != '/' && !in_array($tag, $ignores)) {
                    $tags[] = $tag;
                } elseif (end($tags) == substr($tag, 1)) {
                    array_pop($tags);
                }
                $counter += $match[1][1] - $match[0][1];
            }
        }
        $output = substr($string, 0, $length = min(strlen($string), $length + $counter)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

        $output = append_in_tag($output, $elipsis);

        // Strip out junk.
        $output = preg_replace('/[\r\n\t\v]/', '', $output);
        $output = preg_replace('/<p[^>]*>[\s|&nbsp;]*<\/p>/', '', $output);
        $output = str_replace('<p></p>', '', $output);

        return trim($output);
    }
}

// Append in the last tag
if (!function_exists('append_in_tag')) {
    function append_in_tag($string, $append)
    {
        // Replace all spaces between tags
        $string = preg_replace('/(?<=>)[\n\r\s]+(?=<)/', '', $string);
        // Find the last tag
        preg_match('/(<\/\w+>)+$/', $string, $match, PREG_OFFSET_CAPTURE);
        if (!empty($match[0][1])) {
            $start  = substr($string, 0, $match[0][1]);
            $end    = substr($string, $match[0][1]);
            $string = $start . $append . $end;
        }

        return $string;
    }
}

if (!function_exists('add_url_and_ip')) {
    function add_url_and_ip($data)
    {
        // Get the ip addresss
        $data['ip_address'] = $_SERVER['REMOTE_ADDR'];
        // Get the url of the entry
        $root        = URL::to('/');
        $url         = URL::previous();
        $data['uri'] = preg_replace('/^' . preg_quote($root, '/') . '/', '', $url);

        return $data;
    }
}

if (!function_exists('is_serialized')) {
    function is_serialized($data, $strict = true)
    {
        // if it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace     = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }

            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }

            if (false !== $brace && $brace < 4) {
                return false;
            }
        }
        $token = $data[0];
        switch ($token) {
            case 's':
                if ($strict) {
                    if ('"' !== substr($data, -2, 1)) {
                        return false;
                    }
                } elseif (false === strpos($data, '"')) {
                    return false;
                }
            // or else fall through
            case 'a':
            case 'O':
                return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
        }
        return false;
    }
}

// Only uses the versioned files if it's in production
// This is for speed of compilation
if (!function_exists('elixir_source')) {
    function elixir_source($file)
    {
        if (env('APP_ENV') == 'production') {
            return url(elixir($file));
        }

        if (env('APP_ENV') == 'staging') {
            return asset($file) . '?' . date('Y-m-d-His');
        }

        return asset($file);
    }
}

// Could do with the NumberFormat class, but didn't want to have intl as a dependency
if (!function_exists('number_spellout_ordinal')) {
    function number_spellout_ordinal($number)
    {
        switch ($number) {
            case 1:
                return 'first';
                break;
            case 2:
                return 'second';
                break;
            case 3:
                return 'third';
                break;
            case 4:
                return 'forth';
                break;
            case 5:
                return 'fifth';
                break;
            case 6:
                return 'sixth';
                break;
            case 7:
                return 'seventh';
                break;
            case 8:
                return 'eighth';
                break;
            case 9:
                return 'ninth';
                break;
            case 10:
                return 'tenth';
                break;
            default:
                return 'too-high';
                break;
        }
    }
}

if (!function_exists('current_tree')) {
    function current_tree($items)
    {
        $url = Request::url();
        foreach ($items as $item) {
            if ($item->url() == $url) {
                return $item;
            }
        }
    }
}

/*
 * Return a multidimensional array from a unidimensional array
 */
if (!function_exists('array_map_assoc')) {
    function array_map_assoc($array, $first = null)
    {
        $associated = [];

        if ($first) {
            $associated[null] = $first;
        }

        foreach ($array as $value) {
            $associated[$value] = $value;
        }

        return $associated;
    }
}

if (!function_exists('piped_options')) {
    function piped_options($string, $headers = null)
    {
        $options = [];
        $rows    = explode("\r\n", $string);

        foreach ($rows as $key => $row) {
            if (empty($row)) {
                continue;
            }

            $columns = explode('|', $row);
            foreach ($columns as $pos => $column) {
                if (!empty($headers)) {
                    $options[$key][$headers[$pos]] = $column;
                } else {
                    $options[$key][] = $column;
                }
            }
        }

        return $options;
    }
}

if (!function_exists('get_value')) {
    function get_value($array, $key, $empty = null)
    {
        if (array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $empty;
    }
}

if (!function_exists('get_property')) {
    function get_property($object, $property, $empty = null)
    {
        if (!empty($object->{$property})) {
            return $object->{$property};
        }

        return $empty;
    }
}

if (!function_exists('heading_class')) {
    function heading_class($text)
    {
        $count = strlen($text);
        if ($count > 60) {
            return 'small';
        } elseif ($count > 20) {
            return 'medium';
        }

        return 'large';
    }
}
