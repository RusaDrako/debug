<?php

namespace RusaDrako\debug;

/**
 * View Типовые функции выводаБазовые настройки
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Show {

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function info($data, $title=false, $view=true){
		if($view){
			Debug::call()
				->addBacktrace(1)
				->addDescription($data)
				->useStyle(Debug::STYLE_NOTE)
				->addTitle($title)
				->showHTML();
		}
	}

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function info_app($data, $title=false, $view=true){
		if($view){
			Debug::call()
				->addBacktrace(1)
				->useStyle(Debug::STYLE_NOTE)
				->addDescription($data)
				->addTitle($title)
				->showConsole();
		}
	}

	/** Пользовательское сообщение.
	 * @param int|string|array $data Данные для вывода.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function dump($data, $title=false, $view=true){
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

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function table($data, $title=false, $view=true){
		if($view){
			$obj_array=new ArrayView();
			Debug::call()
				->addBacktrace(1)
				->isAsItIs(true)
				->addDescription($obj_array->print_table_2d_array($data))
				->addTitle($title)
				->showHTML();
		}
	}

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function tree($data, $title=false, $view=true){
		if($view){
			$obj_array=new ArrayView();
			Debug::call()
				->addBacktrace(1)
				->isAsItIs(true)
				->addDescription($obj_array->print_table_tree_array($data))
				->addTitle($title)
				->showHTML();
		}
	}

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function object($data, $title=false, $view=true){
		if($view){
			$obj_array=new ArrayView();
			$data=json_decode(json_encode($data), true);
			Debug::call()
				->addBacktrace(1)
				->isAsItIs(true)
				->addDescription($obj_array->print_table_tree_array($data))
				->addTitle($title)
				->showHTML();
		}
	}

	/** Пользовательское сообщение - лог.
	 * @param int|string|array $data Переменная для печати.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function style($style, $data, $title=false, $view=true){
		if($view){
			Debug::call()
				->useStyle($style)
				->addDescription($data)
				->addTitle($title)
				->showHTML();
		}
	}

/**/
}
