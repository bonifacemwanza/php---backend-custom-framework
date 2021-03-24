<?php 

if (empty($_REQUEST['user_id']) || !IS_LOGGED) {
    exit("Undefined Alien ಠ益ಠ");
}
$is_owner = false;
if ($_REQUEST['user_id'] == $user->id || IsAdmin()) {
    $is_owner = true;
}
$user_id = Secure($_REQUEST['user_id']);

if($first == 'add_book'){
	$book_cover = '';
	$request   = array();
    $request[] = (empty($_POST['language']) || empty($_POST['book_number']));
    $request[] = ( empty($_POST['book_title']));
    $request[] = (empty($_POST['book_description']));

    if (in_array(true, $request)) {
        $error = $error_icon . __('please_check_details');
    } 

	if (!empty($_FILES['book_cover']['tmp_name'])) {
	   // if ($_FILES['video']['size'] > $kd->config->max_image_upload_size) {
    //     $max  = size_format($kd->config->max_image_upload_size);
    //     $data = array('status' => 401,'message' => ($lang->file_is_too_big .": $max") );
    //     echo json_encode($data);
    //     exit();
    // } else {
    //     $data = 
    //        array ('status' => 400, 'error' => 'empty file name'); 
        
	    $file_info   = array(
	        'file' => $_FILES['book_cover']['tmp_name'],
	        'size' => $_FILES['book_cover']['size'],
	        'name' => $_FILES['book_cover']['name'],
	        'type' => $_FILES['book_cover']['type'],
	        'crop' => array(
	            'width' => 600,
	            'height' => 600
	        )
	    );
	    $file_upload = ShareFile($file_info);
	     if (!empty($file_upload['filename'])) {
            $book_cover = Secure($file_upload['filename']);
	     }
	 }
    if (empty($error)) {

        $book_data           = array(
            'language' => Secure($_POST['language']),
            'book_number' => Secure($_POST['book_number']),
            'book_title' => Secure($_POST['book_title']),
            'book_description' => Secure($_POST['book_description']),
           
            'book_cover' => $book_cover,
            'book_posted_time' => time(),
            'uniqid'  => random_str(8, 'abcdefghijklmnopqrstuvwxyz')
        );

        $book_send                       = $db->insert(T_BOOK, $book_data);
        if ($book_send) {
             $data                          = array(
            'message' => $success_icon . __('book_added'),
            'status' => 200,
            'url' => UrlLink('admin-uploads/book')
        );
        } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }
    
    } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }

}
if($first == 'add_lesson'){
	$lesson_media = '';
	$request   = array();
    $request[] = (empty($_POST['language']) || empty($_POST['lesson_number']));
    $request[] = ( empty($_POST['book_id']) || empty($_POST['lesson_title']));
    $request[] = (empty($_POST['lesson_name']) || empty($_POST['lesson_body']));

    if (in_array(true, $request)) {
        $error = $error_icon . __('please_check_details');
    } 

	if (!empty($_FILES['lesson_media']['tmp_name'])) {
	   // if ($_FILES['video']['size'] > $kd->config->max_image_upload_size) {
    //     $max  = size_format($kd->config->max_image_upload_size);
    //     $data = array('status' => 401,'message' => ($lang->file_is_too_big .": $max") );
    //     echo json_encode($data);
    //     exit();
    // } else {
    //     $data = 
    //        array ('status' => 400, 'error' => 'empty file name'); 
        
	    $file_info   = array(
	        'file' => $_FILES['lesson_media']['tmp_name'],
	        'size' => $_FILES['lesson_media']['size'],
	        'name' => $_FILES['lesson_media']['name'],
	        'type' => $_FILES['lesson_media']['type'],
	        'crop' => array(
	            'width' => 600,
	            'height' => 600
	        )
	    );
	    $file_upload = ShareFile($file_info);
	     if (!empty($file_upload['filename'])) {
            $lesson_media = Secure($file_upload['filename']);
	     }
	 }
    if (empty($error)) {

        $lesson_data           = array(
            'language' => Secure($_POST['language']),
            'lesson_number' => Secure($_POST['lesson_number']),
            'book_id' => Secure($_POST['book_id']),
            'lesson_title' => Secure($_POST['lesson_title']),
            'lesson_name' => Secure($_POST['lesson_name']),
             'lesson_body' => Secure($_POST['lesson_body']),
            'lesson_media' => $lesson_media,
            'lesson_posted_time' => time(),
            'lesson_uniqid'  => random_str(8, 'abcdefghijklmnopqrstuvwxyz')
        );

        $book_send                       = $db->insert(T_LESSONS, $lesson_data);
        if ($book_send) {
             $data                          = array(
            'message' => $success_icon . __('lesson_added'),
            'status' => 200,
            'url' => UrlLink('admin-uploads/lesson')
        );
        } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }
    
    } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }

}

