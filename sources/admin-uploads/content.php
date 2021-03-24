<?php
if (IS_LOGGED == false) {
    header("Location: " . UrlLink('login'));
    exit();
}





$kd->page_url_     = $kd->config->site_url . '/admin-uploads';

$kd->admin_page = '';

$kd->admin_page = 'book';

 $pages_array = [
    'lesson',
    'quiz',
    'book'

];
 if (!empty($_GET['page'])) {
    if (in_array($_GET['page'], $pages_array)) {
        $kd->admin_page = $_GET['page'];

    $kd->page_url_   = $kd->config->site_url.'/admin-uploads/'.$kd->admin_page;
     }
    }


 $kd->page          = 'admin-uploads';
$kd->title         = __('admin_uploads') . ' | ' . $kd->config->title;
$kd->description   = $kd->config->description;
$kd->keyword       = $kd->config->keyword;

$books = '';
$posted_books = $db->get(T_BOOK);
if(!empty($posted_books)){
    foreach ($posted_books as $key => $value1) {
         $books .= '<option id="'.$value1->id.'" value="' . $value1->id . '" >' . $value1->book_title . '</option>';
    }
} else {
    $books .= '<option disabled > No books Added</option>';
}
$lesson = '';
$posted_lessons = $db->get(T_LESSONS);
if(!empty($posted_lessons)){
    foreach ($posted_lessons as $key => $value) {
         $lesson .= '<option id="'.$value->book_id.'" value="' . $value->lesson_number . '" >' . $value->lesson_title . '</option>';
    }
}

$kd->content     = LoadPage('admin-upload/admin', array(
     'USER_DATA' => $user,
    'ADMIN_PAGE'       => LoadPage("admin-upload/$kd->admin_page", [  'USER_DATA' => $user, 'BOOK_ARRAY' => $books, 'LESSON_ARRAY' => $lesson,])
));


