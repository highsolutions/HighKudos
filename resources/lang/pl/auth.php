<?php

return array (
  'failed' => 'Błędny login lub hasło.',
  'login' =>
  array (
    'email' => 'Adres e-mail',
    'login' => 'Zaloguj się',
    'password' => 'Hasło',
    'resetPassword' => 'Przypomnij hasło',
    'title' => 'Logowanie',
  ),
  'logout' => 'Wyloguj się',
  'notifications' =>
  array (
    'userCreated' =>
    array (
      'title' => 'Założono konto w systemie ....!',
      'about' => 'Zaloguj się do systemu wpisując:<br/>adres e-mail: :email<br/>hasło: :password',
      'action' => 'Zaloguj się',
      'after' => 'Koniecznie zmień hasło przy pierwszym logowaniu.',
    ),
    'userDeleted' =>
    array (
      'title' => 'Usunięto konto w systemie ....!',
      'about' => 'Twoje konto zostało usunięte z systemu. Dopóki nie zostanie przywrócone, nie będziesz mógł się zalogować.',
    ),
    'userRestored' =>
    array (
      'title' => 'Przywrócono konto w systemie ....!',
      'about' => 'Zaloguj się do systemu wpisując:<br/>adres e-mail: :email<br/>hasło: :password',
      'action' => 'Zaloguj się',
      'after' => 'Po zalogowaniu będziesz miał dostęp do wszystkich poprzednich danych.',
    ),
  ),
  'password' =>
  array (
    'email' =>
    array (
      'rememberPassword' => 'Pamiętasz hasło do konta? <a href="'. route('login') .'">Zaloguj się</a>',
      'email' => 'Adres e-mail',
      'remind' => 'Przypomnij hasło',
      'title' => 'Przypomnienie hasła',
    ),
    'notification' =>
    array (
      'title' => 'Zmień hasło do swojego konta w systemie ...',
      'instruction' => 'Otrzymałeś ten e-mail, ponieważ zgłoszono prośbę o zmianę hasła do Twojego konta.',
      'action' => 'Zmień hasło',
      'closing' => 'Jeśli to nie Ty zgłaszałeś/-aś tę prośbę, zignoruj tę wiadomość.',
    ),
    'reset' =>
    array (
      'rememberPassword' => 'Nie chcesz zmieniać hasła do konta? <a href="'. route('login') .'">Zaloguj się</a>',
      'email' => 'Adres e-mail',
      'password' => 'Nowe hasło',
      'passwordConfirmation' => 'Powtórz hasło',
      'reset' => 'Zmień hasło',
      'title' => 'Zmiana hasła',
    ),
  ),
  'throttle' => 'Za dużo nieudanych prób logowania. Proszę spróbować za :seconds sekund.',
  'wrongRole' => 'Zaloguj się jako administrator by mieć dostęp do panelu.',
);
