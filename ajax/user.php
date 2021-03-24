<?php
if (empty($_REQUEST['user_id']) || !IS_LOGGED) {
    exit("Undefined Alien ಠ益ಠ");
}
$is_owner = false;
if ($_REQUEST['user_id'] == $user->id || IsAdmin()) {
    $is_owner = true;
}

$erros_final = '';
$active      = '';
$username    = '';
$email       = '';
$success     = '';
$defaulGender = 'selectGender';
$country      = '';

if ($first == 'save_book') {

   
 
        if (empty($_POST['book_id']) || empty($_POST['user_id'])) {
            $errors[] = $error_icon . __('please_check_details');
        }
         
        $book_id = Secure($_POST['book_id']);
        $user_id = Secure($_POST['user_id']);
    
        $isApplied = $db->where('lesson_id',$book_id)->where('user_id',$user_id)->getValue(T_USER_LESSONS, 'count(*)');
        $job_applied = false;

        $insert_data         = array(
            'user_id' => $kd->user->id,
            'lesson_id' => $book_id,
        );
      if($isApplied === 0 ){
           $apply = $db->insert(T_USER_LESSONS, $insert_data);
     
            if ($apply) {

               
                $data = array(
                    'status' => 200,

                );
            }
      }  else{
         $data = array(
                'status' => 400,
                
            );

      }
}
if ($first == 'general') {
    if (empty($_POST['user_id']) OR empty($_POST['hash_id'])) {
        $errors[] = $error_icon . __('please_check_details');
    }

    else {
        $user_data = UserData($_POST['user_id']);
        if (!empty($user_data->id)) {
            // if ($_POST['email'] != $user_data->email) {
            //     if (UserEmailExists($_POST['email'])) {
            //         $errors[] = $error_icon . __('email_exists');
            //     }
            // }
            if ($_POST['username'] != $user_data->username) {
                $is_exist = UsernameExists($_POST['username']);
                if ($is_exist) {
                    $errors[] = $error_icon . __('username_is_taken');
                }
            }
            if (in_array($_POST['username'], $kd->site_pages)) {
                $errors[] = $error_icon . __('username_invalid_characters');
            }
            // if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            //     $errors[] = $error_icon . __('email_invalid_characters');
            // }
            if (strlen($_POST['username']) < 4 || strlen($_POST['username']) > 32) {
                $errors[] = $error_icon . __('username_characters_length');
            }
            if (!preg_match('/^[\w]+$/', $_POST['username'])) {
                $errors[] = $error_icon . __('username_invalid_characters');
            }
            $active = $user_data->active;
            if (!empty($_POST['activation']) && IsAdmin()) {
                if ($_POST['activation'] == '1') {
                    $active = 1;
                } else {
                    $active = 2;
                }
                if ($active == $user_data->active) {
                    $active = $user_data->active;
                }
            }
            $type = $user_data->admin;
            if (!empty($_POST['type']) && IsAdmin()) {
                if ($_POST['type'] == '2') {
                    $type = 1;
                }

                else if ($_POST['type'] == '1') {
                    $type = 0;
                }
                if ($type == $user_data->admin) {
                    $type = $user_data->admin;
                }
            }
            $gender       = 'male';
            $gender_array = array(
                'male',
                'female'
            );
            if (!empty($_POST['gender'])) {
                if (in_array($_POST['gender'], $gender_array)) {
                    $gender = $_POST['gender'];
                }
            }
            if (empty($errors)) {
                $update_data = array(
                    'namei' => Secure($_POST['namei']),
                    'username' => Secure($_POST['username']),
                     'showgoogle' => Secure($_POST['showgoogle']),
                    'gender' => Secure($gender),
                    'country_id' => Secure($_POST['country']),
                    'active' => Secure($active),
                    'admin' => Secure($type),
                    'facebook' => Secure($_POST['facebook']),
                    'pinterest' => Secure($_POST['pinterest']),
                    'twitter' => Secure($_POST['twitter']),
                    'linkedin' => Secure($_POST['linkedin']),
                    'location' => Secure($_POST['location']),
                );
              
                if (!empty($_POST['verified'])) {
                    if ($_POST['verified'] == 'verified') {
                        $verification = 1;
                    } else {
                        $verification = 0;
                    }
                    if ($verification == $user_data->verified) {
                        $verification = $user_data->verified;
                    }
                    $update_data['verified'] = $verification;
                }
                if ($is_owner == true) {
         
                    $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
      
                    if ($update){
                        $data = array(
                            'status' => 200,
                            'message' => $success_icon . __('setting_updated')
                        );
                    }
                }
            }
        }
    }
}

if ($first == 'profile') {
    $user_data = UserData($_POST['user_id']);
    if (!empty($user_data->id)) {
        if (empty($errors)) {
            $update_data = array(
                // 'about' => Secure($_POST['about']),
                'location' => Secure($_POST['location']),
                // 'website' => Secure($_POST['website']),
                'facebook' => Secure($_POST['facebook']),
                'pinrest' => Secure($_POST['pinrest']),
                'twitter' => Secure($_POST['twitter']),
                'linkedln' => Secure($_POST['linkedln'])
            );
            if ($is_owner == true) {
                $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
                if ($update) {
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . __('setting_updated')
                    );
                }
            }
        }
    }
}

