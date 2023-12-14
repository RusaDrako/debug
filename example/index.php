<?php

namespace RusaDrako\test;


use RusaDrako\debug\ArrayView;
use RusaDrako\debug\Debug;

require_once('../src/autoload.php');

class ct1 {
	const CONST_1_1 = 'Константа 1 1';
	const CONST_1_2 = 'Константа 1 2';
	const CONST_1_3 = 'Константа 1 3';

	static public $paramStat_1_1 = 'Параметр статический 1 1';
	static public $paramStat_1_2 = 'Параметр статический 1 2';
	static public $paramStat_1_3 = 'Параметр статический 1 3';

	public $param_1_1 = 'Параметр 1 1';
	public $param_1_2 = 'Параметр 1 2';
	public $param_1_3 = 'Параметр 1 3';

	public $paramObj;

	public function __construct($recursion = null){
		$obj = $recursion ? $this : '---';
		$this->paramObj = new ct2($obj);
	}
}

class ct2 {
	const CONST_2_1 = 'Константа 2 1';
	const CONST_2_2 = 'Константа 2 2';
	const CONST_2_3 = 'Константа 2 3';

	static public $paramStat_2_1 = 'Параметр статический 2 1';
	static public $paramStat_2_2 = 'Параметр статический 2 2';
	static public $paramStat_2_3 = 'Параметр статический 2 3';

	public $param_2_1 = 'Параметр 2 1';
	public $param_2_2 = 'Параметр 2 2';
	public $param_2_3 = 'Параметр 2 3';

	public $parent;

	public function __construct($parent){
		$this->parent = $parent;
	}
}

class c1 {
	function m1() {
		$this->m2();
	}
	function m2() {
		f1();
	}
}

function f1() {

	$arrData = [
		'string' => 'Test string',
		'array' => ['arr_1'=>'ddddd', 'arr_2'=>12344],
		'object' => new ct1(),
		'object_recursion' => new ct1(1),
	];

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
				'key_3_7'=>new ct1(1),
			],
			'key_2_4'=>'test 2 4',
		]
	];

	foreach($arrData as $k=>$v) {
		print_info($v, "print_info ({$k})");
	}

	echo '<hr>';

	echo '<pre>';
	foreach($arrData as $k=>$v) {
		print_info_app($v, "print_info_app ({$k})");
	}
	echo '</pre>';

	echo '<hr>';

	foreach($arrData as $k=>$v) {
		print_dump($v, "print_dump ({$k})");
	}

	echo '<hr>';

	print_table($arrTable, 'print_table');
	print_tree($arrTree, 'print_tree');
	print_tree(new ct1(), 'print_tree (object)');
	print_tree(new ct1(1), 'print_tree (object_recursion)');

	echo '<hr>';

	$arrStyle = [
		Debug::STYLE_NO,
		Debug::STYLE_NOTE,
		Debug::STYLE_OK,
		Debug::STYLE_WARNING,
		Debug::STYLE_ERROR,
	];
	foreach($arrStyle as $v){
		print_style($v, $arrData['array'], "print_style ({$v})");
	}

	$objArray = new ArrayView();
	echo '<hr>';
	echo $objArray->print_table_2d_array($arrTable);
	echo '<hr>';
	echo $objArray->print_table_tree_array($arrTable);
	echo '<hr>';
	echo $objArray->print_table_tree_array($arrTree);
	echo '<hr>';
}

$class = new c1();
$class->m1();