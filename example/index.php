<?php

use RusaDrako\debug\Debug;

require_once('../src/autoload.php');

class c1 {
	function m1() {
		$this->m2();
	}
	function m2() {
		f1();
	}
}

function f1() {
	$arr = ['arr_1'=>'ddddd', 'arr_2'=>12344];
	print_info('print_info');
	print_info($arr, 'print_info');
	echo '<pre>';
	print_info_app($arr, 'print_info_app');
	echo '</pre>';
	print_dump($arr, 'print_dump');
	print_table(
		[
			['column_1'=>'test 1', 'column_2' => 1],
			['column_2'=>'test 2', 'column_3' => 2],
			['column_1'=>'test 3', 'column_2' => 3],
		], 'print_table');
	print_tree(
		[
			'arr_1_1'=>'test 1 1',
			'arr_1_2'=>[
				'arr_2_1'=>'test 2 1',
				'arr_2_2'=>[
					'arr_3_1'=>'test 3 1',
					'arr_3_2'=>'test 3 2',
					'arr_3_3'=>'test 3 3'
				]]], 'print_tree');
	print_style(Debug::STYLE_NO, $arr, 'print_style');

	$arrStyle = [
		Debug::STYLE_NO,
		Debug::STYLE_NOTE,
		Debug::STYLE_OK,
		Debug::STYLE_WARNING,
		Debug::STYLE_ERROR,
	];
	foreach($arrStyle as $v){
		Debug::call()
			->addBacktrace(2)
			->useStyle($v)
			->addDescription($arr)
			->addTitle("style: {$v}")
			->showHTML();
	}
}

$class = new c1();
$class->m1();