if($first == 'edit_book'){
    $book_cover = '';
    $request   = array();
    $request[] = (empty($_POST['language']) || empty($_POST['book_number']));
    $request[] = ( empty($_POST['book_title']));
    $request[] = (empty($_POST['book_description']));

    if (in_array(true, $request)) {
        $error = $error_icon . __('please_check_details');
    } 

    if (!empty($_FILES['book_cover']['tmp_name'])) {
      
        
        $file_info   = array(
            'file' => $_FILES['book_cover']['tmp_name'],
            'size' => $_FILES['book_cover']['size'],
            'name' => $_FILES['book_cover']['name'],
            'type' => $_FILES['book_cover']['type'],
            'crop' => array(
                'width' => 600,
                'height' => 600
            )
        );
        $file_upload = ShareFile($file_info);
         if (!empty($file_upload['filename'])) {
            $book_cover = Secure($file_upload['filename']);
         }
     }
    if (empty($error)) {

        $book_data           = array(
            'language' => Secure($_POST['language']),
            'book_number' => Secure($_POST['book_number']),
            'book_title' => Secure($_POST['book_title']),
            'book_description' => Secure($_POST['book_description']),  
            'book_cover' => (!empty($book_cover)) ? $book_cover : Secure($_POST['book_cover_path']),
            'book_posted_time' => time(),
            'uniqid'  => (!empty($_POST['book_uniqid'])) ? Secure($_POST['book_uniqid']) : random_str(8, 'abcdefghijklmnopqrstuvwxyz')
        );

        $book_send                       = $db->where('id', Secure($_POST['book_id']))->update(T_BOOK, $book_data);
        if ($book_send) {
             $data                          = array(
            'message' => $success_icon . __('book_edited'),
            'status' => 200,
            'url' => UrlLink('lesson-edit/'.$_POST['book_uniqid'])
        );
        } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }
    
    } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }

}
if($first == 'edit_lesson'){
    $lesson_media = '';
    $request   = array();
    $request[] = (empty($_POST['language']) || empty($_POST['lesson_number']));
    $request[] = ( empty($_POST['book_id']) || empty($_POST['lesson_title']));
    $request[] = (empty($_POST['lesson_name']) || empty($_POST['lesson_body']));

    if (in_array(true, $request)) {
        $error = $error_icon . __('please_check_details');
    } 

    if (!empty($_FILES['lesson_media']['tmp_name'])) {

        
        $file_info   = array(
            'file' => $_FILES['lesson_media']['tmp_name'],
            'size' => $_FILES['lesson_media']['size'],
            'name' => $_FILES['lesson_media']['name'],
            'type' => $_FILES['lesson_media']['type'],
            'crop' => array(
                'width' => 600,
                'height' => 600
            )
        );
        $file_upload = ShareFile($file_info);
         if (!empty($file_upload['filename'])) {
            $lesson_media = Secure($file_upload['filename']);
         }
     }
    if (empty($error)) {

        $lesson_data           = array(
            'language' => Secure($_POST['language']),
            'lesson_number' => Secure($_POST['lesson_number']),
            'book_id' => Secure($_POST['book_id']),
            'lesson_title' => Secure($_POST['lesson_title']),
            'lesson_name' => Secure($_POST['lesson_name']),
             'lesson_body' => Secure($_POST['lesson_body']),
            'lesson_media' => (!empty($lesson_media)) ? $lesson_media : Secure($_POST['lesson_media_path']),
            'lesson_posted_time' => time(),
            'lesson_uniqid'  => (!empty($_POST['lesson_uniqid'])) ? Secure($_POST['lesson_uniqid']) : random_str(8, 'ab123cdefg23h_6ijklmn434opqrs43tuv5wxyz')
        );

        $book_send                       = $db->where('id',Secure($_POST['lesson_id']))->update(T_LESSONS, $lesson_data);
        if ($book_send) {
             $data                          = array(
            'message' => $success_icon . __('lesson_edited'),
            'status' => 200,
            'url' => UrlLink('lesson-edit')
        );
        } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }
    
    } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }

}

