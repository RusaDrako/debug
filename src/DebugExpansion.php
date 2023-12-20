<?php

namespace RusaDrako\debug;

/**
 * View Типовые функции выводаБазовые настройки
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class DebugExpansion extends Debug {

	/** Пользовательское сообщение.
	 * @param int|string|array $data Переменная для печати.
	 * @param string $title Заголовок.
	 * @param bool $view Маркер "показывать в любом случае".
	 */
	public static function info($data, $title=false, $view=true){
		if($view){
			static::call()
				->addBacktrace(static::BACKTRACE_TYPE_1)
				->addDescription($data)
				->useStyle(static::STYLE_NOTE)
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
			static::call()
				->addBacktrace(static::BACKTRACE_TYPE_1)
				->useStyle(static::STYLE_NOTE)
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
			static::call()
				->addBacktrace(static::BACKTRACE_TYPE_1)
				->useStyle(static::STYLE_NOTE)
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
			$obj_array=new Visualization();
			static::call()
				->addBacktrace(static::BACKTRACE_TYPE_1)
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
			$obj_array=new Visualization();
			if(is_object($data)){
				$data=['OBJECT'=>$data];
			}
			static::call()
				->addBacktrace(static::BACKTRACE_TYPE_1)
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
			static::call()
				->useStyle($style)
				->addDescription($data)
				->addTitle($title)
				->showHTML();
		}
	}

/**/
}
