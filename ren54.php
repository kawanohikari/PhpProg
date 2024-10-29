<?php
// じゃんけん大会（１＝グー、２＝チョキ、３＝パー）

do {
  print("じゃんけん大会（１＝グー、２＝チョキ、３＝パー）\n");
  print("1～3 を入力してください\n\n");
  $user = trim(fgets(STDIN));
  $comp = mt_rand(1, 3);
} while (!($user == 1 || $user == 2 || $user == 3));

if ($user == 1) {
  print("あなたは 1.グー\n");
}
if ($user == 2) {
  print("あなたは 2.チョキー\n");
}
if ($user == 3) {
  print("あなたは 3.パー\n");
}
if ($comp == 1) {
  print("コンピューターは 1.グー\n");
}
if ($comp == 2) {
  print("コンピューターは 2.チョキー\n");
}
if ($comp == 3) {
  print("コンピューターは 3.パー\n");
}
print("\n");

if ($user == 1 && $comp == 1) {
  print("あいこです。");
}
if ($user == 1 && $comp == 2) {
  print("あなたの勝ちです。");
}
if ($user == 1 && $comp == 3) {
  print("コンピューターの勝ちです。");
}
if ($user == 2 && $comp == 1) {
  print("コンピューターの勝ちです。");
}
if ($user == 2 && $comp == 2) {
  print("あいこです。");
}
if ($user == 2 && $comp == 3) {
  print("あなたの勝ちです。");
}
if ($user == 3 && $comp == 1) {
  print("あなたの勝ちです。");
}
if ($user == 3 && $comp == 2) {
  print("コンピューターの勝ちです。");
}
if ($user == 3 && $comp == 3) {
  print("あいこです。");
}
print("\n");
print("\n");
