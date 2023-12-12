<?php

namespace RusaDrako\debug;

/**
 * Backtrace
 * @created 2023-12-12
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Backtrace {
	/** @var string Тип вывода трасировки */
	const BACKTRACE_TYPE_1 = 1;
	const BACKTRACE_TYPE_2 = 2;

	public $template = ':file: (:line:): => :function:';
	public $count = 4;

	/** Формирует данные backtrace по шаблону */
	public function viewBacktrace(){
		$content=[];
		foreach($this->getBacktraceData() as $v){
			$content[]=str_replace(array_keys($v), $v, $this->template);
		}
		# Возвращаем содержимое
		return $content;
	}

	/** Возвращает подготовленные данные backtrace */
	public function getBacktraceData(){
		$result=[];
		# Получаем информацию по "цепочке вызова"
		$backtrace=debug_backtrace();
		for ($i = 0; $i<$this->count; $i++) {
			unset($backtrace[$i]);
		}
		# "Разворачиваем" массив
		$backtrace=array_reverse($backtrace);
		# Проходим по элементам массива
		foreach($backtrace as $k=>$v){
			$result[] = [
				':file:'=>isset($v['file']) ? $v['file'] : '---',
				':dir:'=>isset($v['file']) ? dirname($v['file']) : '---',
				':fileName:'=>isset($v['file']) ? basename($v['file']) : '---',
				':line:'=>isset($v['line']) ? $v['line'] : '---',
				':function:'=>isset($v['function']) ? $v['function'].'()' : '---',
			];
		}
		# Возвращаем содержимое
		return $result;
	}

/**/
}
