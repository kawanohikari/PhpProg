<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>星座を表示</title>
</head>

<body>
  <h1>星座を表示</h1>
  <?php
  function h($str)
  {
    return htmlspecialchars($str, ENT_QUOTES, "utf-8");
  }

  //月は 1～12 まで
  function monthNG($month)
  {
    return $month < 1 || 12 < $month;
  }

  //日は 1～月末 まで
  function dayNG($month, $day)
  {
    return $day < 1 || date('t', strtotime(sprintf("2024-%02d-01", $month))) < $day;
  }

  //月日を4桁にする
  function getMonthDay($month, $day)
  {
    return sprintf("%02d%02d", $month, $day);
  }

  $seizas = [
    ["end" => "0119", "seiza" => "やぎ座"],
    ["end" => "0218", "seiza" => "みずがめ座"],
    ["end" => "0320", "seiza" => "うお座"],
    ["end" => "0419", "seiza" => "おひつじ座"],
    ["end" => "0520", "seiza" => "おうし座"],
    ["end" => "0621", "seiza" => "ふたご座"],
    ["end" => "0722", "seiza" => "かに座"],
    ["end" => "0822", "seiza" => "しし座"],
    ["end" => "0922", "seiza" => "おとめ座"],
    ["end" => "1023", "seiza" => "てんびん座"],
    ["end" => "1122", "seiza" => "さそり座"],
    ["end" => "1221", "seiza" => "いて座"],
    ["end" => "1231", "seiza" => "やぎ座"],
  ];

  // 入力チェック
  $inputNG = false;
  $month = filter_input(INPUT_GET, "month", FILTER_VALIDATE_INT);
  $day = filter_input(INPUT_GET, "day", FILTER_VALIDATE_INT);
  if (monthNG($month) || dayNG($month, $day)) {
    $inputNG = true;
  }

  // 星座を求める
  if (is_null($_GET["day"])) {
    print("<p>月日を入力してください。</p>");
  } else if ($inputNG) {
    print("<p>正しい月日を入力してください。</p>");
  } else {
    $mmdd = getMonthDay($month, $day);
    foreach ($seizas as $seiza) {
      if ($mmdd <= $seiza["end"]) {
        $za = $seiza["seiza"];
        break;
      }
    }
    print("<p>" . h($za) . "です。</p>");
  }
  ?>
  <form method="get">
    <label for="input">月：</label>
    <input type="number" name="month" value="<?= h($month) ?>" required><br>
    <label for="input">日：</label>
    <input type="number" name="day" value="<?= h($day) ?>" required><br>
    <input type="submit" value="送信">
  </form>
</body>

</html>