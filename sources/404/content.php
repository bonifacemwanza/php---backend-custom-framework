<?php
header("HTTP/1.0 404 Not Found");
$kd->page_url_ = $kd->config->site_url.'/404';
$kd->page = '404';
$kd->title = '404 | ' . $kd->config->title;
$kd->description = $kd->config->description;
$kd->keyword = $kd->config->keyword;
$kd->content = LoadPage('404/content');