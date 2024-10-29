<?php
//カレンダーWebアプリ
function h($str)
{
  return htmlspecialchars($str);
}

//年範囲チェック
function yearErr($year)
{
  return $year < 1;
}

//月範囲チェック
function monthErr($month)
{
  return $month < 1 || 12 < $month;
}

//うるう年判定
function is_uruudosi($year)
{
  return $year % 4 == 0 && $year % 100 != 0 || $year % 400 == 0;
}

//月初日の曜日を得る
function getFirstYoubiNum($year, $month)
{
  $yyyymmdd = sprintf('%04d', $year) . sprintf('%02d', $month) . "01";
  return date('w', strtotime($yyyymmdd));
}

//月末日を得る
function getLastDay($year, $month)
{
  $yyyymmdd = sprintf('%04d', $year) . sprintf('%02d', $month) . "01";
  return date('t', strtotime($yyyymmdd));
}

//3月の春分の日
function getShunbunDay($year)
{
  if ($year % 4 == 1) {
    return 21;
  }
  return 20;
}

//9月の秋分の日
function getShuubunDay($year)
{
  if ($year % 4 == 2 || $year % 4 == 3) {
    return 22;
  }
  return 23;
}

//第n月曜日の祝日
function holidayOfMonday($month)
{
  if ($month == 1)  return 2; //第2月曜日は成人の日
  if ($month == 7)  return 3; //第3月曜日は海の日
  if ($month == 9)  return 3; //第3月曜日は敬老の日
  if ($month == 10) return 2; //第2月曜日は体育の日
  return 0;
}

//その他の祝日
$holidays = [
  "0101" => "元日",
  "0211" => "建国記念の日",
  "0223" => "天皇誕生日",
  "0429" => "昭和の日",
  "0503" => "憲法記念日",
  "0504" => "みどりの日",
  "0505" => "こどもの日",
  "0811" => "山の日",
  "1103" => "文化の日",
  "1123" => "勤労感謝の日"
];

//曜日
$weeks = ['日', '月', '火', '水', '木', '金', '土'];

//入力チェック
$dayErr = false;
if (!(is_null($_GET["year"]) && is_null($_GET["month"]))) {
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
}

//当月1日の曜日と月末日を得る
if (!$dayErr) {
  $firstYoubiNum = getFirstYoubiNum($year, $month);
  $monthEnd = getLastDay($year, $month);
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>カレンダー</title>
  <style>
    td {
      text-align: right;
    }

    .red {
      color: red;
    }

    .blue {
      color: blue;
    }
  </style>
</head>

<body>
  <?php
  print("<p>$year 年 $month 月 のカレンダー</p>");
  if (!$dayErr && !(is_null($_GET["year"]) && is_null($_GET["month"]))) {
    print("<table>");
    print("<tr>");
    foreach (range(0, 6) as $i) {
      print("<td ");
      if ($i == 0) print(" class='red' ");
      if ($i == 6) print(" class='blue' ");
      print("> $weeks[$i] </td>");
    }
    print("</tr>");
    print("<tr>");
    $day = 1;
    $cntMonday = 0;
    $furikaeKyuujitu = false;
    for ($i = 0; $i < $firstYoubiNum; $i++) {
      print("<td></td>");
    }
    if ($firstYoubiNum <= 1) $cntMonday++;
    for ($i = $firstYoubiNum; $i < 7; $i++) {
      print("<td ");
      if ($i == 0) print(" class='red' ");
      if ($i == 6) print(" class='blue' ");
      if ($furikaeKyuujitu) {
        $furikaeKyuujitu = false;
        print(" style='color: red' ");
      }
      foreach ($holidays as $key => $val) {
        if (sprintf("%02d", $month) == substr($key, 0, 2) && sprintf("%02d", $day) == substr($key, -2)) {
          if ($i == 0) {
            $furikaeKyuujitu = true;
          } else {
            print(" style='color: red' ");
          }
        }
      }
      print("> $day </td>");
      $day++;
    }
    print("</tr>");
    foreach (range(1, 6) as $j) {
      print("<tr>");
      $cntMonday++;
      for ($i = 0; $i < 7; $i++) {
        print("<td ");
        if ($i == 0) print(" class='red' ");
        if ($i == 6) print(" class='blue' ");
        if ($furikaeKyuujitu) {
          $furikaeKyuujitu = false;
          print(" style='color: red' ");
        }
        foreach ($holidays as $key => $val) {
          if (sprintf("%02d", $month) == substr($key, 0, 2) && sprintf("%02d", $day) == substr($key, -2)) {
            if ($i == 0) {
              $furikaeKyuujitu = true;
            } else {
              print(" style='color: red' ");
            }
          }
        }
        if ($i == 1 && holidayOfMonday($month) == $cntMonday) {
          print(" style='color: red' ");
        }
        if ($month == 3 && $day == getShunbunDay($year)) {
          if ($i == 0) {
            $furikaeKyuujitu = true;
          } else {
            print(" style='color: red' ");
          }
        }
        if ($month == 9 && $day == getShuubunDay($year)) {
          if ($i == 0) {
            $furikaeKyuujitu = true;
          } else {
            print(" style='color: red' ");
          }
        }
        print("> $day </td>");
        $day++;
        if ($day > $monthEnd) {
          break 2;
        }
      }
    }
    print("</tr>");
    print("</table>");
  }
  ?>

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