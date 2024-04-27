<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  print('Страничка редактирования.');
  $langs = $_COOKIE['langs_value'];
  $id = $_COOKIE["id"];

  include("userP.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if(isset($_POST["Close"])){
    header('Location: ./admin.php');
    exit();
  } 

  if(isset($_POST["Edit"])){
    SaveUser();
    header('Location: ./admin.php');
    exit();
  }
}


function SaveUser()
{
  include('../credentials.php');
  $db = new PDO('mysql:host=localhost;dbname=u67322', $db_user, $db_pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $formId = $_COOKIE["id"];

    $agree = empty($_POST["editCheckContract"])?"0":"1";
    $stmt = $db->prepare("UPDATE application SET fio = :fio, year = :year, email = :email,  gender = :gender, biography = :biography, checkcontract = :checkcontract WHERE id = :id");
    $stmt -> execute(['fio'=>$_POST["editFio"], 'year'=>$_POST["editYear"], 'email'=>$_POST["editEmail"],'gender'=>$_POST["gender"],'biography'=>$_POST["editBiography"], 'checkcontract'=>$agree, 'id' => $formId]);

    $stmt = $db->prepare("DELETE FROM application_language WHERE application_id = :formId");
    $stmt -> execute(['formId'=>$formId]);
    
    $langsval = array();
    $langsval = $_POST["field-multiple-language"];

    for($i = 0; $i < count($langsval); $i++)
    {
        $langId = null;
        $sth = $db->prepare('SELECT id FROM programming_language WHERE name = :langName');
        $sth->execute(['langName' => $langsval[$i]]);
        while ($row = $sth->fetch()) {
          $langId = $row['Id'];
        }
        if($langId == null)
        {
          $stmt = $db->prepare("INSERT INTO programming_language (name) VALUES (:languageNameDB)");
          $stmt -> execute(['languageNameDB'=>$langsval[$i]]);

          $langId = $db->lastInsertId();
        }

        $stmt = $db->prepare("INSERT INTO application_language (application_id, language_id) VALUES (:formId, :languageIdDB)");
        $stmt -> execute(['formId'=>$formId, 'languageIdDB'=>$langId]);
    }
    
}
?>