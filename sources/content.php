<?php
$kd->page_url_   = $kd->config->site_url.'/dashboard';
$kd->title       = __('dashboard') . ' | ' . $kd->config->title;
$kd->page        = "dashboard";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;
$kd->content     = LoadPage('dashboard/content');