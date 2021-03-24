<?php
$kd->page_url_   = $kd->config->site_url.'/lesson';
$kd->title       = __('lesson') . ' | ' . $kd->config->title;
$kd->page        = "lesson";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;

$books = '';
$posted_books = $db->get(T_BOOK);
if(!empty($posted_books)){
    foreach ($posted_books as $key => $value) {
         $books .= LoadPage('courses/lesson_grid', array(
         	'BOOK_TITLE' => $value->book_title,
         	'BOOK_DESCRIPTION' => htmlspecialchars_decode($value->book_description),
         	'BOOK_COVER' => GetMedia($value->book_cover),
         	'BOOK_UNIQID' =>$value->uniqid,
          
         ));
    }
} else {
    $books .= '<h1> No books Added</h1>';
}
$kd->content     = LoadPage('courses/content', array(
   'LESSON_GRID' => $books 
));