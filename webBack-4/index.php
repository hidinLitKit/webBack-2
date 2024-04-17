<?php
/**
 * Реализовать проверку заполнения обязательных полей формы в предыдущей
 * с использованием Cookies, а также заполнение формы по умолчанию ранее
 * введенными значениями.
 */

// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // Массив для временного хранения сообщений пользователю.
  $messages = array();

  // В суперглобальном массиве $_COOKIE PHP хранит все имена и значения куки текущего запроса.
  // Выдаем сообщение об успешном сохранении.
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages[] = 'Спасибо, результаты сохранены.';
  }
// Складываем признак ошибок в массив.
$errors = array();
$errors['fio'] = !empty($_COOKIE['fio_error']);
$errors['email'] = !empty($_COOKIE['email_error']);
$errors['year'] = !empty($_COOKIE['year_error']);
$errors['gender'] = !empty($_COOKIE['gender_error']);
$errors['field-multiple-language'] = !empty($_COOKIE['langs_error']);
$errors['biography'] = !empty($_COOKIE['biography_error']);
$errors['checkcontract'] = !empty($_COOKIE['checkcontract_error']);

// Выдаем сообщения об ошибках.
if ($errors['fio']) {
  // Удаляем куки, указывая время устаревания в прошлом.
  setcookie('fio_error', '', 100000);
  setcookie('fio_value', '', 100000);
  // Выводим сообщение.
  $messages[] = '<div class="error">Заполните имя.</div>';
}
if ($errors['email']) {
  setcookie('email_error', '', 100000);
  setcookie('email_value', '', 100000);
  $messages[] = '<div class="error">Заполните email.</div>';
}
if ($errors['year']) {
  setcookie('year_error', '', 100000);
  setcookie('year_value', '', 100000);
  $messages[] = '<div class="error">Заполните год.</div>';
}
if ($errors['gender']) {
  setcookie('gender_error', '', 100000);
  setcookie('gender_value', '', 100000);
  $messages[] = '<div class="error">Выберете один из вариантов.</div>';
}
if ($errors['field-multiple-language']) {
  setcookie('langs_error', '', 100000);
  setcookie('langs_value', '', 100000);
  $messages[] = '<div class="error">Выберете хотя бы один язык.</div>';
}
if ($errors['biography']) {
  setcookie('biography_error', '', 100000);
  setcookie('biography_value', '', 100000);
  $messages[] = '<div class="error">Заполните биографию.</div>';
}
if ($errors['checkcontract']) {
  setcookie('checkcontract_error', '', 100000);
  setcookie('checkcontract_value', '', 100000);
  $messages[] = '<div class="error">Согласие обязательно.</div>';
}

// Складываем предыдущие значения полей в массив, если есть.
$values = array();
$values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
$values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
$values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
$values['gender'] = empty($_COOKIE['gender_value']) ? '' : $_COOKIE['gender_value'];
$values['field-multiple-language'] = empty($_COOKIE['langs_value']) ? '' : $_COOKIE['langs_value'];
$values['biography'] = empty($_COOKIE['biography_value']) ? '' : $_COOKIE['biography_value'];
$values['checkcontract'] = empty($_COOKIE['checkcontract_value']) ? '' : $_COOKIE['checkcontract_value'];


// Включаем содержимое файла form.php.
// В нем будут доступны переменные $messages, $errors и $values для вывода 
// сообщений, полей с ранее заполненными данными и признаками ошибок.
include('form.php');
}

else
{
  // Проверяем ошибки
  $errors = FALSE;
  //Получаем значения
  $fioval = $_POST['fio'];
  $emailval = $_POST['email'];
  $yearval = $_POST['year'];
  $genderval = $_POST['gender'];
  $checkval = !empty($_POST['checkcontract']);
  $bioval = $_POST['biography'];
  $langsval = !empty($_POST['field-multiple-language'])?$_POST['field-multiple-language']:null;
  
  
  // Проверка ФИО
  if (!preg_match("/^[a-zA-Zа-яА-Я\s]+$/u", $_POST['fio'])) {
    print('Заполните имя.<br/>');
    $errors = TRUE;
  }

  // Проверка года
  if (empty($_POST['year']) || !is_numeric($_POST['year']) || !preg_match('/^\d+$/', $_POST['year'])){
    print('Заполните год.<br/>');
    $errors = TRUE;
  }

  // Проверка email
  if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
    print('Введите корректный email.<br/>');
    $errors = TRUE;
  }

  // Проверка пола
  if (empty($_POST['gender']) || ($_POST['gender'] != 'male' && $_POST['gender'] != 'female')) {
    print('Выберите пол.<br/>');
    $errors = TRUE;
  }

  // Проверка выбора языка программирования
  if (empty($_POST['field-multiple-language'])) {
    print('Выберите язык программирования.<br/>');
    $errors = TRUE;
  }
  else
  {

  }

  // Проверка биографии
  if (empty($_POST['biography']) || strlen($_POST['biography']) > 150) {
    print('Расскажите о себе.<br/>');
    $errors = TRUE;
  }

  // Проверка согласия с контрактом
  if (empty($_POST['checkcontract'])) {
    print('Вы должны согласиться с контрактом.<br/>');
    $errors = TRUE;
  }

  // *************
  // Тут необходимо проверить правильность заполнения всех остальных полей.
  // *************

  if ($errors) {
    // При наличии ошибок завершаем работу скрипта.
    exit();
  }
  print('Валидация прошла успешно!');
  // Сохранение в базу данных.

  include('../credentials.php');
  $db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
    [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX
}
// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO application (fio, year, email, gender, biography, checkcontract) VALUES (?, ?, ?, ?, ?, ?)");
  $checkContractValue = $_POST['checkcontract'] === 'on' ? 1 : 0;
  $stmt->execute([$_POST['fio'], $_POST['year'], $_POST['email'], $_POST['gender'], $_POST['biography'], $checkContractValue]);

      // Получение ID последней вставленной записи
      $lastInsertId = $db->lastInsertId();

      // Сохранение выбранных языков программирования в отдельной таблице
      /* if (!empty($_POST['field-multiple-language'])) {
        $languages = $_POST['field-multiple-language'];
        foreach ($languages as $language) {
          $stmt = $db->prepare("INSERT INTO programming_languages (user_id, language) VALUES (?, ?)");
          $stmt->execute([$lastInsertId, $language]);
        }
      } */
      if (!empty($_POST['field-multiple-language'])) {
        $languages = $_POST['field-multiple-language'];
        foreach ($languages as $language) {
            $stmt = $db->prepare("SELECT id FROM programming_language WHERE name = ?");
            $stmt->execute([$language]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if (!$row) {
                $stmt = $db->prepare("INSERT INTO programming_language (name) VALUES (?)");
                $stmt->execute([$language]);
                $languageId = $db->lastInsertId();
            } else {
                $languageId = $row['id'];
            }
    
            $stmt = $db->prepare("INSERT INTO application_language (application_id, language_id) VALUES (?, ?)");
            $stmt->execute([$lastInsertId, $languageId]);
        }
    }
    print('Данные успешно сохранены!');
}
catch(PDOException $e){
  print('Error : ' . $e->getMessage());
  exit();
}

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test (label,color) VALUES (:label,:color)");
//$stmt -> execute(['label'=>'perfect', 'color'=>'green']);
 
//Еще вариант
/*$stmt = $db->prepare("INSERT INTO users (firstname, lastname, email) VALUES (:firstname, :lastname, :email)");
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':email', $email);
$firstname = "John";
$lastname = "Smith";
$email = "john@test.com";
$stmt->execute();
*/

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
