練習問題（ren053.php）
<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}
function check1($shin)
{
  $msg = "";
  if (empty($shin)) {
    $msg = "身長の値が未入力か誤りがあります";
  } elseif ($shin < 0 || $shin > 300) {
    $msg = "身長の値に誤りがあります";
  }
  return $msg;
}
function check2($tai)
{
  $msg = "";
  if (empty($tai)) {
    $msg = "体重の値が未入力か誤りがありま";
  } elseif ($tai < 0 || $tai > 250) {
    $msg = "体重の値に誤りがあります";
  }
  return $msg;
}
function check3($mail)
{
  $msg = "";
  if (empty($mail)) {
    $msg = "メールが未入力か、誤りがあります";
  }
  return $msg;
}
function check4($nen)
{
  $msg = "";
  if (empty($nen) && $nen != 0) {
    $msg = "年齢の値に誤りがあります";
  } elseif ($nen < 0 || $nen > 150) {
    $msg = "年齢の値に誤りがあります";
  }
  return $msg;
}
$shinn = $_GET["shin"];
$taii = $_GET["tai"];
$email = $_GET["mail"];
$nenn = $_GET["nen"];
$shin = filter_input(INPUT_GET, "shin", FILTER_VALIDATE_INT);
$tai = filter_input(INPUT_GET, "tai", FILTER_VALIDATE_INT);
$mail = filter_input(INPUT_GET, "mail", FILTER_VALIDATE_EMAIL);
$nen = filter_input(INPUT_GET, "nen", FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>BMI 計算機</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<boby>
  <?php
  $chk1 = check1(h($shin));
  $chk2 = check2(h($tai));
  $chk3 = check3(h($mail));
  $chk4 = check4(h($nen));
  if (!(($chk1 == "" && $chk2 == "" && $chk3 == "" && $chk4 == "")) || is_null($shin)) :
  ?>
    <form method="get">
      <p><label>身 長</label>
        <input name="shin" type="text" value="<?= $shinn ?>"><span>cm</span>
      </p>
      <?php if ($chk1 == "" || is_null($shin)): ?>
        <p> </p>
      <?php else: ?>
        <p class="error"> <?= $chk1 ?></p>
      <?php endif; ?>
      <p><label>体 重</label>
        <input name="tai" type="text" value="<?= $taii ?>"><span>kg</span>
      </p>
      <?php if ($chk2 == "" || is_null($tai)): ?>
        <p> </p>
      <?php else: ?>
        <p class="error"> <?= $chk2 ?></p>
      <?php endif; ?>
      <p><label>メール</label>

        <input name="mail" type="email" value="<?= $email ?>">
      </p>
      <?php if ($chk3 == "" || is_null($mail)): ?>
        <p> </p>
      <?php else: ?>
        <p class="error"> <?= $chk3 ?></p>
      <?php endif; ?>
      <p><label>年 齢</label>
        <input name="nen" type="text" value="<?= $nenn ?>"><span>才</span>
      </p>
      <?php if ($chk4 == "" || is_null($nen)): ?>
        <p> </p>
      <?php else: ?>
        <p class="error"> <?= $chk4 ?></p>
      <?php endif; ?>
      <p class="center">
        <input type="submit" value="送信">
      </p>
    </form>
  <?php else:
    $bmi = $tai / (($shin / 100) ** 2);
  ?>
    BMI:<?= $bmi ?><br>
    <?php
    if ($bmi < 18.5) {
      echo ("やせすぎです");
    } elseif ($bmi < 25) {
      echo ("ふつうです");
    } elseif ($bmi < 30) {
      echo ("ふとりすぎ１です");
    } elseif ($bmi < 35) {
      echo ("ふとりすぎ２です");
    } elseif ($bmi < 40) {
      var_dump("ふとりすぎ３です");
    } else {
      echo ("ふとりすぎ４です");
    }
    ?>
  <?php endif; ?>
  </body>

</html>