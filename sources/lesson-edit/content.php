<?php

$book = '';
$posted_books = $db->get(T_BOOK);
if(!empty($posted_books)){
    foreach ($posted_books as $key => $value) {
         $book .= '<option value="' . $value->id . '" >' . $value->book_title . '</option>';
    }
} else {
    $book .= '<option disabled > No books Added</option>';
}

 $books = '';
$posted_book = $db->get(T_BOOK);
if(!empty($posted_book)){
    foreach ($posted_book as $key => $bookv) {
         $books .= '<li><a href={{LINK lesson-edit}}/'.$bookv->uniqid.'>' . $bookv->book_title . '</a></li>';
    }
} else {
    $books = 'No books added';
} 

$bookdata_html = '';

if (isset($_GET['page']) && !empty($_GET['page'])){

	$bookdataa = $db->where('uniqid', Secure($_GET['page']))->get(T_BOOK,1);

    if(!empty($bookdataa )){
	   foreach ($bookdataa as $key => $bookdata) {
	   	    $kd->book_id = $bookdata->id;
	      	$bookdata_html .= LoadPage('courses/book_edit_page',array(
				'BOOK_TITLE' =>$bookdata->book_title,
				'BOOK_DESCRIPTION' => html_entity_decode($bookdata->book_description),
				'BOOK_ID' => $bookdata->id,
				'BOOK_UNIQID' => $bookdata->uniqid,
				'BOOK_NUMBER' => $bookdata->book_number,
				'BOOK_COVER' => GetMedia($bookdata->book_cover),
				'BOOK_COVER_PATH' => $bookdata->book_cover,
				'BOOK_LANGUAGE' => $bookdata->language
			));
	   }

	   if(!empty($kd->book_id )){
	    $book_lessons = $db->where('book_id', $kd->book_id)->get(T_LESSONS);
	    $books_html = '';
			foreach ($book_lessons as $key => $book_lesson) {
			  $books_html .= '<li><a href={{LINK lesson-edit}}/'.$_GET['page'].'/'.$book_lesson->lesson_uniqid.'>' . $book_lesson->lesson_title . '</a></li>';
			}

			if(isset($_GET['lesson']) && !empty($_GET['lesson'])){
				$less_unid = Secure($_GET['lesson']);
				$lessondataa = $db->where('lesson_uniqid', $less_unid)->get(T_LESSONS,1);
                $b_html = '';
				if(!empty($lessondataa)){

					 foreach ($lessondataa as $key => $bkdat) {
					   	   
					      	$b_html .= LoadPage('courses/lessons_edit_page',array(
								'LESSON_TITLE' =>$bkdat->lesson_title,
								'LESSON_BODY' => html_entity_decode($bkdat->lesson_body),
								'LESSON_ID' => $bkdat->id,
								'LESSON_UNIQID' => $bkdat->lesson_uniqid,
								'LESSON_NAME' => $bkdat->lesson_name,
								'LESSON_NUMBER' => $bkdat->lesson_number,
								'BOOK_ID'  => $bkdat->book_id,
								'LESSON_MEDIA' => GetMedia($bkdat->lesson_media),
								'LESSON_MEDIA_PATH' => $bkdat->lesson_media,
								'LESSON_LANGUAGE' => $bkdat->language,
								'BOOK_ARRAY' =>  $book
							));
					   }

				}

			};
       }
	}
}




$kd->page_url_   = $kd->config->site_url.'/edit-lessons';
$kd->title       = __('lessons_edit') . ' | ' . $kd->config->title;
$kd->page        = "lessons_edit";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;
$kd->content     = LoadPage('courses/lesson_edit', array(
    'BOOKS'    =>  $books,
    'LESSON_LIST' => (!empty($books_html)) ? $books_html : '',
    'BOOK_PAGE' => (!empty($bookdata_html)) ? $bookdata_html : '',
    'LESSON_PAGE' => (!empty($b_html)) ? $b_html : '',


));