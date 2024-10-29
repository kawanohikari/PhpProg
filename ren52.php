<!-- うるう年チェックをせよ。
年を入力し、その年がうるう年であればうるう年、そうでなければうるう年でないと表示せよ。 -->
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

function is_uruudosi($year)
{
  if ($year % 4 == 0) {
    if ($year % 100 == 0) {
      if ($year % 400 == 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return true;
    }
  } else {
    return false;
  }
}

function nengou($year, &$nengou_year)
{
  if (1868 <= $year && $year < 1912) {
    $nengou_year = $year - 1867;
    return "明治";
  } elseif ($year < 1926) {
    $nengou_year = $year - 1911;
    return "大正";
  } elseif ($year < 1989) {
    $nengou_year = $year - 1925;
    return "昭和";
  } elseif ($year < 2019) {
    $nengou_year = $year - 1988;
    return "平成";
  } elseif (2019 <= $year) {
    $nengou_year = $year - 2018;
    return "令和";
  }
  return "";
}

$year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
?>

<head>
  <meta charset="UTF-8">
  <title>うるう年計算機</title>
</head>

<body>
  <h1>うるう年計算機</h1>
  <?php if ($year <= 0) : ?>
    <p>数字を入力してください</p>
    <form method="get">
      <label>年</label>
      <input type="number" name="year" value="<?= h(date("Y")) ?>">
    </form>
  <?php else: ?>
    <?php if (is_uruudosi($year)) : ?>
      <p><?= h($year) ?>年は、うるう年です。</p>
    <?php else : ?>
      <p><?= h($year) ?>年は、うるう年ではありません。</p>
    <?php endif; ?>
    <p><?= nengou($year, $nengou_year) . $nengou_year ?>年　<?= eto($year) ?>年です。</p>
  <?php endif; ?>
</body>

</html>