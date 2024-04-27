<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/


// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.


include('../credentials.php');
$db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $sth = $db->prepare('SELECT Login, Password FROM Admin');
  $sth->execute();

  $adminLogin;
  $adminPass;
  while ($row = $sth->fetch()) {
    $adminLogin = $row["Login"];
    $adminPass = $row["Password"];
  }
  

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != $adminLogin ||
    sha1($_SERVER['PHP_AUTH_PW']) != $adminPass) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}


if (isset($_POST))
{ 
  if(isset($_POST["Delete"])){
    DeleteUser($_POST["id"]);
    header('Location: ./admin.php');
  } 
  if(isset($_POST["Edit"])){

    setcookie('id', $_POST["id"], time() + 30 * 24 * 60 * 60);
    setcookie('fio_value', $_POST["fio"], time() + 30 * 24 * 60 * 60);
    setcookie('email_value', $_POST["email"], time() + 30 * 24 * 60 * 60);
    setcookie('year_value', $_POST["year"], time() + 30 * 24 * 60 * 60);
    setcookie('gender_value', $_POST["gender"], time() + 30 * 24 * 60 * 60);
    setcookie('langs_value', $_POST["field-multiple-language"], time() + 30 * 24 * 60 * 60);
    setcookie('biography_value', $_POST["biography"], time() + 30 * 24 * 60 * 60);
    setcookie('checkcontract_value', $_POST["checkcontract"], time() + 30 * 24 * 60 * 60);
    

    header('Location: ./user.php');
  } 
}


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  print('Вы успешно авторизовались и видите защищенные паролем данные.');
  $users = GetUsers();
  $result = GetLanguageStats();
  $sum = LanguageSum($result);

  include("adminP.php");
}
else{
  include("adminP.php");
}


function GetUsers()
{
  include('../credentials.php');
  $db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  try{
    $sth = $db->prepare('SELECT id, fio, year, email, gender, biography, checkcontract FROM application');
      $sth->execute();
      $k = 0;
      $values = array();
      $row = $sth->fetchAll();
      for($h = 0; $h < count($row); $h++) {
        $values['fio'] = $row[$h]['fio'];
        $values['year'] = $row[$h]['year'];
        $values['email'] = $row[$h]['email'];
        $values['gender'] = $row[$h]['gender'];
        $values['biography'] = $row[$h]['biography'];
        $values['checkcontract'] = $row[$h]['checkcontract'];
        $values['id'] = $row[$h]['id'];
        $formId = $row[$h]['id'];

        $sth = $db->prepare('SELECT language_id FROM application_language WHERE application_id = :id');
        $sth->execute(['id' => $formId]);
        $j = 0;
        $langsval = [];
        $rowlang = $sth->fetchAll();
        for($i = 0; $i < count($rowlang); $i++) {
          $sth = $db->prepare('SELECT name FROM programming_language WHERE id = :id');
          $sth->execute(['id' => ($rowlang[$i])['language_id']]);
          while ($langrow = $sth->fetch()) {
            $langsval[$j++] = $langrow['name'];
          }
        }
        $langsCV = '';
        for($i = 0; $i < count($langsval); $i++)
        {
          $langsCV .= $langsval[$i] . ",";
        }
        $values['field-multiple-language'] = $langsCV;
        $users[$k++] = $values;
      }
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
  

  return $users;
}

function DeleteUser($id)
{
  include('../credentials.php');
  $db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  try{
    $sth = $db->prepare('DELETE FROM Users WHERE FormId = :id');
    $sth->execute(['id' => $id]);
    $sth = $db->prepare('DELETE FROM application_language WHERE application_id = :id');
    $sth->execute(['id' => $id]);
    $sth = $db->prepare('DELETE FROM application WHERE id = :id');
    $sth->execute(['id' => $id]);
  }
  catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
  }
}

function GetLanguageStats()
{
  include('../credentials.php');
  $db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  try{
    $sth = $db->prepare('SELECT name, COUNT(*) AS LanguageCount 
    FROM application_language 
    JOIN programming_language ON application_language.language_id = programming_language.id 
    GROUP BY name ORDER BY LanguageCount DESC');
    $sth->execute();
    $result = array();
    while ($row = $sth->fetch()) {
      $result[$row["name"]]= $row['LanguageCount'];
    }
  }
  catch(PDOException $e){
    print_r($e->getTrace());
    exit();
  }

  return $result;
}

function LanguageSum($arr)
{
  $sum = 0;
  foreach($arr as $count)
  {
    $sum += $count;
  }
  return $sum;
}


?>