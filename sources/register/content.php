<?php
if (IS_LOGGED == true ) {
    header("Location: " . UrlLink(''));
    exit();
}
$kd->page          = 'register';
$kd->title         = __('register') . ' | ' . $kd->config->title;
$kd->description   = $kd->config->description;
$kd->keyword       = $kd->config->keyword;
$kd->page_url_     = $kd->config->site_url . '/register';
$kd->content     = LoadPage('auth/content', array(

));