<?php

namespace RusaDrako\debug;

/**
 * Визуализация массивов
 * @created 2023-12-12
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class Visualization {

	public $tableStyle = ' style="border: 1px solid #ddd;"';
	public $keyStyle = ' style="border: 1px solid #00f; color: #00f; font-weight: bold;"';
	public $arrayStyle = ' style="border: 1px solid #f0f; color: #f0f; font-weight: bold;"';
	public $objectStyle = ' style="border: 1px solid #080; color: #080; font-weight: bold;"';
	public $objectNameStyle = ' style="color: #080; font-weight: bold;"';
	public $errorStyle = ' style="border: 1px solid #fdd; color: #f00"';

	/**
	 * Возвращает html-код таблицы сформированной на основе двухмерного массива
	 * @param array $array Двухмерный массив.
	 * @param array $column_name Массив, где ключ - старое имя столбца, а значение - новое имя столбца.
	 * array('ID' => '#');
	 * @param string $setting Список столбцов, через запятую, которые печатать не надо.
	 * array(
	 * 'not_print'    - Список столбцов, которые не надо печатать (через запятую)
	 * 'column_place' - Порядок вывода столбцов (через запятую). Неуказанные столбцы ставятся в конец, в порядке получения.
	 * 'line_key'     - Печатать номер строки по ключу - true, или просто номер строки - false
	 * 'class'        - Имя класса таблицы (по умолчанию используется стиль),
	 * 'column_size'  - Размер столбцов (через запятую)
	 * );
	 * @return string Табличное представление двухмерного массива
	 */
	public function print_table_2d_array($array = [], $column_name = [], $setting = []) {
		# Нулевые настройки функции
		$setting_t = array(
			'class'        => false,   # Имя класса таблицы (по умолчанию используется стиль)
			'not_print'    => false,   # Список столбцов, которые не надо печатать (через запятую)
			'column_size'  => false,   # Размер столбцов (через запятую)
			'line_key'     => false,   # Печатать номер строки по ключу - true, или просто номер строки - false
			'column_place' => false,   # Порядок вывода столбцов
		);
		# Накладываем присланные настрйки функции
		$setting_t = array_merge($setting_t, $setting);
		# Используем класс или стиль
		$class = $setting_t['class'] ? ' class="' . $setting_t['class'] . '"' : '';
		$style = $setting_t['class'] ? '' : $this->tableStyle;
		# имена столбцов всегда маленькими буквамии без пробелов
		$setting_t['not_print'] = preg_replace("/\s/is", '', $setting_t['not_print']);
		# создаём массив столбцов, которые не надо писать
		$arr_not_print = $this->string_divide_to_array($setting_t['not_print']);
		$content[] = "<table{$class}{$style}>";
		# Если передан массив данных и он не пустой
		if (is_array($array)
			&& !empty($array)
			&& is_array(current($array))) {
			# Выстраиваем столбцы в заданном порядке
			if (!empty($setting_t['column_place'])) {
				# Формируем массив из строки
				$arr_place_column = $this->string_divide_to_array($setting_t['column_place']);
				foreach ($arr_place_column as $key => $value) {
					$arr_place_column[$key] = '';
				}
				$new_array = [];
				foreach ($array as $key => $value) {
//					if (is_array($value)) {
					$new_array[$key] = array_merge($arr_place_column, $value);
//					}
				}
				$array = $new_array;
			}
			# Если заданы размеры столбцов, то формируем их
			if (!empty($setting_t['column_size'])) {
				$arr_size = explode(",", $setting_t['column_size']);
				$content[] = '<col width="' . implode('px"><col width="', $arr_size) . 'px">';
			}
			$content[] = '<thead>';
			$content[] = '<tr>';
			$content[] = "<th{$style}><b>#</b></th>";
			# Проходим по массиву данных (только по первой строке, для формирования заголовков)
			foreach ($array as $value) {
				# Если строка - массив (каждый элемент - столбец)
				if (is_array($value)) {
					# Получаем массив ключей столбцов
					$column_name_all = array_diff_key ($value, $arr_not_print);
					# Проходим по массиву ключей столбцов
					foreach ($column_name_all as $key_2 => $value_2) {
						# Если существует пара, для замены названия столбцов
						if (array_key_exists($key_2, $column_name)) {
							# Подменяем ключ
							$key_2 = $column_name[$key_2];
						}
						# Прописываем заголовок
						$content[] = "<th{$style}><b>{$key_2}</b></th>";
					}
				}
				# Прерываем проход по массиву
				break;
			}
			$content[] = '</tr>';
			$content[] = "</thead>";
			$i = 0;
			# Проходим по массиву данных
			foreach($array as $key => $value) {
				# Если строка - массив (каждый элемент - столбец)
				if (is_array($value)
					&& !empty($value)) {
					$value = array_diff_key ($value, $arr_not_print);
					$content[] = '<tr>';
					# Если требуется выводить ключ
					if ($setting_t['line_key']) {
						$content[] = "<td{$style}><b><span style=\"color: #f00;\">{$key}</span></b></td>";
						# Если требуется выводить номер строки
					} else {
						$content[] = "<td{$style}><b>" . ++$i . "</b></td>";
					}
					$content[] = "<td{$style}>" . implode("</td>\n<td{$style}>", $value) . "</td>";
					$content[] = '</tr>';
				}
			}
		} else {
			$content[] = '<tr>';
			$content[] = '<td>Нет данных</td>';
			$content[] = '</tr>';
		}
		$content[] = '</table>';
		# Объединяем массив в строку
		$result = implode("\n", $content);
		# Возвращаем результат
		return $result;
	}

	/**
	 * Возвращает html-код таблицы сформированной на основе древовидного массива
	 * @param array $array Древовидный многоуровневый массив массив.
	 * @param string $class Имя класса таблицы (по умолчанию присваевается стиль).
	 * @return string Табличное представление древовидного массива
	 */
	public function print_table_tree_array($array = [], $class = false, $objectRecursion = []) {
		# Используем класс или стиль
		$class = $class ? ' class="' . $class . '"' : '';
		$style = $class ? '' : $this->tableStyle;
		$content[] = "<table{$class}{$style}>";
		if (is_array($array)) {
			if (empty($array)) {
				$content[] = '<tr>';
				$content[] = "<td{$style}> <i>Пустой массив</i> </td>";
				$content[] = '</tr>';
			} else {
				foreach($array as $key => $value) {
					$content[] = '<tr>';
					if (is_array($value)){
						$content[] = "<td{$this->arrayStyle}>{$key}</td>";
						$content[] = "<td{$style}>";
						$content[] = $this->print_table_tree_array($value);
						$content[] = '</td>';
					} else if (is_object($value)) {
						$class = get_class($value);
						$content[] = "<td{$this->objectStyle}>{$key}</td>";
						$content[] = "<td{$style}>";
						$content[] = "<span{$this->objectNameStyle}>Object ({$class})</span>";
						$recursion = false;
						foreach($objectRecursion as $v) {
							if ($v === $value) {$recursion=true;}
						}
						# Если в дереве ранее встречался данный объект
						if ($recursion) {
							$content[] = '<br>*RECURSION*';
						} else {
							$objectRecursion[] = $value;
							$valueNext = [];
							foreach((array)$value as $k_2 => $v_2) {
								$k_2 = str_replace($class, '**', $k_2);
								$valueNext[$k_2] = $v_2;
							}
							$content[] = $this->print_table_tree_array($valueNext, false, $objectRecursion);
						}
						$content[] = '</td>';
					} else {
						$content[] = "<td{$this->keyStyle}>{$key}</td>";
						$content[] = "<td{$style}>{$value}</td>";
					}
					$content[] = '</tr>';
				}
			}
		} else {
			$content[] = '<tr>';
			$content[] = "<td{$this->errorStyle}><b>ОШИБКА:</b> Переданные данные не являются массивом.</td>";
			$content[] = '</td>';
			$content[] = '</tr>';
		}
		$content[] = '</table>';
		$result = implode("\n", $content);
		# Возвращаем результат
		return $result;
	}

	/**
	 * Возвращает массив на основе строки с разделителем (Пробелы удаляются)
	 * @param string $string Строка с элементами для массива
	 * @param string $delimiter Разделитель для получения массива
	 * @return array Новый массив (ключи и значени имеют одинаковые значения)
	 */
	private function string_divide_to_array($string, $delimiter = ',') {
		# Чистим строку от пробелов
		$string_t = preg_replace("/\s/i", '', $string);
		# Из строки получаем массив
		$array = explode($delimiter, $string_t);
		# Создаём массив, где ключи и значения имеют одно значение
		$result = array_combine($array, $array);
		# Возвращаем результат
		return $result;
	}

/**/
}
