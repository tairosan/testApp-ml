<?php
// 画像からカラーヒストグラムを計算する関数
function make_histogram ($path, $debug = TRUE) {
	if ($debug) { echo "histogram: $path\n"; }
	$im_big = imagecreatefromjpeg($path);
	$sx_big = imagesx($im_big);
	$sy_big = imagesy($im_big);
	// 高速化のために縮小する
	$sx = 256; $sy = 192;
	$im = imagecreatetruecolor($sx, $sy);
	imagecopyresampled($im, $im_big, 0, 0, 0, 0, 
		$sx, $sy, $sx_big, $sy_big);
	// ピクセルを数える
	$his = array_fill(0, 64, 0);
	for ($y = 0; $y < $sy; $y++) {
		for ($x = 0; $x < $sx; $x++) {
			$rgb = imagecolorat($im, $x, $y);
			$no = rgb2no($rgb);
			$his[$no]++;
		}
	}
	// 正規化
	$pixels = $sx * $sy;
	for ($i = 0; $i < 64; $i++) {
		$his[$i] = $his[$i] / $pixels;
	}
	imagedestroy($im_big);
	imagedestroy($im);
	return $his;
}

// ヒストグラムのピンを計算する関数
function rgb2no($rgb) {
	$r = ($rgb >> 16) & 0xFF;
	$g = ($rgb >> 8) & 0xFF;
	$b = $rgb & 0xFF;
	$rn = floor($r / 64);
	$gn = floor($g / 64);
	$bn = floor($b / 64);
	return 16 * $rn + 4 * $gn +$bn;
}