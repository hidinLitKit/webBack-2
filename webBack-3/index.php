<?php
// Отправляем браузеру правильную кодировку,
// файл index.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  // Включаем содержимое файла form.php.
  include('form.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;

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

$user = 'u67322'; // Заменить на ваш логин uXXXXX
$pass = '9577670'; // Заменить на пароль, такой же, как от SSH
$db = new PDO('mysql:host=localhost;dbname=u67322', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]); // Заменить test на имя БД, совпадает с логином uXXXXX

// Подготовленный запрос. Не именованные метки.
try {
  $stmt = $db->prepare("INSERT INTO application (fio, year, email, gender, biography, checkcontract) VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$_POST['fio'], $_POST['year'], $_POST['email'], $_POST['gender'], $_POST['biography'], $_POST['checkcontract']]);

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
