<?php
// ヒストグラムを作成する関数を取り込む
require_once 'histogram-lib.inc.php';

$ramen_type = [
	"miso" => "1 0",
	"sio"  => "0 1",
];

gen_data('', 40);
gen_data('-test', 14);

echo "ok\n";

function gen_data ($dir_type, $count) {
	// 画像を列挙
	$sio_list = glob("sio{$dir_type}/*.jpg");
	$miso_list = glob("miso{$dir_type}/*.jpg");
	// 偏りがないように50件ずつ学習データとする
	shuffle($sio_list);
	shuffle($miso_list);
	$sio_list = array_slice($sio_list, 0, $count);
	$miso_list = array_slice($miso_list, 0, $count);
	// 学習データを生成
	$count = count($sio_list) + count($miso_list);
	$data = "$count 64 2\n";
	$data .= gen_fann_data($sio_list, 'sio');
	$data .= gen_fann_data($miso_list, 'miso');
	file_put_contents("ramen{$dir_type}.dat", $data);
}

// データ生成
function gen_fann_data($list, $type) {
	global $ramen_type;
	$out = $ramen_type[$type];
	$data = '';
	foreach ($list as $f) {
		$his = make_histogram($f);
		$data .= implode(' ', $his)."\n";
		$data .= $out."\n";
	}
	return $data;
}