<?php

if (IS_LOGGED == false) {
    header("Location: " . UrlLink('login'));
    exit();
}
$user_id                = $kd->user->id;
$kd->is_admin          = IsAdmin();
$final_page = '';
if (isset($_GET['user']) && !empty($_GET['user']) && ($kd->is_admin === true)) {
    if (empty($db->where('username', Secure($_GET['user']))->getValue(T_USERS, 'count(*)'))) {
        header("Location: " . UrlLink(''));
        exit();
    }
    $user_id               = $db->where('username', Secure($_GET['user']))->getValue(T_USERS, 'id');
    $kd->is_settings_admin = true;
}
$kd->settings     = UserData($user_id);

$kd->isowner = false;

if (IS_LOGGED == true) {
    if ($kd->settings->id == $user->id) {
        $kd->isowner = true;
    }
}
$countries = '';
foreach ($countries_name as $key => $value) {
    $selected = ($key == $kd->settings->country_id) ? 'selected' : '';
    $countries .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
}



$pages_array = [
	'dashboard',
    'profile_edit',
    'messages',
    'lessons',
    'complete_quiz',
    'reward_points',
    'admin',
   
  
];

$get_my_lesson = $db->where('user_id', $kd->user->id)->get(T_USER_LESSONS,5, array('lesson_id'));
$my_lesson_html = '';
if(!empty($get_my_lesson)){
    foreach ($get_my_lesson as $valueQ) {
        $value = GetBookById($valueQ->lesson_id);
        
       
     $my_lesson_html .= LoadPage('dashboard/pages/lesson_part', array(
        'BOOK_TITLE' => $value->book_title,
        'BOOK_DESCRIPTION' => htmlspecialchars_decode($value->book_description),
        'BOOK_COVER' => GetMedia($value->book_cover),
        'BOOK_UNIQID' =>$value->uniqid ));
                      
            
                
                 
     }
    
} else {

    $my_lesson_html = __('you_have_not_taken_any_lessons');

}




$kd->page_url_   = $kd->config->site_url.'/dashboard';

$kd->dashboard_page = 'dashboard';
$kd->admin_page = 'book';

if (!empty($_GET['page'])) {
    if (in_array($_GET['page'], $pages_array)) {
        $kd->dashboard_page = $_GET['page'];
        $kd->page_url_ = $kd->config->site_url.'/dashboard/'.$kd->dashboard_page.'/'.$kd->settings->username;
    }
} 

$final_page =  LoadPage("dashboard/pages/$kd->dashboard_page", [
        'USER_DATA' => $user,
         'TAKEN_LESSONS' => $my_lesson_html,
       
        
]);



$kd->page        = 'dashboard';
$kd->title       = __('dashboard') . ' | ' . $kd->config->title;
$kd->description = $kd->config->description;
$kd->keyword     = $kd->config->keyword;


$kd->content     = LoadPage('dashboard/content', [
        'USER_DATA' => $user,
        'PROFILE_PAGE' => $final_page,
       
    
]);