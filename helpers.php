<?php

if (!function_exists('form')) {

    /**
     * @return \Illuminate\Foundation\Application
     */
    function form()
    {
        return app('forms');
    }
}