<link rel="stylesheet" href="style.css">
<form action="index.php" method="POST">
  <label>
    ФИО:<br />
    <input name="fio" type="text" placeholder="Фамилия, Имя, Отчество" />
  </label><br />
  <label>
    Ваш email:<br />
    <input name="email" type="email" placeholder="Введите вашу почту" />
  </label><br />
  <label>
    Дата рождения:<br />
    <select name="year">
      <?php 
      for ($i = 1922; $i <= 2022; $i++) {
        printf('<option value="%d">%d год</option>', $i, $i);
      }
      ?>
    </select>
  </label><br />
  <label>
    Пол:<br />
    <input type="radio" name="gender" value="male" /> Мужской
    <input type="radio" name="gender" value="female" /> Женский
  </label><br />
  <label>
    Выберите любимый язык программирования:<br />
    <select name="field-multiple-language[]" multiple="multiple">
      <option value="C">C</option>
      <option value="C++">C++</option>
      <option value="JavaScript">JavaScript</option>
      <option value="PHP">PHP</option>
      <option value="Python">Python</option>
      <option value="Java">Java</option>
      <option value="Haskel">Haskel</option>
      <option value="Clojure">Clojure</option>
      <option value="Prolog">Prolog</option>
      <option value="Scala">Scala</option>
    </select>
  </label><br />
  <label>
    Расскажите о себе!<br />
    <textarea name="biography">Меня зовут Кира Йошикаге...</textarea>
  </label><br />
  <label>
    <input type="checkbox" name="checkcontract" /> С контрактом ознакомлен(а)
  </label><br />
  <input type="submit" value="ok" />
</form>
