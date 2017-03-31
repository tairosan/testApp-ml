<?php
require_once 'phpflickr/phpFlickr.php';

define('API_KEY', '1e52c72d7e1f6f4ca795925484581df4');
define('API_SECRET', '83c1b83407a14c34');

download_flickr('塩ラーメン', 'sio');
download_flickr('味噌ラーメン', 'miso');

function download_flickr ($keyword, $dir) {
	if (!file_exists($dir)) mkdir($dir);

	$flickr = new phpFlickr(API_KEY, API_SECRET);

	$search_opt = [
		'text' => $keyword,
		'media' => 'photos',
		'license' => '4,5,6,7,8',
		'per_page' => 200,
		'sort' => 'relevant'
	];

	$result = $flickr->photos_search($search_opt);

	if(!$result) die("Flickr API error");

	foreach ($result['photo'] as $photo) {
		// 情報から写真のURLを構築する
		$farm = $photo['farm'];
		$server = $photo['server'];
		$id = $photo['id'];
		$secret = $photo['secret'];
		$url = "http://farm{$farm}.staticflickr.com/{$server}/{$id}_{$secret}.jpg";

		echo "get $id : $url\n";
		$savepath = "./$dir/$id.jpg";
		if (file_exists($savepath)) continue;

		// ダウンロードと保存
		$bin = file_get_contents($url);
		file_put_contents($savepath, $bin);
	}
}