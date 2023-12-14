<?php

use RusaDrako\debug\ArrayView;
use RusaDrako\debug\Debug;

if (!function_exists('print_info')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info($data, $title=false, $view=true){
		if($view){
			Debug::call()
				->useStyle(Debug::STYLE_NOTE)
				->addTitle($title)
				->addBacktrace(1)
				->addDescription($data)
				->showHTML();
		}
	}
}

if (!function_exists('print_info_app')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info_app($data, $title=false, $view=true){
		if($view){
			Debug::call()
				->useStyle(Debug::STYLE_NOTE)
				->addTitle($title)
				->addBacktrace(1)
				->addDescription($data)
				->showConsole();
		}
	}
}

if (!function_exists('print_dump')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Данные для вывода.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_dump($data, $title=false, $view=true){
		if($view){
			Debug::call()
				->useStyle(Debug::STYLE_NOTE)
				->addTitle($title)
				->addBacktrace(1)
				->addDescription($data)
				->isVarDump(true)
				->showHTML();
		}
	}
}

if (!function_exists('print_table')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_table($data, $title=false, $view=true){
		if($view){
			$obj_array=new ArrayView();
			Debug::call()
				->addTitle($title)
				->addBacktrace(1)
				->addDescription($obj_array->print_table_2d_array($data))
				->isAsItIs(true)
				->showHTML();
		}
	}
}

if (!function_exists('print_tree')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_tree($data, $title=false, $view=true){
		if($view){
			$obj_array=new ArrayView();
			if(is_object($data)){
				$data=['OBJECT'=>$data];
			}
			Debug::call()
				->addTitle($title)
				->addBacktrace(1)
				->addDescription($obj_array->print_table_tree_array($data))
				->isAsItIs(true)
				->showHTML();
		}
	}
}

if (!function_exists('print_style')){
	/** Пользовательское сообщение - лог.
	 * @param int|string|array $data Переменная для печати.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_style($style, $data, $title=false, $view=true){
		if($view){
			Debug::call()
				->useStyle($style)
				->addTitle($title)
				->addDescription($data)
				->showHTML();
		}
	}
}
