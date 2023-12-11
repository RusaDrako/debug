<?php

use RusaDrako\debug\Debug;

if (!function_exists('print_info_test')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info_test($data, $title=false, $view=false){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(2)
				->addDescription($data)
				->addTitle($title)
				->printForm();
		}
	}
}

if (!function_exists('print_info')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info($data, $title=false, $view=false){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->addDescription($data)
				->addTitle($title)
				->printForm();
		}
	}
}

if (!function_exists('print_info_app')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_info_app($data, $title=false, $view=false){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->addDescription($data)
				->addTitle($title)
				->printApp();
		}
	}
}

if (!function_exists('print_dump')){
	/** Пользовательское сообщение.
	 * @param int|string|array $data Данные для вывода.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_dump($data, $title=false, $view=false){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->addBacktrace(1)
				->var_dump(true)
				->addDescription($data)
				->addTitle($title)
				->printForm();
		}
	}
}

//if (!function_exists('print_table')){
//	/** Пользовательское сообщение.
//	 * @param int|string|array $data Переменная для печати.
//	 * @param string $title Заголовок.
//	 * @param bool $view Маркер "показывать в любом случае".
//	 */
//	function print_table($data, $title=false, $view=false){
//		# Отрабатывать только в тестовом режиме
//		if($view){
//			$obj_array=new \array_class();
//			Debug::call()
//				->addBacktrace(1)
//				->viewAsItIs(true)
//				->addDescription($obj_array->print_table_2d_array($data))
//				->addTitle($title)
//				->printForm();
//		}
//	}
//}
//
//if (!function_exists('print_tree')){
//	/** Пользовательское сообщение.
//	 * @param int|string|array $data Переменная для печати.
//	 * @param string $title Заголовок.
//	 * @param bool $view Маркер "показывать в любом случае".
//	 */
//	function print_tree($data, $title=false, $view=false){
//		# Отрабатывать только в тестовом режиме
//		if($view){
//			$obj_array=new \array_class();
//			Debug::call()
//				->addBacktrace(1)
//				->viewAsItIs(true)
//				->addDescription($obj_array->print_table_tree_array($data))
//				->addTitle($title)
//				->printForm();
//		}
//	}
//}

if (!function_exists('print_log')){
	/** Пользовательское сообщение - лог.
	 * @param int|string|array $data Переменная для печати.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_log($data, $view=false){
		# Отрабатывать только в тестовом режиме
		if($view){
			Debug::call()
				->setTitleColor('#008')
				->setBackgroundColor('#eef')
				->addDescription($data)
				->printForm();
		}
	}
}

if (!function_exists('print_error')){
	/** Пользовательское сообщение - ошибка.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	function print_error($data, $title=false, $view=false){
		if($view){
			Debug::call()
				->setTitleColor('#800')
				->setBackgroundColor('#faa')
				->addDescription($data)
				->addTitle($title)
				->printForm();
		}
	}
}