if ($first == 'change-pass') {
    $user_data = UserData($_POST['user_id']);
    if (!empty($user_data->id)) {
        if ( !IsAdmin() && empty($_POST['current_password']) ) {
            $errors[] = $error_icon . __('please_check_details');
        }
        else if ( empty($_POST['new_password']) || empty($_POST['confirm_new_password'])) {
            $errors[] = $error_icon . __('please_check_details');
        } else {
            if ( !IsAdmin() ) {
                if ($user_data->password != sha1($_POST['current_password'])) {
                    $errors[] = $error_icon . __('current_password_dont_match');
                }
            }
            if (strlen($_POST['new_password']) < 4) {
                $errors[] = $error_icon . __('password_is_short');
            }
            if ($_POST['new_password'] != $_POST['confirm_new_password']) {
                $errors[] = $error_icon . __('new_password_dont_match');
            }
            if (empty($errors)) {
                $update_data = array(
                    'password' => sha1($_POST['new_password'])
                );
                if ($is_owner == true || IsAdmin()) {
                    $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
                    if ($update) {
                        $data = array(
                            'status' => 200,
                            'message' => $success_icon . __('setting_updated')
                        );
                    }
                }
            }
        }
    }
}

if ($first == 'delete' && $kd->config->delete_account == 'on') {
    $user_data = UserData($_POST['user_id']);
    if (!empty($user_data->id)) {
        if ($user_data->password != sha1($_POST['current_password'])) {
            $errors[] = $error_icon . __('current_password_dont_match');
        }
        if (empty($errors) && $is_owner == true) {
            $delete = DeleteUser($user_data->id);
            if ($delete) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . __('your_account_was_deleted'),
                    'url' => UrlLink('/logout')
                );
            }
        }
    }
}

if ($first == 'update-notifications') {
    $user_data = UserData($_POST['user_id']);
    if (!empty($user_data->id)) {
        $notifications_array = [
            'notification_on_answered_question',
            'notification_on_visit_profile',
            'notification_on_like_question',
            'notification_on_shared_question'
        ];
        $update_data = [];
        if(empty($_POST['notifications'])){
            foreach ($notifications_array as $key){
                $update_data[$key] = 0;
            }
        }else{
            foreach ($notifications_array as $key){
                $update_data[$key] = (int)in_array($key, $_POST['notifications']);
            }
        }
        if ($is_owner == true) {
            $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
            if ($update) {
                $data = array(
                    'status' => 200,
                    'message' => $success_icon . __('setting_updated')
                );
            }
        }
    }
}

if ($first == 'step_info') {
    if (empty($_POST['user_id'])) {
        $errors[] = $error_icon . __('please_check_details');
    }

    else{
        $user_data = UserData($_POST['user_id']);
        if (!empty($user_data->id)) {
            $gender       = 'male';
            $gender_array = array(
                'male',
                'female'
            );
            if (!empty($_POST['gender'])) {
                if (in_array($_POST['gender'], $gender_array)) {
                    $gender = $_POST['gender'];
                }
            }
            $update_data = array(
                'first_name' => Secure($_POST['first_name']),
                'last_name' => Secure($_POST['last_name']),
                'gender' => Secure($gender),
                'country_id' => Secure($_POST['country']),
                'startup' => 2
            );
            if ($is_owner == true) {
                $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
                if ($update){
                    $data = array(
                        'status' => 200,
                        'message' => $success_icon . __('setting_updated')
                    );
                }
            }
        }
    }
}

if ($first == 'avatar') {
    $user_data = UserData($_POST['user_id']);
    $update_data = array();
    if (!empty($user_data->id)) {
        if (!empty($_FILES['avatar']['tmp_name'])) {
            $file_info = array(
                'file' => $_FILES['avatar']['tmp_name'],
                'size' => $_FILES['avatar']['size'],
                'name' => $_FILES['avatar']['name'],
                'type' => $_FILES['avatar']['type'],
                'crop' => array('width' => 400, 'height' => 400),
                'mode' => 'avatar'
            );
            $file_upload = ShareFile($file_info);
            if (!empty($file_upload['filename'])) {
                $update_data['avatar'] = $file_upload['filename'];
            }
        }
        if (!empty($_FILES['cover']['tmp_name'])) {
            $file_info = array(
                'file' => $_FILES['cover']['tmp_name'],
                'size' => $_FILES['cover']['size'],
                'name' => $_FILES['cover']['name'],
                'type' => $_FILES['cover']['type'],
                'crop' => array('width' => 1000, 'height' => 550)
            );
            $file_upload = ShareFile($file_info);
            if (!empty($file_upload['filename'])) {
                $update_data['cover'] = $file_upload['filename'];
            }
        }
    }
    if ($is_owner == true) {
        if(isset($_POST['mode']) && $_POST['mode'] == 'step' ){
            $update_data['startup'] = 1;
        }
        $update = $db->where('id', Secure($_POST['user_id']))->update(T_USERS, $update_data);
        if ($update) {
            $data = array(
                'status' => 200,
                'message' => $success_icon . __('avatar_uploaded_successfully')
            );
            if(isset($update_data['avatar'])){
                $data['avatar_url'] = GetMedia($update_data['avatar']);
            }
            if(isset($update_data['cover'])){
                $data['cover_url'] = GetMedia($update_data['cover']);
            }
        }
    }
}

if ($first == 'mention') {
    $data = GetFollowingSug(5, $_GET['term']);
}

if ($first == 'save_user_location' && isset($_POST['lat']) && isset($_POST['lng'])) {
    $lat          = Secure($_POST['lat']);
    $lng          = Secure($_POST['lng']);
    $update_array = array(
        'lat' => (is_numeric($lat)) ? $lat : 0,
        'lng' => (is_numeric($lng)) ? $lng : 0,
        'last_location_update' => (strtotime("+1 week"))
    );
    $data         = array(
        'status' => 304
    );
    if (UpdateUserData($user->id, $update_array)) {
        $data['status'] = 200;
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

header("Content-type: application/json");
if (isset($errors)) {
    echo json_encode(array(
        'errors' => $errors
    ));
    exit();
}
