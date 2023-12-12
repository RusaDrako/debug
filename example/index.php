<?php

use RusaDrako\debug\ArrayView;
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
	$arrTable = [
		['column_1'=>'test 1', 'column_2' => 1,],
		['column_2'=>'test 2', 'column_3' => 2,],
		['column_1'=>'test 3', 'column_2' => 3,],
	];
	$arrTree = [
		'key_1_1'=>'test 1 1',
		'key_1_2'=>[
			'key_2_1'=>'test 2 1',
			'key_2_2'=>'test 2 2',
			'key_2_3'=>[
				'key_3_1'=>'test 3 1',
				'key_3_2'=>'test 3 2',
				'key_3_3'=>'test 3 3',
				'key_3_4'=>NULL,
				'key_3_5'=>false,
				'key_3_6'=>true,
			],
			'key_2_4'=>'test 2 4',
		]
	];
	print_info('print_info');
	print_info($arr, 'print_info');
	echo '<pre>';
	print_info_app($arr, 'print_info_app');
	echo '</pre>';
	print_dump($arr, 'print_dump');
	print_table($arrTable, 'print_table');
	print_tree($arrTree, 'print_tree');
	print_style(Debug::STYLE_NO, $arr, 'print_style');

	$objArray = new ArrayView();
	echo '<hr>';
	echo $objArray->print_table_2d_array($arrTable);
	echo '<hr>';
	echo $objArray->print_table_tree_array($arrTable);
	echo '<hr>';
	echo $objArray->print_table_tree_array($arrTree);
	echo '<hr>';

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