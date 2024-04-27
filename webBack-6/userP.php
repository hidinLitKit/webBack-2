<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" rel="stylesheet"/>
  <title>Users</title>
</head>
<body>
<div class="modal">
  <div class="modal-content">
    
    <h3>User Editing Mode</h3>
    <form action="" method="post">
      <input class="close" name="Close" value="&times;" type="submit"/>
      <label for="editFio">ФИО:</label>
      <input type="text" id="editFio" name="editFio" value="<?=$_COOKIE['fio_value']?>"><br>

      <label for="editEmail">Email:</label>
      <input type="email" id="editEmail" name="editEmail" value="<?=$_COOKIE['email_value']?>"><br>

      <label for="editYear">Дата рождения:</label>
      <label>
    Дата рождения:<br />
    <select name="editYear">
      <?php
      for ($i = 1922; $i <= 2022; $i++) {
        $selected = ($i == $_COOKIE['year_value']) ? 'selected' : '';
        printf('<option value="%d" %s>%d год</option>', $i, $selected, $i);
      }
      ?>
    </select>
  </label><br />
      <br>
      
        Пол:
        <label><input name="gender" type="radio" value="male" <?php if($_COOKIE['gender_value']=="male") {print "checked";} else print($_COOKIE['gender_value']); ?>/> Мужчина </label>
        <label><input name="gender" type="radio" value="female" <?php if($_COOKIE['gender_value']=="female") {print "checked";}  else print($_COOKIE['gender_value']); ?>/> Женщина </label>
        <br/>
        Выберете ваши любимые языки программирования:
        <br />
        <select name="field-multiple-language[]" multiple="multiple">
          <option <?php ChooseLanguage($langs, "Pascal") ?> value="Pascal">Pascal</option>
          <option <?php ChooseLanguage($langs, "JavaScript") ?> value="JavaScript">JavaScript</option>
          <option <?php ChooseLanguage($langs, "PHP") ?> value="PHP">PHP</option>
          <option <?php ChooseLanguage($langs, "Python") ?> value="Python">Python</option>
          <option <?php ChooseLanguage($langs, "Haskel") ?> value="Haskel">Haskel</option>
          <option <?php ChooseLanguage($langs, "Clojure") ?> value="Clojure">Clojure</option>
          <option <?php ChooseLanguage($langs, "Prolog") ?> value="Prolog">Prolog</option>
          <option <?php ChooseLanguage($langs, "Scala") ?> value="Scala">Scala</option>
        </select>
      </label>
      <br/>
      <br/>
      <label for="editBiography">Биография:</label>
      <br/>
      <textarea id="editBiography" name="editBiography"><?=$_COOKIE['biography_value']?></textarea><br>

      <br/>
      <label for="editCheckContract">Согласие:</label>
      <input type="checkbox" class="checkbox" id="editCheckContract" <?php if($_COOKIE['checkcontract_value'] == 1) print "checked" ?> name="editCheckContract"><br>

      <input class="save-button" type="submit" value="Сохранить" name="Edit">
    </form>
  </div>
</div>
</body>

<?php 
function ChooseLanguage($langs, $value){
  $langArray = str_getcsv($langs, ',');
  for($i = 0; $i < count($langArray); $i++)
  {
     if($langArray[$i] == $value)
       print "selected";
  }
}
?>