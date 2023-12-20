<?php

namespace RusaDrako\debug;

use RusaDrako\debug\view\ConsoleView;
use RusaDrako\debug\view\HTMLView;

/**
 * Debug
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Debug{

	/** @var string Тип вывода трасировки */
	const BACKTRACE_TYPE_1 = 1;
	const BACKTRACE_TYPE_2 = 2;

	/** @var string Стили отображения */
	const STYLE_NO      = 'no';
	const STYLE_NOTE    = 'note';
	const STYLE_OK      = 'ok';
	const STYLE_WARNING = 'warning';
	const STYLE_ERROR   = 'error';

	/** @var mixed Выводить backtrace */
	private $_htmlStyle = [
		self::STYLE_NO      => ['title' => '', 'bg' => ''],
		self::STYLE_NOTE    => ['title' => 'color: #080;', 'bg' => 'background: #ffd; color: #000;'],
		self::STYLE_OK      => ['title' => 'color: #080;', 'bg' => 'background: #dfd; color: #000;'],
		self::STYLE_WARNING => ['title' => 'color: #870;', 'bg' => 'background: #fea; color: #000;'],
		self::STYLE_ERROR   => ['title' => 'color: #800;', 'bg' => 'background: #fdd; color: #000;'],
		self::STYLE_ERROR   => ['title' => 'color: #800;', 'bg' => 'background: #fdd; color: #000;'],
	];
	/** @var mixed Выводить backtrace */
	private $_typeBacktrace;
	/** @var mixed Заголовок */
	private $_title;
	/** @var mixed Выводимые данные */
	private $_description;
	/** @var bool Выводить в стиле var_dump() */
	private $_isVarDump=false;
	/** @var bool Выводить данные как есть, без модификации */
	private $_isAsItIs=false;
	/** @var string Цвет фона */
	private $_styleBackground;
	/** @var string Цвет заголовка */
	private $_styleTitle;

	/** Объект модели */
	private static $_object=null;

	/**  */
	public function __construct(){
		# Базовая чистка
		$this->_clean();
	}

	/**  */
	public function __destruct(){}

	/** Вызов объекта класса
	 * @return object Объект модели
	 */
	public static function call(...$args){
		# Если объект отсутствует
		if(null===self::$_object){
			# Активируем объект
			self::$_object=new static(...$args);
		}
		# Возвращаем объект каласса
		return self::$_object;
	}

	/** Базова чистка настроек */
	private function _clean(){
		$this->_typeBacktrace = null;
		$this->_title         = null;
		$this->_description   = null;
		$this->_isVarDump     = null;
		$this->_isAsItIs      = null;
		$this->useStyle(static::STYLE_NOTE);
	}

	/** Рекурсивная сборка массива в строку
	 * @param string $glue Строка-склейка
	 * @param array $array Массив
	 */
	private function _implode_recursion($glue, $array){
		foreach($array as $k=>$v){
			if(is_array($v)){
				$array[$k]=$this->_implode_recursion($glue, $v);
			}
		}
		return implode($glue, $array);
	}

	/**
	 * @param string $styleName
	 * @return $this
	 */
	public function useStyle(string $styleName){
		if (!array_key_exists($styleName, $this->_htmlStyle)){
			$styleName=self::STYLE_NO;
		}
		$this->_styleTitle=$this->_htmlStyle[$styleName]['title'];
		$this->_styleBackground=$this->_htmlStyle[$styleName]['bg'];
		return $this;
	}

	/**
	 * @param string $styleName
	 * @param string $cssBackgrount
	 * @param string $cssTitle
	 * @return $this
	 */
	public function setStyle(string $styleName, string $cssBackgrount, string $cssTitle){
		$this->_htmlStyle[$styleName]['title'] = $cssTitle;
		$this->_htmlStyle[$styleName]['bg'] = $cssBackgrount;
		return $this;
	}

	/**
	 * Устанавливает заголовок сообщения
	 * @param $value
	 * @return $this
	 */
	public function addTitle($value){
		$this->_title=$value;
		return $this;
	}

	/**
	 * Устанавливает тип вывода backtrace
	 * @param $type
	 * @return $this
	 */
	public function addBacktrace($type){
		$this->_typeBacktrace=$type;
		return $this;
	}

	/**
	 * Устанавливает данные для вывода
	 * @param $value
	 * @return $this
	 */
	public function addDescription($value){
		$this->_description=$value;
		return $this;
	}

	/**
	 * Устанавливает маркер "Отображать как есть"
	 * @param false $bool
	 * @return $this
	 */
	public function isAsItIs($bool=false){
		$this->_isAsItIs=$bool;
		return $this;
	}

	/**
	 * Устанавливает маркер "Выводить в стиле var_dump()"
	 * @param false $bool
	 * @return $this
	 */
	public function isVarDump($bool=false){
		$this->_isVarDump=$bool;
		return $this;
	}

	/**
	 * Ваводит сообщение в стиле HTML
	 */
	public function showHTML(){
		$classView = new HTMLView();
		$classView->setTitle($this->_title ?: 'Не указано');
		$classView->setTitleStyle($this->_styleTitle);
		$classView->setBacktraceStyle($this->_styleBackground);
		$classView->setBacktrace($this->_printBacktrace());
		$classView->setDescription($this->_viewDescription());
		$classView->getView();
		$this->_clean();
	}

	/**
	 * Ваводит сообщение в стиле Консоль
	 */
	public function showConsole(){
		$classView = new ConsoleView();
		$classView->setTitle($this->_title ?: 'Не указано');
		$classView->setBacktrace($this->_printBacktraceApp());
		$classView->setDescription($this->_viewDescription());
		$classView->getView();
		$this->_clean();
	}

	/** Блок информации по "цепочке вызова" */
	private function _printBacktrace(){
		$content=[];
		$btClass = new Backtrace();
		switch($this->_typeBacktrace){
			case static::BACKTRACE_TYPE_1:
				$btClass->template=<<<HTML
<b>:file: (:line:):</b> => :function:
HTML;
				$content[]=implode($btClass->viewBacktrace(), '<br>');
				break;
			case static::BACKTRACE_TYPE_2:
				$btClass->template=<<<HTML
<b>Папка:</b>   :dir:
<b>Файл:</b>    :fileName:
<b>Строка:</b>  :line:
<b>Функция:</b> :function:
HTML;
				$content[]=implode($btClass->viewBacktrace(), '<hr>');
				break;
			default:
				$content=null;
				break;
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" */
	private function _printBacktraceApp(){
		$content=[];
		$btClass = new Backtrace();
		switch($this->_typeBacktrace){
			case static::BACKTRACE_TYPE_1:
				$btClass->template=<<<HTML
:file: (:line:): => :function:
HTML;
				$content[]=$btClass->viewBacktrace();
				break;
			case static::BACKTRACE_TYPE_2:
				$btClass->template=<<<HTML
Папка:  :dir:
Файл:   :fileName:
Строка: :line:
HTML;
				$content[]=$btClass->viewBacktrace();
				break;
			default:
				$content=null;
				break;
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Вывод содержимого */
	public function _viewDescription(){
		$content=[];
		$value=$this->_description;
		# Выводим основной блок информации
		if($this->_isVarDump){
//			$content[] = var_export($value, true);
			ob_start();
			var_dump($value);
			$content[]=ob_get_contents();
			ob_end_clean();
		} else if(is_string($value)){
			if(true!=$this->_isAsItIs){
				$value=str_replace('<', '&#60;', $value);
				$value=str_replace('<', '&#62;', $value);
			}
			$content[]=print_r($value, true);
		}else{
			$content[]=print_r($value, true);
		}
		# Возвращаем содержимое
		return $content;
	}

/**/
}
