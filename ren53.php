<!-- 身長、体重、メール、年齢、送信ボタンをフォームに配置し、範囲チェックを行い、すべて正常データならばBMIと診断結果を表示しなさい。 -->
<!DOCTYPE html>
<html lang="ja">

<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}

function bmi($sincho, $taijuu)
{
  $sincho /= 100;
  $bmi = $taijuu / ($sincho ** 2);
  return $bmi;
}

function errSincho($sincho)
{
  if (1 <= $sincho && $sincho <= 300) {
    return false;
  }
  return true;
}

function errTaijuu($taijuu)
{
  if (1 <= $taijuu && $taijuu <= 250) {
    return false;
  }
  return true;
}

function errEmail($e_mail)
{
  if (empty($e_mail)) {
    return true;
  }
  return false;
}

function errNenrei($nenrei)
{
  if (0 <= $nenrei && $nenrei <= 150) {
    return false;
  }
  return true;
}

$e_mail_tmp = $_GET["e_mail"];
$sincho = filter_input(INPUT_GET, "sincho", FILTER_VALIDATE_INT);
$taijuu = filter_input(INPUT_GET, "taijuu", FILTER_VALIDATE_INT);
$e_mail = filter_input(INPUT_GET, "e_mail", FILTER_VALIDATE_EMAIL);
$nenrei = filter_input(INPUT_GET, "nenrei", FILTER_VALIDATE_INT);
?>

<head>
  <meta charset="UTF-8">
  <title>BMI計算機</title>
  <style>
    table {
      width: 400px;
      /* border: 1px solid black; */
    }

    input {
      text-align: right;
    }

    .error_msg {
      margin-right: 0;
      text-align: right;
    }

    .error_msg input {
      width: 300px;
      border: 0;
      color: red;
      font-weight: bold;
      text-align: left;
    }

    .e_mail {
      width: 230px;
    }

    .button {
      text-align: right;
    }
  </style>
</head>

<body>
  <h1>BMI計算機</h1>
  <?php
  $showForm = false;
  if (empty($sincho) && empty($taijuu) && empty($e_mail) && empty($nenrei)) {
    $showForm = true;
  }
  if (errSincho($sincho)) {
    $showForm = true;
  }
  if (errTaijuu($taijuu)) {
    $showForm = true;
  }
  if (errEmail($e_mail)) {
    $showForm = true;
  }
  if (errNenrei($nenrei)) {
    $showForm = true;
  }
  ?>
  <?php if ($showForm) : ?>
    <p>フォーム入力</p>
    <form method="get">
      <table>
        <tr>
          <td><label>身長</label><input type="number" name="sincho" value="<?= h($sincho) ?>">cm</td>
        </tr>
        <tr>
          <td class="error_msg">
            <?php if (!is_null($sincho) && errSincho($sincho)) : ?>
              <input type="text" name="err_sincho" value="1～300cm の範囲で入力してください。">
            <?php else : ?>
              <input type="text" name="err_sincho" value="">
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td><label>体重</label><input type="number" name="taijuu" value="<?= h($taijuu) ?>">kg</td>
        </tr>
        <tr>
          <td class="error_msg">
            <?php if (!is_null($taijuu) && errTaijuu($taijuu)) : ?>
              <input type="text" name="err_taijuu" value="1～250kg の範囲で入力してください。">
            <?php else : ?>
              <input type="text" name="err_taijuu" value="">
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td><label>メール</label><input type="text" name="e_mail" class="e_mail" value="<?= h($e_mail_tmp) ?>"></td>
        </tr>
        <tr>
          <td class="error_msg">
            <?php if (!is_null($e_mail) && empty($e_mail)) : ?>
              <input type="text" name="err_e_mail" value="e-mail形式が不正です。">
            <?php else : ?>
              <input type="text" name="err_e_mail" value="">
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td><label>年齢</label><input type="number" name="nenrei" value="<?= h($nenrei) ?>">歳</td>
        </tr>
        <tr>
          <td class="error_msg">
            <?php if (!is_numeric($nenrei) && !is_null($nenrei) || errNenrei($nenrei)) : ?>
              <input type="text" name="err_nenrei" value="0～150歳 の範囲で入力してください。">
            <?php else : ?>
              <input type="text" name="err_nenrei" value="">
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td class="button"><input type="submit" value="送信"></td>
        </tr>
      </table>
    </form>
  <?php else: ?>
    <?php
    $bmi = bmi($sincho, $taijuu);
    echo "BMI = $bmi \n\n";
    if ($bmi < 18.5) {
      echo "やせすぎです。";
    } elseif ($bmi < 25) {
      echo "ふつうです。";
    } else {
      echo "ふとりすぎです。";
    }
    ?>
  <?php endif; ?>
</body>

</html>