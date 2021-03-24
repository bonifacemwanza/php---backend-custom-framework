<?php






$kd->page_url_   = $kd->config->site_url.'/contact-us';
$kd->title       = __('contact_us') . ' | ' . $kd->config->title;
$kd->page        = "contact_us";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;
$kd->content     = LoadPage('contact/content');