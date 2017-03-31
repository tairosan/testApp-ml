<?php
// FANNを生成
$num_layers = 3;
$num_input = 64;
$num_neuros_hidden = 3;
$num_output = 2;
$ann = fann_create_standard(
  $num_layers, $num_input, 
  $num_neuros_hidden, $num_output);
if (!$ann) { die("FANNの初期化に失敗"); }

// パラメータを設定
fann_set_activation_function_hidden($ann, FANN_SIGMOID_SYMMETRIC);
fann_set_activation_function_output($ann, FANN_SIGMOID_SYMMETRIC);

// 学習する
echo "学習します\n";
$desired_error = 0.0001;
$max_epochs = 500000;
$epochs_between_reports = 1000;
fann_train_on_file($ann, "ramen.dat",
  $max_epochs, $epochs_between_reports, $desired_error);
fann_save($ann, 'ramen.net');

// 学習データをテスト
echo "テストします\n";
$ramen_data = [
  "1 0" => "miso",
  "0 1" => "sio",
];
$ramen_index = ["miso", "sio"];
$testdata = explode("\n", file_get_contents("ramen-test.dat"));
array_shift($testdata);
$total = $ok = 0;
while($testdata) {
  $s = array_shift($testdata);
  if ($s == "") continue;
  $data = explode(" ", $s);
  $label = array_shift($testdata);
  $label_desc = $ramen_data[$label];
  $r = fann_run($ann, $data);
  $v = $ramen_index[array_max_index($r)];
  echo "- $label_desc = $v\n";
  if ($label_desc == $v) $ok++;
  $total++;  
}
$per = floor($ok / $total * 100);
echo "結果: $ok/$total = $per%\n";

// 配列の中で最も高い数値を持つインデックスを返す
function array_max_index($a) {
  $mv = -1; $mi = -1;
  foreach ($a as $i => $v) {
    if ($mv < $v) {
      $mv = $v; $mi = $i;
    }
  }
  return $mi;
}


