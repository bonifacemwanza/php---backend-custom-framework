<?php
$kd->mode = "all";
$username = null;
if( $_GET['id'] == 'null' && is_numeric($_GET['postid']) ){
    $question = $db->rawQuery('SELECT `username` FROM `'.T_USERS.'` INNER JOIN `'.T_QUESTIONS.'` ON (`'.T_USERS.'`.id = `'.T_QUESTIONS.'`.user_id) WHERE `'.T_QUESTIONS.'`.id = '. Secure($_GET['postid']));
    if($question[0]){
        $username = $question[0]->username;
        $kd->mode = "single";
    }
}

if($kd->mode == "all"){

}

if( isset($_GET['id']) && $_GET['id'] !== 'null'){
    $username = Secure($_GET['id']);
}

if( $username == null ){
    header("Location: " . UrlLink(''));
    exit();
}

$page = 'content';
$kd->page_url_ = $kd->config->site_url.'/@'.$username;
if( isset($_GET['page']) && !empty($_GET['page']) ){
    $page = Secure($_GET['page']);
    $kd->page_url_ = $kd->config->site_url.'/@'.$username.'/'.$page;
}
if($kd->mode == "single"){
    $kd->page_url_ = $kd->config->site_url.'/post/'.Secure($_GET['postid']);
}

$user_id  = $db->where('username', $username)->getOne(T_USERS);
if (empty($user_id)) {
    header("Location: " . UrlLink(''));
    exit();
}

$user_data   = UserData($user_id, [
    'data' => true
]);
$kd->isowner = false;

if (IS_LOGGED == true) {
    if ($user_data->id == $user->id) {
        $kd->isowner = true;
    }
}

if (IS_LOGGED == true) {
    if ($kd->config->profile_visit_notification == 'on' && $kd->isowner === false && $user_data->notification_on_visit_profile === 1) {
        $db->where('recipient_id', $user_data->id)->where('type', 'visit_profile')->where('notifier_id', $user->id)->delete(T_NOTIFICATIONS);
        $notif_data = array(
            'notifier_id' => $user->id,
            'recipient_id' => $user_data->id,
            'type' => 'visit_profile',
            'url' => ('@' . $user->username),
            'time' => time()
        );
        Notify($notif_data);
    }
}

$kd->profile_user  = $user_data;
$kd->page          = 'timeline';
$kd->title         = $user_data->name . ' | ' . $kd->config->title;
$kd->description   = $kd->config->description;
$kd->keyword       = $kd->config->keyword;

$langs__footer = $langs;
$langs_right    = '';
$langs_left    = '';
$number = 0;
foreach ($langs__footer as $key => $language) {
    $lang_explode = explode('.', $language);
    $language     = $lang_explode[0];
    $language_    = ucfirst($language);
    if ($number % 2 == 0) {
        $langs_right .= LoadPage('footer/languages', ['LANGID' => $language, 'LANGNAME' => $language_]);
    }else{
        $langs_left .= LoadPage('footer/languages', ['LANGID' => $language, 'LANGNAME' => $language_]);
    }
    $number++;
}

$html_followers = '';
$followers = array();
$followers_get = $db->where('user_id', $user_id->id)->orderby('follower_id', 'DESC')->get(T_FOLLOWERS, 20);
if (!empty($followers_get)) {
    $html_followers = GetFollowersHtml($followers_get);
}
$kd->followers_count= (int)count($followers_get);

$html_following = '';
$following = array();
$following_get = $db->where('follower_id', $user_id->id)->orderby('user_id', 'DESC')->get(T_FOLLOWERS, 20);
if (!empty($following_get)) {
    $html_following = GetFollowingsHtml($following_get);
}
$kd->following_count= (int)count($following_get);

$questions_data = array();
if($kd->mode == 'all') {
    $questions_data = GetQuestions(['page' => 'timeline','user_data' => $user_data->id]);
}else if($kd->mode == 'single'){
    $questions_data = $db->where('id', Secure($_GET['postid']))->get(T_QUESTIONS, 1);
}
if($kd->mode == "single"){
    $page = 'post';
}

$kd->page = $page;
$kd->content       = LoadPage('timeline/'.$page, [
    'USER_DATA'         => $user_data,
    'COVER_AVATAR'      => LoadPage('timeline/partials/cover_avatar', [
        'USER_DATA' => $user_data,
        'FOLLOW_BUTTON' => GetFollowButton($user_data->id),
        'FOLLOWERS' => number_format($db->where('user_id', $user_data->id)->getValue(T_FOLLOWERS, "count(*)")),
        'FOLLOWING' => number_format($db->where('follower_id', $user_data->id)->getValue(T_FOLLOWERS, "count(*)")),
        'QUESTIONS' => number_format($db->where('user_id', $user_data->id)->getValue(T_QUESTIONS, "count(*)"))
    ]),
    'FOLLOWERS_BOX'     => LoadPage('timeline/partials/followers_box', [
        'USER_DATA' => $user_data,
        'FOLLOWERS_DATA' => $html_followers,
    ]),
    'TRENDING_HASHTAG'  => LoadPage('timeline/partials/trending', []),
    'PEOPLE_SUGGESTION' => LoadPage('timeline/partials/people_suggestion', []),
    'FOLLOWING_BOX'     => LoadPage('timeline/partials/following_box', [
        'USER_DATA' => $user_data,
        'FOLLOWING_DATA' => $html_following,
    ]),
    'PUBLISHER_BOX'     => LoadPage('timeline/partials/publisher_box', ['USER_DATA' => $user_data]),
    'POST_AREA'         => LoadPage('timeline/partials/post_area', [
        'USER_DATA' => $user_data,
        'QUESTIONS_DATA' => $questions_data,
        'PROMOTED_DATA' => GetQuestions(['page' => 'promoted'])
    ]),
//    'STATS'             => LoadPage('timeline/partials/stats', [
//        'USER_DATA' => $user_data,
//        'FOLLOWERS' => number_format($db->where('user_id', $user_data->id)->getValue(T_FOLLOWERS, "count(*)")),
//        'FOLLOWING' => number_format($db->where('follower_id', $user_data->id)->getValue(T_FOLLOWERS, "count(*)")),
//        'QUESTIONS' => number_format($db->where('user_id', $user_data->id)->getValue(T_QUESTIONS, "count(*)"))
//    ]),
    'ABOUT_ME'          => LoadPage('timeline/partials/about_me', ['USER_DATA' => $user_data]),
    'SIDEBAR_FOOTER'    => LoadPage('timeline/partials/sidebar_footer', [
        'ACTIVE_LANG'       => $kd->language,
        'ACTIVE_LANGNAME'   => ucfirst($kd->language),
        'LANGS_RIGHT'       => $langs_right,
        'LANGS_LEFT'        => $langs_left,
    ]),
    'SIDE_AD' => GetAd('side_bar'),
    'FOOTER_AD' => ($kd->page != 'login') ? GetAd('footer') : '',
    'HEADER_AD' => GetAd('header')
]);