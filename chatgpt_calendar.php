<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>カレンダー表示アプリ</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .calendar {
      width: 100%;
      max-width: 600px;
      margin: 20px auto;
      border-collapse: collapse;
    }

    .calendar th,
    .calendar td {
      padding: 10px;
      text-align: center;
      border: 1px solid #ccc;
    }

    .calendar th {
      background-color: #f2f2f2;
    }

    .sunday,
    .holiday {
      color: red;
    }

    .saturday {
      color: blue;
    }

    .saturday.holiday {
      color: red;
      /* 土曜日の祝日も赤くする */
    }

    .controls {
      text-align: center;
      margin-bottom: 20px;
    }

    .holiday-list {
      margin: 20px auto;
      max-width: 600px;
      font-size: 14px;
    }

    .holiday-list ul {
      padding: 0;
      list-style: none;
    }

    .holiday-list li {
      margin-bottom: 5px;
    }

    .gengou {
      font-weight: bold;
      font-size: 1.2em;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <div class="controls">
    <form method="GET">
      <label for="year">年（例：2024または令和4年）: </label>
      <input type="text" id="year" name="year" value="<?= isset($_GET['year']) ? htmlspecialchars($_GET['year']) : date('Y') ?>">
      <label for="month">月: </label>
      <input type="number" id="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('n') ?>" min="1" max="12">
      <button type="submit">カレンダー表示</button>
    </form>
  </div>

  <?php
  // 固定祝日リスト
  function getFixedHolidays($year)
  {
    return [
      "$year-01-01" => "元日",
      "$year-02-11" => "建国記念の日",
      "$year-04-29" => "昭和の日",
      "$year-05-03" => "憲法記念日",
      "$year-05-04" => "みどりの日",
      "$year-05-05" => "こどもの日",
      "$year-11-03" => "文化の日",
      "$year-11-23" => "勤労感謝の日"
    ];
  }

  // 動的祝日リスト（毎年日付が変わる祝日）
  function getDynamicHolidays($year)
  {
    $holidays = [];

    // 成人の日 (1月の第2月曜日)
    $holidays[date('Y-m-d', strtotime("second monday of January $year"))] = "成人の日";

    // 海の日 (7月の第3月曜日)
    $holidays[date('Y-m-d', strtotime("third monday of July $year"))] = "海の日";

    // 敬老の日 (9月の第3月曜日)
    $holidays[date('Y-m-d', strtotime("third monday of September $year"))] = "敬老の日";

    // 体育の日 (スポーツの日) (10月の第2月曜日)
    $holidays[date('Y-m-d', strtotime("second monday of October $year"))] = "スポーツの日";

    // 春分の日（3月20日または21日）
    $springEquinox = calculateSpringEquinox($year);
    $holidays[date("Y-m-d", strtotime("$year-03-$springEquinox"))] = "春分の日";

    // 秋分の日（9月22日または23日）
    $autumnEquinox = calculateAutumnEquinox($year);
    $holidays[date("Y-m-d", strtotime("$year-09-$autumnEquinox"))] = "秋分の日";

    return $holidays;
  }

  // 春分の日を計算 (参考: 日本国立天文台)
  function calculateSpringEquinox($year)
  {
    if ($year <= 2023) {
      return 20;
    } elseif ($year >= 2024 && $year <= 2050) {
      return ($year % 4 == 0) ? 20 : 21;
    }
    return 20;
  }

  // 秋分の日を計算 (参考: 日本国立天文台)
  function calculateAutumnEquinox($year)
  {
    if ($year <= 2023) {
      return 23;
    } elseif ($year >= 2024 && $year <= 2050) {
      return ($year % 4 == 0) ? 22 : 23;
    }
    return 23;
  }

  // 振替休日を計算する関数
  function calculateSubstituteHoliday($holidays, $year)
  {
    $substituteHolidays = [];
    foreach ($holidays as $date => $name) {
      $holidayDate = new DateTime($date);
      if ($holidayDate->format('w') == 0) { // 祝日が日曜日なら
        $substituteDate = $holidayDate->modify('+1 day')->format('Y-m-d');
        // 振替休日が祝日でなければ追加
        if (!array_key_exists($substituteDate, $holidays)) {
          $substituteHolidays[$substituteDate] = "振替休日";
        }
      }
    }

    // 特別なルール：5月の祝日が日曜日にかぶった場合の5月6日の振替休日処理
    if (
      isset($holidays["$year-05-03"]) && (new DateTime("$year-05-03"))->format('w') == 0 ||
      isset($holidays["$year-05-04"]) && (new DateTime("$year-05-04"))->format('w') == 0 ||
      isset($holidays["$year-05-05"]) && (new DateTime("$year-05-05"))->format('w') == 0
    ) {
      if ((new DateTime("$year-05-06"))->format('w') >= 1 && (new DateTime("$year-05-06"))->format('w') <= 5) {
        $substituteHolidays["$year-05-06"] = "振替休日";
      }
    }

    return $substituteHolidays;
  }

  // 元号を取得する関数（大化から現在まで対応）
  function getGengou($year)
  {
    if ($year >= 2019) {
      return "令和" . ($year - 2018) . "年";
    } elseif ($year >= 1989) {
      return "平成" . ($year - 1988) . "年";
    } elseif ($year >= 1926) {
      return "昭和" . ($year - 1925) . "年";
    } elseif ($year >= 1912) {
      return "大正" . ($year - 1911) . "年";
    } elseif ($year >= 1868) {
      return "明治" . ($year - 1867) . "年";
    } elseif ($year >= 645) {
      return "大化" . ($year - 644) . "年";
    } else {
      return "不明"; // 対応していない元号
    }
  }

  // 元号から西暦を計算する関数
  function convertGengouToWesternYear($gengou)
  {
    preg_match('/([^\d]+)(\d+)/u', $gengou, $matches);
    if (count($matches) === 3) {
      $era = $matches[1]; // 元号名
      $year = (int)$matches[2]; // 年号

      switch ($era) {
        case '令和':
          return 2018 + $year;
        case '平成':
          return 1988 + $year;
        case '昭和':
          return 1925 + $year;
        case '大正':
          return 1911 + $year;
        case '明治':
          return 1867 + $year;
        case '大化':
          return 644 + $year;
        default:
          return null; // 不明な元号
      }
    }
    return null; // 無効な入力
  }

  function generateCalendar($year, $month)
  {
    // 固定祝日と動的祝日を取得
    $fixedHolidays = getFixedHolidays($year);
    $dynamicHolidays = getDynamicHolidays($year);
    $holidays = array_merge($fixedHolidays, $dynamicHolidays);

    // 振替休日を計算して追加
    $substituteHolidays = calculateSubstituteHoliday($holidays, $year);
    $holidays = array_merge($holidays, $substituteHolidays);

    // 月初と月末の日付
    $firstDayOfMonth = new DateTime("$year-$month-01");
    $lastDayOfMonth = new DateTime($firstDayOfMonth->format('Y-m-t'));

    // 曜日の配列 (日月火水木金土)
    $weekdays = ['日', '月', '火', '水', '木', '金', '土'];

    // 元号を取得
    $gengou = getGengou($year);

    // カレンダーのテーブル開始
    echo '<div class="gengou">' . $gengou . '</div>'; // 元号の表示
    echo '<table class="calendar">';

    // ヘッダー行の作成
    echo '<tr>';
    foreach ($weekdays as $day) {
      echo "<th>$day</th>";
    }
    echo '</tr><tr>';

    // 最初の空のセルを追加
    $dayOfWeek = $firstDayOfMonth->format('w');
    for ($i = 0; $i < $dayOfWeek; $i++) {
      echo '<td></td>';
    }

    // 日付をカレンダーに追加
    for ($day = 1; $day <= $lastDayOfMonth->format('d'); $day++) {
      $currentDate = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
      $class = '';

      if (isset($holidays[$currentDate])) {
        $class = 'holiday';
      }

      // 土曜日と日曜日のクラス設定
      if ($firstDayOfMonth->format('w') == 6) {
        $class .= ' saturday'; // 土曜日
      } elseif ($firstDayOfMonth->format('w') == 0) {
        $class .= ' sunday'; // 日曜日
      }

      // 土曜日が祝日の場合は赤く表示
      if ($class === 'holiday saturday') {
        $class = 'holiday'; // 土曜日の祝日も赤く
      }

      echo "<td class='$class'>$day</td>";

      // 次の行に移動
      $firstDayOfMonth->modify('+1 day');
      if ($firstDayOfMonth->format('w') == 0) {
        echo '</tr><tr>'; // 日曜日の後に改行
      }
    }

    echo '</tr></table>';

    // 当月の祝日リストを表示
    echo '<div class="holiday-list"><h3>祝日一覧</h3><ul>';
    foreach ($holidays as $date => $name) {
      if (date('Y-m', strtotime($date)) == "$year-$month") { // 当月の祝日のみ表示
        echo "<li>" . date('Y年m月d日', strtotime($date)) . " - $name</li>";
      }
    }
    echo '</ul></div>';
  }

  // 年を入力した場合の処理
  if (isset($_GET['year'])) {
    $inputYear = trim($_GET['year']);
    // 元号入力の処理
    if (preg_match('/^(令和|平成|昭和|大正|明治|大化)(\d+)年$/u', $inputYear, $matches)) {
      $year = convertGengouToWesternYear($inputYear);
    } else {
      // 数字の場合
      $year = (int)$inputYear;
    }

    $month = (int)$_GET['month'];
    if ($year && $month >= 1 && $month <= 12) {
      generateCalendar($year, $month);
    } else {
      echo '<p>有効な年または月を入力してください。</p>';
    }
  }
  ?>
</body>

</html>