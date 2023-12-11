<?php

namespace RusaDrako\debug;

/**
 * Debug - Печать сообщений
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Debug{

	/** @var mixed Выводить backtrace */
	private $_typeBacktrace;
	/** @var mixed Заголовок */
	private $_title;
	/** @var mixed Выводимые данные */
	private $_description;
	/** @var bool Выводить в стиле var_dump() */
	private $_isVarDump=false;
	/** @var bool Выводить данные как есть, без модификации */
	private $_asItIs=false;
	/** @var string Цвет фона */
	private $_colorBackground;
	/** @var string Цвет заголовка */
	private $_colorTitle;

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
		$this->_typeBacktrace=false;
		$this->_title=false;
		$this->_description=false;
		$this->_isVarDump=false;
		$this->_colorBackground='#ffd';
		$this->_colorTitle='#080';
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


	/** */
	public function setBackgroundColor($value){
		$this->_colorBackground=$value;
		return $this;
	}


	/** */
	public function setTitleColor($value){
		$this->_colorTitle=$value;
		return $this;
	}


	/** */
	public function addBacktrace($type){
		$this->_typeBacktrace=$type;
		return $this;
	}


	/** */
	public function viewAsItIs($bool=false){
		$this->_asItIs=$bool;
		return $this;
	}


	/** */
	public function var_dump($bool=false){
		$this->_isVarDump=$bool;
		return $this;
	}


	/** */
	public function addTitle($value){
		$this->_title=$value;
		return $this;
	}


	/** */
	public function addDescription($value){
		$this->_description=$value;
		return $this;
	}


	/** */
	public function printForm(){
		$content=[];
		# Рандомный ключ для checkbox
		$key_rand=rand(1000, 9999);
		# Стиль для "всплывающего div"
		$content[]='<style>
	.block_print_info .block_print_info_show{ display: none;}
	.block_print_info input[type=checkbox]:checked + .block_print_info_show { display: block;}
</style>';
		# Открываем тэг вывода (без обработки текста браузером)
		$content[]='<pre class="block_print_info" style="display: block; position: relative; min-height: 20px; background: '.$this->_colorBackground.'; color: #000; border: 2px dashed #000; padding: 10px 20px; margin: 10px 15px; font-size: 12px;">';
		$content[]=$this->_printTitle($key_rand);
		# Выводим скрытый input
		$content[]='<input id="input_print_info_'.$key_rand.'" class="input_print_info" type="checkbox" style="display: none;">';
		# Открываем "всплывающий div"
		$content[]='<div class="block_print_info_show">';
		$content[]=$this->_printBacktrace();
		# Добавляем контент
		$content[]=$this->_print_description();
		# Закрываем "всплывающий div"
		$content[]='</div>'."\n";
		# Закрываем тэг вывода
		$content[]='</pre>'."\n";
		# Выводим блок
		echo $this->_implode_recursion('', $content);
		$this->_clean();
	}


	/** */
	public function printApp(){
		$content=[];
		# Открываем тэг вывода (без обработки текста браузером)
		$content[]='================================================================================';
		$content[]=$this->_printTitleApp($key_rand);
		$content[]=$this->_printBacktraceApp();
		$content[]='================================================================================';
		# Добавляем контент
		$content[]=$this->_print_description();
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
		$content='<label for="input_print_info_'.$key_rand.'"><span style="color: '.$this->_colorTitle.'; font-size: 120%;"><b>&#9660; '.$_title." &#9660;</b></span></label>";
		# Возвращаем содержимое
		return $content;
	}


	/** */
	private function _printTitleApp($key_rand){
		# Если заголовок не задан
		if(!$this->_title){
			# Заголовок по умолчанию
			$_title='Не указано';
		}
		else{
			$_title=$this->_title;
		}
		# Выводим заголовок
		$content=$_title."\n";
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" */
	private function _printBacktrace(){
		$content=[];
		switch($this->_typeBacktrace){
			case '1':
				$content[]=$this->_printBacktrace1();
				break;
			case '2':
				$content[]=$this->_printBacktrace2();
				break;
			default:
				break;
		}
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" Тип 1 */
	public function _printBacktrace1(){
		$content=[];
		# Получаем информацию по "цепочке вызова"
		$backtrace=debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		$content[]='<span style="font-size: 90%;">';
		# Проходим по элементам массива
		foreach($backtrace as $k=>$v){
			$file = isset($v['file']) ? dirname($v['file']).'\\'.basename($v['file']) : '---';
			$line = isset($v['line']) ? $v['line'] : '---';
			$function = isset($v['function']) ? $v['function'] : '---';
			$content[]="<b>{$file} (<span>{$line}</span>):</b> => {$function}<br>";
		}
		$content[]='</span>';
		$content[]='<hr>';
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" Тип 2 */
	private function _printBacktrace2(){
		$content=[];
		# Получаем информацию по "цепочке вызова"
		$backtrace=debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		$content[]='<span style="font-size: 90%;">';
		# Проходим по элементам массива
		foreach($backtrace as $k=>$v){
			# Выводим информацию о вызывающем файле
			if(isset($v['file'])){
				$content[]='<b>Папка:</b>  '.dirname($v['file'])."\n";
				$content[]='<b>Файл:</b>   '.basename($v['file'])."\n";
				$content[]='<b>Строка:</b> '.$v['line'];
			}
			else{
				$content[]="<b>Папка:</b>\n";
				$content[]="<b>Файл:</b>\n";
				$content[]="<b>Строка:</b>";
			}
			$content[]='<hr>';
		}
		$content[]='</span>';
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" */
	private function _printBacktraceApp(){
		$content=[];
		switch($this->_typeBacktrace){
			case '1':
				$content[]=$this->_printBacktraceApp1();
				break;
			case '2':
				$content[]=$this->_printBacktraceApp2();
				break;
			default:
				break;
		}
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" Тип 1 */
	public function _printBacktraceApp1(){
		$content=[];
		# Получаем информацию по "цепочке вызова"
		$backtrace=debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		# Проходим по элементам массива
		foreach($backtrace as $k=>$v){
			$content[]=(isset($v['file']) ? dirname($v['file']).'\\'.basename($v['file']) : '---').' ('.(isset($v['line']) ? $v['line'] : '---').'): => '.(isset($v['function']) ? $v['function'] : '---');
		}
		# Возвращаем содержимое
		return $content;
	}


	/** Блок информации по "цепочке вызова" Тип 2 */
	private function _printBacktraceApp2(){
		$content=[];
		# Получаем информацию по "цепочке вызова"
		$backtrace=debug_backtrace();
		unset($backtrace[0]);
		unset($backtrace[1]);
		unset($backtrace[2]);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		# Проходим по элементам массива
		foreach($backtrace as $k=>$v){
			# Выводим информацию о вызывающем файле
			if(isset($v['file'])){
				$content[]='Папка:	'.dirname($v['file']);
				$content[]='Файл:	'.basename($v['file']);
				$content[]='Строка:	'.$v['line']."\n";
			}
			else{
				$content[]='<b>Папка:';
				$content[]='<b>Файл:';
				$content[]='<b>Строка:'."\n";
			}
		}
		# Возвращаем содержимое
		return $content;
	}


	/** Вывод содержимого */
	public function _print_description(){
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
			if(true!=$this->_asItIs){
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
