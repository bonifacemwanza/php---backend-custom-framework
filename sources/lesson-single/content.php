<?php

$lesson_page = '';
if (isset($_GET['page'])) {
    $lesson_page = $_GET['page'];
}

 $kd->book_id  = GetBookIdWithUniqid($lesson_page);
$booK_query = $db->where('id', $kd->book_id)->get(T_BOOK,1);
if(!empty($booK_query)){
	foreach ($booK_query as $key => $bk) {
		$kd->bkk = $bk;
	}
}

$lesson_query = $db->where('book_id', $kd->book_id)->get(T_LESSONS);
$lesson_list_html = '';
if(!empty($lesson_query)){
	foreach ($lesson_query as $key => $lesson) {
		$lesson_list_html .= LoadPage('courses/lesson_list', array(
          'LESSON_TITLE' => $lesson->lesson_title,
          'LESSON_UNIQID' => $lesson->lesson_uniqid
		));
	}
}
$kd->page_url_   = $kd->config->site_url.'/lesson/'.$lesson_page;
$kd->title       = __('lesson') . ' | ' . $kd->config->title;
$kd->page        = "lesson";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;


$books = '';
$posted_books = $db->where('language', $_SESSION['lang'])->where('book_number', $kd->bkk->book_number, '<>')->get(T_BOOK);
if(!empty($posted_books)){
    foreach ($posted_books as $key => $value) {
         $books .= LoadPage('courses/featured_lesson', array(
         	'BOOK_TITLE' => $value->book_title,
         	'BOOK_DESCRIPTION' => htmlspecialchars_decode($value->book_description),
         	'BOOK_COVER' => GetMedia($value->book_cover),
         	'BOOK_UNIQID' =>$value->uniqid,
          
         ));
    }
}



$kd->content     = LoadPage('courses/single_lesson', array(
    'BOOK_ID' => $kd->bkk->book_number,
    'USER_ID' => $kd->user->id,
	'BOOK_TITLE' => $kd->bkk->book_title,
	'BOOK_COVER' => GetMedia($kd->bkk->book_cover),
	'BOOK_DESCRIPTION' => htmlspecialchars_decode($kd->bkk->book_description),
    'LESSON_LIST' => $lesson_list_html,
    'FEATURED_LESSONS' => $books

));