<?php
session_start();

function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}

if (is_null($_SESSION["kotae"])) {
  $_SESSION["kotae"] = mt_rand(1, 99);
  $_SESSION["kaisuu"] = 0;
}

$input = isset($_GET["input"]) ? intval($_GET["input"]) : null;
$kotae = $_SESSION["kotae"];
$message = "";

if ($input !== null) {
  $_SESSION["kaisuu"]++;
  if ($input < $kotae) {
    $message = "いいえ。もっと大きいです。";
  } elseif ($input > $kotae) {
    $message = "いいえ。もっと小さいです。";
  } elseif ($input == $kotae) {
    $message = "正解です！ 回答回数: " . $_SESSION["kaisuu"] . "回";
    unset($_SESSION["kotae"]);
    unset($_SESSION["kaisuu"]);
  }
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>数あてゲーム</title>
</head>

<body>
  <h1>数あてゲーム</h1>
  <form method="get">
    <?php if (isset($message)): ?>
      <p><?= h($message) ?></p>
    <?php else: ?>
      <p>これからコンピューターが1～99の値を考えます。いくつを想像したのか当ててください。</p>
    <?php endif; ?>
    <br>
    <label for="input">数字を入力してください:</label>
    <input type="number" name="input" id="input" value="<?= h($input) ?>" required>
    <input type="submit" value="送信">
  </form>
</body>

</html>