<?php
$kd->page_url_   = $kd->config->site_url.'/about';
$kd->title       = __('about') . ' | ' . $kd->config->title;
$kd->page        = "about";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;
$kd->content     = LoadPage('about/content');