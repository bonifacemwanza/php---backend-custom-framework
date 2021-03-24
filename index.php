<?php
include('./assets/init.php');
$page = 'home';
if (isset($_GET['link1'])) {
    $page = $_GET['link1'];
}






if (file_exists("./sources/$page/content.php")) {
    include("./sources/$page/content.php");
}

if (empty($kd->content)) {
    include("./sources/404/content.php");
}

$side_header = 'not-logged';

if (IS_LOGGED == true) {
    $side_header = 'loggedin';
}

$footerall            = '';
$footerlogin            = '';
$og_meta           = '';

$langs__footer = $langs;
$langs_right    = '';
$langs_left    = '';
$number = 0;
foreach ($langs__footer as $key => $language) {
    $lang_explode = explode('.', $language);
    $language     = $lang_explode[0];
    $language_    = ucfirst($language);
    if ($number % 2 == 0) {
        $langs_right .= LoadPage('footer/languages', array('LANGID' => $language, 'LANGNAME' => $language_));
    }else{
        $langs_left .= LoadPage('footer/languages', array('LANGID' => $language, 'LANGNAME' => $language_));
    }
    $number++;
}


$footerall = LoadPage('footer/content');

 $books_a = '';
$posted_book = $db->where('language', $_SESSION['lang'])->get(T_BOOK);
if(!empty($posted_book)){
    foreach ($posted_book as $key => $valu) {
         $books_a .= '<li><a href={{LINK lesson-single}}/'.$valu->uniqid.'>' . $valu->book_title . '</a></li>';
    }
} else {
    $books_a = 'No books added';
}  



$final_content = LoadPage('container', array(
    'CONTAINER_TITLE' => $kd->title,
    'CONTAINER_DESC' => $kd->description,
   // 'CONTAINER_KEYWORDS' => $kd->keyword,
    'CONTAINER_CONTENT' => $kd->content,
    'IS_LOGGED' => (IS_LOGGED == true) ? 'data-logged="true"' : '',
    'MAIN_URL' => $kd->actual_link,
    'HEADER_LAYOUT' => LoadPage('header/content', array(
        'BOOKS' => $books_a,
        
    )),
    'FOOTER_LAYOUT_ALL' => $footerall,
    'FOOTER_LAYOUT_LOGIN' => $footerlogin,
   // 'OG_METATAGS' => $og_meta,
    'EXTRA_JS' => LoadPage('extra-js/content'),
  
    'ACTIVE_LANG' => $kd->language,
    'ACTIVE_LANGNAME' => ucfirst($kd->language)
));



echo $final_content;



$db->disconnect();
unset($kd);