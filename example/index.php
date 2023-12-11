<?php
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
	print_info_test($arr, 'print_info_test', 1);
	print_info($arr, 'print_info', 1);
	echo '<pre>';
	print_info_app($arr, 'print_info_app', 1);
	echo '</pre>';
	print_dump($arr, 'print_dump', 1);
	/*print_table(
		[
			['column_1'=>'test 1', 'column_2' => 1],
			['column_2'=>'test 2', 'column_3' => 2],
			['column_1'=>'test 3', 'column_2' => 3],
		], 'print_table', 1);*/
	/*print_tree(
		[
			'arr_1_1'=>'test 1 1',
			'arr_1_2'=>[
				'arr_2_1'=>'test 2 1',
				'arr_2_2'=>[
					'arr_3_1'=>'test 3 1',
					'arr_3_2'=>'test 3 2',
					'arr_3_3'=>'test 3 3'
				]]], 'print_tree', 1);*/
	print_log($arr, 1);
	print_error($arr, 'print_error', 1);

}

$class = new c1();
$class->m1();