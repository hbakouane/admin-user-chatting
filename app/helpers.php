<?php

if (!function_exists('isPhoto')) 
{
    function isPhoto($path) 
    {
        // Get the file extension from the path
        $exploded = explode('.', $path);
        $ext = strtolower(end($exploded));
        // Define the photos extensions
        $photoExtensions = ['png', 'jpg', 'jpeg', 'gif', 'jfif', 'tif', 'webp'];
        // Check if this extension belongs to the extensions we defined
        if (in_array($ext, $photoExtensions)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isVideo')) 
{
    function isVideo($path) 
    {
        // Get the file extension from the path
        $exploded = explode('.', $path);
        $ext = end($exploded);
        // Define the videos extensions
        $videoExtensions = ['mov', 'mp4', 'avi', 'wmf', 'flv', 'webm'];
        // Check if this extension belongs to the extensions we defined
        if (in_array($ext, $videoExtensions)) {
            return true;
        }
        return false;
    }
}