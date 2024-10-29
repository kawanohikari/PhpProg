<!-- 練習問題（ren057.php）  -->
<?php
$seiza = ["やぎ座" => "0119", "みずがめ座" => "0218", "うお座" => "0320", "おひつじ座" => "0419", "おうし座" => "0520", "ふたご座" => "0621", "かに座" => "0722", "しし座" => "0822", "おとめ座" => "0922", "てんびん座" => "1023", "さそり座" => "1122", "いて座" => "1221"];
$tukinohi = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}
function get_seiza($seiza, $tuki, $hi)
{
  $tukihi = date("md", strtotime("2024-" . $tuki . "-" . $hi));
  foreach ($seiza as $key => $val) {
    if ($tukihi <= $val) {
      return "あなたは、" . $key . "です。";
    }
  }
  return "あなたは、やぎ座です。";
}
$msg = "";
$tuki = $_GET["tuki"];
$hi = $_GET["hi"];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>あなたの星座</title>
  <style>
    .error {
      color: red;
    }
  </style>
</head>
<boby>
  あなたの星座をお調べいたします。<BR>
  誕生日の月と日を入力してください<BR>
  <form method="get">
    月：<INPUT name="tuki" type="number" value=<?= $tuki ?>><br>
    日：<INPUT name="hi" type="number" value=<?= $hi ?>><br>
    <input type="submit" value="送信"><br>
  </form>
  <?php
  if (!is_null($tuki)) {
    if ($tuki > 0 && $tuki < 13) {
      if ($hi >= 1 && $hi <= $tukinohi[$tuki - 1]) {
        $msg = get_seiza($seiza, h($tuki), h($hi));
      } else {
        $msg = "入力した値に誤りがあります再入力してください";
      }
    } else {
      $msg = "入力した値に誤りがあります再入力してください";
    }
  }
  ?>
  <?= $msg ?>