<?php

namespace RusaDrako\debug\view;

class abs_View {

	/** @var int Маркер использования стиля */
	static protected $isUseStyle = 0;

	public $set = [
		':key:' => null,
		':styleTitle:' => null,
		':styleBackground:' => null,
		':title:' => null,
		':backtrace:' => null,
		':description:' => null,
	];

	public $style = '';

	public $template = '';

	public function setTitle($value){
		$this->set[':title:'] = $value;
		return $this;
	}

	public function setBacktrace($value){
		$this->set[':backtrace:'] = $this->_implode_recursion("\n", $value);
		return $this;
	}

	public function setDescription($value){
		$this->set[':description:'] = $this->_implode_recursion("\n", $value);
		return $this;
	}

	/**
	 * Рекурсивная сборка массива в строку
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

	/** Выводит блок */
	public function getView(){
		# Рандомный ключ для checkbox
		$this->set[':key:'] = rand(1000000, 9999999);
		$content=str_replace(array_keys($this->set), $this->set, $this->template);
		$this->getStyle();
		echo $content;
	}

	/** Выводит стиль */
	public function getStyle(){
		if (!static::$isUseStyle) {
			static::$isUseStyle = 1;
			echo $this->style;
		}
	}

}