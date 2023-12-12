<?php

namespace RusaDrako\debug\view;

class abs_View {
	public $set = [
		':key:' => null,
		':styleTitle:' => null,
		':description:' => null,
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
		$this->set[':description:'] = $this->_implode_recursion("", $value);
		return $this;
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

	public function getView(){
		# Рандомный ключ для checkbox
		$this->set[':key:'] = rand(1000000, 9999999);
		$content=str_replace(array_keys($this->set), $this->set, $this->template);
		echo $this->style;
		echo $content;
	}

}