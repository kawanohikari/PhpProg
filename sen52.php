<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}
function uruu($year)
{
  /*
4 で割れればうるう年である。１００年に１度、４で割れてもうるう年ではない。
４００年に１度、１００で割れてもうるう年。
*/
  if ($year % 4 != 0) {
    $msg = "うるう年ではありません。";
  } elseif ($year % 100 != 0) {
    $msg = "うるう年です。";
  } elseif ($year % 400 != 0) {
    $msg = "うるう年ではありません。";
  } else {
    $msg = "うるう年です。";
  }
  return $msg;
}
$year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <title>うるう年チェック</title>
</head>

<body>
  <h1>うるう年チェック</h1>
  <p>年を入力してください</p>
  <form method="get">
    <?php if (empty($year)): ?>
      <label>年:</label><input name="year" type="text" value="<?= $year ?>">
    <?php else: ?>
      <?= h($year) ?>年は<?= uruu(h($year)) ?>
    <?php endif; ?>
  </form>
</body>

</html>