<?php

if (!function_exists('slug')) {
    function slug($string)
    {
        return str_replace(' ', '_', 
               preg_replace('/[^A-Za-z0-9\-]/', '', 
               strtolower(
                   trim($string)
               ))
        );
    }
} 