<?php
//試験2　カレンダーWebアプリ
function h($str)
{
  return htmlspecialchars($str);
}

function yearErr($year)
{
  return ($year < 1);
}

function monthErr($month)
{
  return ($month < 1 || 12 < $month);
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

function monthEnd($year, $month)
{
  if ($month == 2) {
    if (is_uruudosi($year)) {
      return 29;
    } else {
      return 28;
    }
  } elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
    return 30;
  } else {
    return 31;
  }
}

$errMsg1 = NULL;
$errMsg2 = NULL;
$dayErr = false;
$year = filter_input(INPUT_GET, "year", FILTER_VALIDATE_INT);
$month = filter_input(INPUT_GET, "month", FILTER_VALIDATE_INT);
if (yearErr($year)) {
  $errMsg1 = "年は正の整数で入力してください。";
  $dayErr = true;
}
if (monthErr($month)) {
  $errMsg2 = "月は 1～12 の範囲で入力してください。";
  $dayErr = true;
}

if (!$dayErr) {
  //当月1日の曜日を求める
  $weeks = ['日', '月', '火', '水', '木', '金', '土'];
  $yyyymmdd = sprintf('%04d', $year) . sprintf('%02d', $month) . "01";
  $youbiNum = date('w', strtotime($yyyymmdd));
  $youbi = $weeks[$youbiNum];
  $monthEnd = monthEnd($year, $month);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>カレンダー</title>
</head>

<body>
  <?php if (!$dayErr): ?>
    <?php $count = 1 ?>
    <p><?= $year ?>年 <?= $month ?>月 のカレンダー</p>
    <table>
      <tr>
        <?php foreach (range(1, 6) as $i): ?>
          <td><?= $weeks[$i] ?></td>
        <?php endforeach; ?>
        <td><?= $weeks[0] ?></td>
      </tr>
      <tr>
        <?php
        if ($youbiNum == 0) {
          $kuuhaku = 6;
          $w = 7;
        } else {
          $kuuhaku = $youbiNum - 1;
          $w = $youbiNum;
        }
        for ($i = 0; $i < $kuuhaku; $i++) {
          print("<td></td>");
        }
        do {
          print("<td> $count </td>");
          $count++;
          $w++;
        } while ($w <= 7);
        $w = 0;
        ?>
      </tr>
      <tr>
        <?php
        foreach (range(1, 6) as $j) {
          foreach (range(0, 6) as $i) {
            print("<td> $count </td>");
            $count++;
            if ($count > $monthEnd) {
              break 2;
            }
          }
          print("</tr><tr>");
        }
        ?>
      </tr>
    </table>
  <?php endif; ?>
  <br>
  <br>
  <form method="get">
    <label>年：</label><input type="number" name="year" value="<?= $year ?>"><br>
    <p><?= $errMsg1 ?></p>
    <label>月：</label><input type="number" name="month" value="<?= $month ?>"><br>
    <p><?= $errMsg2 ?></p>
    <input type="submit" value="実行"><br>
  </form>
</body>

</html>