<?php


$lesson_page = '';
if (isset($_GET['page'])) {
    $lesson_page = $_GET['page'];
}

 $kd->book_id  = GetLessonIdWithUniqid($lesson_page);



 $lesson_data = $db->where('language', $_SESSION['lang'])->where('id', $kd->book_id)->get(T_LESSONS,1);
if(!empty($lesson_data)){
	foreach ($lesson_data as $key => $lk) {
		$kd->lk = $lk;
	}
}
$lesson_list = $db->where('language', $_SESSION['lang'])->where('book_id', $kd->lk->book_id)->get(T_LESSONS);
$lesson_list_total = $db->where('language', $_SESSION['lang'])->where('book_id', $kd->lk->book_id)->getOne(T_LESSONS,array('count(*) as total'));
$qt_total = $lesson_list_total->total;

$list_li = '';
if(!empty($lesson_list)){
	foreach ($lesson_list as $key => $lesson_l) {

		$list_li .= '<li><a href="{{LINK lesson-view/}}'.$lesson_l->lesson_uniqid.'"> &nbsp; '.$lesson_l->lesson_title.'</a></li>';
	}
}

$media = '';
if (!empty($kd->lk->lesson_media)){
	$media .= LoadPage('courses/audio', array('LESSON_MEDI' => (!empty($kd->lk->lesson_media)) ? GetMedia($kd->lk->lesson_media) : ''));
 }

 $getQuiz = '';
 $choice_html = '';


 $getQuiz = $db->rawQuery('SELECT * FROM `'.T_QUIZ.'` WHERE lesson_number = '.$kd->lk->lesson_number.' AND book_number = '.$kd->lk->book_id.' AND language = "'.$_SESSION['lang'].'" AND quiz_number = "1"');
 $getChoices = $db->rawQuery('SELECT * FROM `'.T_CHOICE.'` WHERE lesson_number = '.$kd->lk->lesson_number.' AND book_number = '.$kd->lk->book_id.' AND language = "'.$_SESSION['lang'].'" AND quiz_number = "1"');

if(!empty($getChoices)){
     foreach ($getChoices as $key => $ck) {

             $choice_html .= '<div class="radio">
        <label>
        <input name="choice" id="optionsRadios2" value="'.$ck->id.'" type="radio">'.$ck->choice.'
        </label>
        </div>'; 
    }
}

$get_question = $db->where('lesson_number',$kd->lk->lesson_number)->where('book_number', $kd->lk->book_id)->where('language',$_SESSION['lang'])->getOne(T_QUIZ,array('count(*) as total'));
$q_total = $get_question->total;
if(!empty($getQuiz)){
     foreach ($getQuiz as $key => $gq) {
         $kd->gq = $gq;

     }
}
if($kd->lk->lesson_number <= $qt_total){
    $nxt_q = $kd->lk->lesson_number + 1;
    $next_lesson = $db->where('language', $_SESSION['lang'])->where('book_id', $kd->lk->book_id)->where('lesson_number',$nxt_q)->getOne(T_LESSONS);
if (!empty($next_lesson)) {
   $link = $next_lesson->lesson_uniqid;
}

}
if($kd->lk->lesson_number > 1){
    $prev_q = $kd->lk->lesson_number - 1;
    $prev_lesson = $db->where('language', $_SESSION['lang'])->where('book_id', $kd->lk->book_id)->where('lesson_number',$prev_q)->getOne(T_LESSONS);
    if (!empty($prev_lesson)) {
   $link2 = $prev_lesson->lesson_uniqid;
}
} else {
    $link2 = $kd->lk->lesson_uniqid;
}



$progress_percentage = '';
    $progress_percentage = $kd->gq->quiz_number / $q_total * 100;


$kd->page_url_   = $kd->config->site_url.'/lesson_view';
$kd->title       = __('lesson_view') . ' | ' . $kd->config->title;
$kd->page        = "lesson_view";
$kd->description = $kd->config->description;
$kd->keyword     = @$kd->config->keyword;
$kd->content     = LoadPage('courses/lesson_view', array(
    'NEXT_LESSON' => $link,
    'PREV_LESSON' => $link2,
    'LESSON_TITLE' =>$kd->lk->lesson_title,
    'LESSON_NUMBER' =>$kd->lk->lesson_number,
    'LESSON_UNIQID' =>$kd->lk->lesson_uniqid,
    'LESSON_MEDIA'  => (!empty($media)) ? $media : '',
    'LESSON_NAME'  =>$kd->lk->lesson_name,
    'LESSON_BODY'  =>html_entity_decode($kd->lk->lesson_body),
    'LESSON_LIST'  => $list_li,
    'PROGRESS' => $progress_percentage,
    'LESSON_TITLE' =>$kd->lk->lesson_title,
     'LESSON_NUMBER' =>$kd->lk->lesson_number,
     'BOOK_NUMBER' => $kd->gq->book_number,
     'QUIZ_NUMBER' => $kd->gq->quiz_number,
     'QUIZ_QUESTION' =>htmlspecialchars_decode($kd->gq->question),
     'CHOICES' => $choice_html 
                
));