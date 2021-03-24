<?php
$errors   = array();

$active      = '';
$username    = '';
$email       = '';
$success     = '';
$session_id  = '';
if (!empty($_POST)) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $data = array(
            'status' => 200,
            'session_id' => $error_icon . __('please_check_details'),
        );
    }
    
    else {
        
        $username        = Secure($_POST['username']);
        $password        = Secure($_POST['password']);
        $login = $db->where("(username = ? or email = ?)", array(
                $username,
                $username
            ))->getOne(T_USERS, ["password", "id", "active","admin"]);

     

         if (empty($login)) {
                $errors[] = $error_icon . __('invalid_username_or_password');
            } 


        else if (!password_verify($password, $login->password)) {
                $errors[] = $error_icon . __('please_check_details');
        }
        if (!empty($login) && empty($errors)) {
            if ($login->active == 0) {
                $errors =
                $data = array(
                    'status' => 400,
                    'message' => $error_icon . __('account_is_not_active') . ' <a href="#" data-email-code="'.$login->email_code.'" data-username="'.$login->username.'" id="resend_confirmation_email">' . __('resend_email') . '</a>'
                );
            }
            else {

                $session_id          = sha1(rand(11111, 99999)) . time() . md5(microtime());
                $insert_data         = array(
                    'user_id' => $login->id,
                    'session_id' => $session_id,
                    'time' => time()
                );
                $insert              = $db->insert(T_SESSIONS, $insert_data);
                $data = array(
                    'status'     => 200,
                    'message'    =>  $success_icon . __('successfully_joined_desc'),
                    'session_id' =>  $session_id
                );
            }
        } else {
            $data = array(
                'status' => 400,
                'message' => $error_icon . __('invalid_username_or_password')
            );
        }
    }
}
