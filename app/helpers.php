<?php

if (!function_exists('is_external_link')) {
    /**
     * Recognizes is the external link
     *
     * @param string url
     * @return bool
     */
    function is_external_link($url)
    {
        return strpos($url, config('app.url')) === FALSE;
    }
}

// ...
