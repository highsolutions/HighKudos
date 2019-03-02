<?php

return [

    'failed' => 'These credentials do not match our records.',
    'login' =>
        array (
            'email' => 'E-mail address',
            'login' => 'Log in',
            'password' => 'Password',
            'resetPassword' => 'Reset password',
            'title' => 'Logging in',
        ),
    'logout' => 'Wyloguj się',
  'notifications' =>
  array (
    'userCreated' =>
    array (
      'title' => 'An account has been created in the system ....!',
      'about' => 'Log in to the system by typing: <br/>email address: :email <br/>password: :password',
      'action' => 'Log in',
      'after' => 'We recommend changing password at first login.',
    ),
    'userDeleted' =>
    array (
      'title' => 'An account has been deleted in the system ....!',
      'about' => 'Your account has been removed from the system. Until it is restored, you will not be able to log in.',
    ),
    'userRestored' =>
    array (
      'title' => 'An account has been restored in the system ....!',
      'about' => 'Log in to the system by typing: <br/>email address: :email <br/>password: :password',
      'action' => 'Log in',
      'after' => 'After logging in you will have access to all previous data.',
    ),
  ),
  'password' =>
  array (
    'email' =>
    array (
      'rememberPassword' => 'Do you remember your account password? <a href="'. route('login') .'"> Log in </a>',
      'email' => 'E-mail address',
      'remind' => 'Remind password',
      'title' => 'Password reminder',
    ),
    'notification' =>
    array (
      'title' => 'Change the password to your account in the system ...',
      'instruction' => 'You received this e-mail because a request to change the password for your account has been made.',
      'action' => 'Change password',
      'closing' => 'If you did not report this request, please ignore this message.',
    ),
    'reset' =>
    array (
      'rememberPassword' => 'Do not you want to change your account password? <a href="'. route('login') .'">Log in</a>',
      'email' => 'E-mail address',
      'password' => 'New password',
      'passwordConfirmation' => 'Repeat password',
      'reset' => 'Change password',
      'title' => 'Change password',
    ),
  ),
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'wrongRole' => 'Log in as administrator to have access to this page.',

];
