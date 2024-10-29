<!DOCTYPE html>
<html lang="ja">

<?php
function h($str)
{
  return htmlspecialchars($str, ENT_QUOTES, "utf-8");
}

function eto($year)
{
  $etos = ["子", "丑", "寅", "卯", "辰", "巳", "午", "未", "申", "酉", "戌", "亥"];
  return $etos[($year - 4) % 12];
}

$year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
?>

<head>
  <meta charset="UTF-8">
  <title>干支計算機</title>
</head>

<body>
  <h1>干支計算機</h1>
  <?php if ($year < 4) : ?>
    <p>数字を入力してください</p>
    <form method="get">
      <label>年</label>
      <input type="number" name="year" value="<?= h(date("Y")) ?>">
    </form>
  <?php else: ?>
    <p><?= h($year) ?>年は<?= eto($year) ?>年です。</p>
  <?php endif; ?>
</body>

</html>