if ($first == 'delete_book') {
   
    if (!empty($_POST['id'])) {
      
      $id = '';

      $id = Secure($_POST['id']);

      $db->where('book_id', $id)->delete(T_LESSONS);
      $bookDeleted = $db->where('id', $id)->delete(T_BOOK);

        if ($bookDeleted) {
            $data = array(
                'status' => 200,
                'message' => $success_icon . __('book_deleted'),
                'url' => UrlLink('lesson-edit')
            );
        }
        
    }
}

if ($first == 'delete_lesson') {
   
    if (!empty($_POST['id'])) {
      
      $id = '';

      $id = Secure($_POST['id']);

      
      $lessonDeleted = $db->where('id', $id)->delete(T_LESSONS);

        if ($lessonDeleted) {
            $data = array(
                'status' => 200,
                'message' => $success_icon . __('lesson_deleted'),
                'url' => UrlLink('lesson-edit')
            );
        }
        
    }
}
if($first == 'add_quiz'){
    $book_cover = '';
    $request   = array();
    $request[] = (empty($_POST['language']) || empty($_POST['book_number']));
    $request[] = ( empty($_POST['lesson_number']) || empty($_POST['question']));
    $request[] = (empty($_POST['correct_choice']) || empty($_POST['question_number']));


    if (in_array(true, $request)) {
        $error = $error_icon . __('please_check_details');
    } 

    
    if (empty($error)) {
            $choices = array();
            $correct_choice = Secure($_POST['correct_choice']);
            $choices[1] = Secure($_POST['choice1']);
            $choices[2] = Secure($_POST['choice2']);
            $choices[3] = Secure($_POST['choice3']);
            $choices[4] = Secure($_POST['choice4']);
            $choices[5] = Secure($_POST['choice5']);

        $quiz_data           = array(
            'language' => Secure($_POST['language']),
            'book_number' => Secure($_POST['book_number']),
            'lesson_number' => Secure($_POST['lesson_number']),          
            'question' => Secure($_POST['question']),
            'uniqid'  => random_str(8, 'abcdefghijklmnopqrstuvwxyz'),
            'quiz_number' => Secure($_POST['question_number']),
            'description' => Secure($_POST['description'])
        );

         

        $bt_send                       = $db->insert(T_QUIZ, $quiz_data);
        if ($bt_send) {
            foreach($choices as $choice => $value){
                if($value != ''){
                   if($correct_choice == $choice){
                      $is_correct = 1;
                   } else {
                     $is_correct = 0;
                   }
                      $choice_data           = array(
                        'language' => Secure($_POST['language']),
                        'book_number' => Secure($_POST['book_number']),
                        'lesson_number' => Secure($_POST['lesson_number']),
                        'is_correct' => $is_correct,
                        'choice' => $value,
                        'quiz_number' => Secure($_POST['question_number']),
                       
                    );
                      
                      $insert_row=$db->insert(T_CHOICE,$choice_data);
                      if($insert_row){
                        continue;
                      }else {
                        die("Error: (".$mysqli->errno.") ".$mysqli->eerror);
                    }
                }
            }

             $data                          = array(
            'message' => $success_icon . __('quiz_added'),
            'status' => 200,
            'url' => UrlLink('admin-uploads/quiz')
        );
             
        } else {
        $data = array(
            'message' => 'Can not insert quiz',
            'status' => 500
        );
    }
    
    } else {
        $data = array(
            'message' => $error,
            'status' => 500
        );
    }

}