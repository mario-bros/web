<?php

/**
 * Charity resize function 
 *
 * @package     charity
 * @version     v.1.0
 */

function charity_resize($url, $width, $height) {
    
    if(!isset($url) || $url == "") return $url;
    
    $siteURL = site_url("/");
    
    
    $upload_dir = wp_upload_dir();
    $upload_dir['baseurl'];
    $start = strpos($url, "/uploads/")+strlen("/uploads/");
    $uploadImage= $upload_dir['baseurl']."/".substr($url, $start, strlen($url)-1);
    
    $full = str_replace($siteURL, ABSPATH, $uploadImage);
    
    /*$siteURL = site_url("/");
    
    $full = str_replace($siteURL, ABSPATH, $url);*/
    
    $thumbURL = "";
    $pathinfo = pathinfo($full);
    
    if (isset($pathinfo['dirname'])) {
        $imageDir = $pathinfo['dirname'];
        $filename = $pathinfo['filename'];
        $extension = $pathinfo['extension'];

        $thumbPath = $imageDir . "/" . $filename . "-{$width}X{$height}." . $extension;

        $thumb = wp_get_image_editor($full);

        if (!is_wp_error($thumb)) {
            $thumb->resize($width, $height, true);
            $thumb->save($thumbPath);
        }

        $thumbURL = str_replace(ABSPATH, $siteURL, $thumbPath);
    }
    return ($thumbURL !="")? $thumbURL : $url;
}
