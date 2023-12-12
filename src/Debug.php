<?php

namespace RusaDrako\debug;

/**
 * Debug
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Debug{
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
		$this->_typeBacktrace=null;
		$this->_title=null;
		$this->_description=null;
		$this->_isVarDump=null;
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
		$content=[];
		# Рандомный ключ для checkbox
		$key_rand=rand(1000000, 9999999);
		# Стиль для "всплывающего div"
		$content[]='<style>
	.block_print_info .block_print_info_show{ display: none;}
	.block_print_info input[type=checkbox]:checked + .block_print_info_show { display: block;}
</style>';
		# Открываем тэг вывода (без обработки текста браузером)
		$content[]='<pre class="block_print_info" style="display: block; position: relative; min-height: 20px; color: #000; border: 2px dashed #000; padding: 10px 20px; margin: 10px 15px; font-size: 12px;'.$this->_styleBackground.'">';
		$content[]=$this->_printTitle($key_rand);
		# Выводим скрытый input
		$content[]='<input id="debug_print_'.$key_rand.'" class="input_print_info" type="checkbox" style="display: none;">';
		# Открываем "всплывающий div"
		$content[]='<div class="block_print_info_show">';
		$content[]=$this->_printBacktrace();
		# Добавляем контент
		$content[]=$this->_viewDescription();
		# Закрываем "всплывающий div"
		$content[]='</div>'."\n";
		# Закрываем тэг вывода
		$content[]='</pre>'."\n";
		# Выводим блок
		echo $this->_implode_recursion('', $content);
		$this->_clean();
	}

	/**
	 * Ваводит сообщение в стиле Консоль
	 */
	public function showConsole(){
		$content=[];
		# Открываем тэг вывода (без обработки текста браузером)
		$content[]='================================================================================';
		$content[]=$this->_printTitleApp();
		$content[]=$this->_printBacktraceApp();
		$content[]='================================================================================';
		# Добавляем контент
		$content[]=$this->_viewDescription();
		$content[]="================================================================================\n";
		# Выводим блок
		echo $this->_implode_recursion("\n", $content);
		$this->_clean();
	}

	/** */
	private function _printTitle($key_rand){
		# Если заголовок не задан
		if(!$this->_title){
			# Заголовок по умолчанию
			$_title='Не указано';
		}else{
			$_title=$this->_title;
		}
		# Выводим заголовок
		$content='<label for="debug_print_'.$key_rand.'"><span style="font-size: 120%;'.$this->_styleTitle.'"><b>&#9660; '.$_title." &#9660;</b></span></label>";
		# Возвращаем содержимое
		return $content;
	}

	/** */
	private function _printTitleApp(){
		# Выводим заголовок
		$content=($this->_title ?: 'Не указано')."\n";
		# Возвращаем содержимое
		return $content;
	}

	/** Блок информации по "цепочке вызова" */
	private function _printBacktrace(){
		$content=[];
		$btClass = new Backtrace();
		$content[]='<hr>';
		switch($this->_typeBacktrace){
			case Backtrace::BACKTRACE_TYPE_1:
				$btClass->template=<<<HTML
<b>:file: (:line:):</b> => :function:
HTML;
				$content[]=implode($btClass->viewBacktrace(), '<br>');
				$content[]='<hr>';
				break;
			case Backtrace::BACKTRACE_TYPE_2:
				$btClass->template=<<<HTML
<b>Папка:</b>   :dir:
<b>Файл:</b>    :fileName:
<b>Строка:</b>  :line:
<b>Функция:</b> :function:
HTML;
				$content[]=implode($btClass->viewBacktrace(), '<hr>');
				$content[]='<hr>';
				break;
			default:
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
			case Backtrace::BACKTRACE_TYPE_1:
				$btClass->template=<<<HTML
:file: (:line:): => :function:
HTML;
				$content[]=$btClass->viewBacktrace();
				break;
			case Backtrace::BACKTRACE_TYPE_2:
				$btClass->template=<<<HTML
Папка:  :dir:
Файл:   :fileName:
Строка: :line:
HTML;
				$content[]=$btClass->viewBacktrace();
				break;
			default:
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
