<?php
// 変数設定
$win = $_GET["win"] ?? 0;
$draw = $_GET["draw"] ?? 0;
$loss = $_GET["loss"] ?? 0;
$player1 = $_GET["janken"] ?? null;
$result = "";

// XSS対策
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

// プレイヤーの選択
function playerChoice($player1, &$win, &$draw, &$loss)
{
    $player2 = rand(1, 3);
    echo "p1: " . $player1 . "\n";
    echo "p2: " . $player2 . "\n";

    return determineWinner($player1, $player2, $win, $draw, $loss);
}

// 勝敗を判定する関数
function determineWinner($player1, $player2, &$win, &$draw, &$loss)
{
    echo "win: " . $win . "\n";
    echo "draw: " . $draw . "\n";
    echo "loss: " . $loss . "\n";

    if ($player1 != 0) {
        if ($player1 == $player2) {
            $draw++;
            echo "引き分け回数：" . $draw . "\n";
            return "引き分けです！";
        } elseif (
            ($player1 == 1 && $player2 == 2) || // ぐーがちょきに勝つ
            ($player1 == 2 && $player2 == 3) || // ちょきがぱーに勝つ
            ($player1 == 3 && $player2 == 1)    // ぱーがぐーに勝つ
        ) {
            $win++;
            echo "勝った回数：" . $win . "\n";
            return "あなたの勝ちです！";
        } else {
            $loss++;
            echo "負けた回数：" . $loss . "\n";
            return "ＰＣの勝ちです！";
        }
    }
}

if ($player1 !== null) {
    $result = playerChoice($player1, $win, $draw, $loss);
    // クエリパラメータを使ってリダイレクト
    header("Location: " . $_SERVER['PHP_SELF'] . "?win=$win&draw=$draw&loss=$loss&result=" . urlencode($result));
    exit();
}

$result = $_GET["result"] ?? "";
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>じゃんけん大会</title>
    <style>
        input.cnt {
            width: 40px;
        }
    </style>
</head>

<body>
    <div class="wrap">
        <h1>★じゃんけん大会★</h1>
        <form method="get">
            <label><input type="radio" name="janken" value="1" checked>1:グー</label>
            <label><input type="radio" name="janken" value="2">2:チョキ</label>
            <label><input type="radio" name="janken" value="3">3:パー</label>
            <input type="submit" value="勝負！">
        </form>
        <div>
            勝ち：<input type="text" name="win" value="<?= h($win) ?>" class="cnt" readonly>
            負け：<input type="text" name="loss" value="<?= h($loss) ?>" class="cnt" readonly>
            引き分け：<input type="text" name="draw" value="<?= h($draw) ?>" class="cnt" readonly>
        </div>
        <p class="result"><?= h($result) ?></p>
    </div>
</body>

</html>