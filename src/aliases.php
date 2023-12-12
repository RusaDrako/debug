<?php

use RusaDrako\debug\Debug;

if (!function_exists('print_info')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info($data, $title=false, $view=true){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->addDescription($data)
				->useStyle(Debug::STYLE_NOTE)
				->addTitle($title)
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
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->useStyle(Debug::STYLE_NOTE)
				->addDescription($data)
				->addTitle($title)
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
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->useStyle(Debug::STYLE_NOTE)
				->isVarDump(true)
				->addDescription($data)
				->addTitle($title)
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
		# Отрабатывать только в тестовом режиме
		if($view){
			$obj_array=new \RusaDrako\debug\ArrayView();
			Debug::call()
				->addBacktrace(1)
				->isAsItIs(true)
				->addDescription($obj_array->print_table_2d_array($data))
				->addTitle($title)
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
		# Отрабатывать только в тестовом режиме
		if($view){
			$obj_array=new \RusaDrako\debug\ArrayView();
			Debug::call()
				->addBacktrace(1)
				->isAsItIs(true)
				->addDescription($obj_array->print_table_tree_array($data))
				->addTitle($title)
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
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->useStyle($style)
				->addDescription($data)
				->addTitle($title)
				->showHTML();
		}
	}
}
