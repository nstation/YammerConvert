<?php

$CHANNEL = 'チャンネル名'; // インポートするチャンネル名
$IMPORT = 'Import';		// 入力用ディレクトリ
$EXPORT = 'Export';		// 出力用ディレクトリ

define("SENDER_EMAIL",	13);
define("BODY",			14);
define("CREAED_AT",		20);

$d = glob('./' . $IMPORT . '/*.csv');

echo 'File: ' . count($d) . '<br /><br />';

foreach ($d as $fn) {
	$f = new SplFileObject($fn);
	$f->setFlags(
		SplFileObject::READ_CSV |
		SplFileObject::READ_AHEAD |
		SplFileObject::SKIP_EMPTY
	);

	$csv = '';
	$name = '';

	$i = 0;
	foreach ($f as $v) {
		if ($i == 1) {	// 最初の有効データを使う
			$name = strstr($v[SENDER_EMAIL], '@', true);	// メールアドレスのアカウント名
			$w = './' . $EXPORT . '/' . $name . '.csv';
		}
		if ($i != 0) {	// 最初の行(ヘッダー)以外は処理
			$csv .= '"' . strtotime($v[CREAED_AT]) . '","' . $CHANNEL . '","' . $name . '","' . $v[BODY] . '"' . "\n";
		}
		$i++;
	}
	echo $fn . ' => ' . $w . ' ( ' . number_format($i) . ' )<br />';
	file_put_contents($w, $csv);
}