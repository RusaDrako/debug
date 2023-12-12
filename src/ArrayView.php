<?php

namespace RusaDrako\debug;

/**
 * ArrayView Класс работы с массивами
 * @created 2023-12-11
 * @author Петухов Леонид <rusadrako@yandex.ru>
 */
class ArrayView {

	/** Типовой маркер класса для сообщений об ошибке
	 * @private string */
	private $error_title = '<br/><br/>--->array_class ';

	/** Функция возвращает массив в котором удаляются заданные ключи.
	@param array $array Массив, который требуется обработать.
	@param string $keys Список ключей, которые требуется удалить (через запятую).
	@param bool $exclusion Маркер "оставлять заданные ключи"
	@return array Очищенный от лишних полей массив
	 */
	public function delete_keys($array, $keys, $exclusion = false) {
		# Значение результата по-умолчанию
		$result = $array;
		if (is_array($array)) {
			# Формируем массив из строки
			$arr_keys = $this->string_divide_to_array ($keys);
			//			# Чистим строку от пробелов
			//			$str_keys = preg_replace("/\s/i", '', $keys);
			//			# Из строки получаем массив и сразу меняем местами ключи и значения
			//			$arr_keys = array_flip(explode(",", $str_keys));
			# если задан маркер "оставлять заданные ключи"
			if ($exclusion) {
				# Оставляем указанные ключи
				$result = array_intersect_key($array, $arr_keys);
			} else {
				# Удаляем указанные ключи
				$result = array_diff_key($array, $arr_keys);
			}
		}
		# Возвращаем результат
		return $result;
	}

	/** Функция проверки переменной - масив это или нет. И, если это не массив, то преобразовываем её в массив.
	@param array/bool/string/int $variable Переменная
	@return array Массив
	 */
	public function control($variable) {
		# Если переменная - не массив
		if (!is_array($variable)) {
			# Преобразуем в массив
			$result[0] = $variable;
		} else {
			# Оставляем массив, как есть
			$result = $variable;
		}

		# Возвращаем результат
		return $result;
	}

	/** Функция возвращает html-код таблицы сформированной на основе двухмерного массива
	 * @param array $array Двухмерный массив.
	 * @param array $column_name Массив, где ключ - старое имя столбца, а значение - новое имя столбца.<br>
	 * array('ID' => '#');
	 * @param string $setting Список столбцов, через запятую, которые печатать не надо.<br>
	 * array(<br>
	 * <b>'not_print'</b>		- Список столбцов, которые не надо печатать (через запятую)<br>
	 * <b>'column_place'</b>	- Порядок вывода столбцов (через запятую). Неуказанные столбцы ставятся в конец, в порядке получения.<br>
	 * <b>'line_key'</b>		- Печатать номер строки по ключу - true, или просто номер строки - false<br>
	 * <b>'class'</b>			- Имя класса таблицы (по умолчанию используется стиль),<br>
	 * <b>'column_size'</b>		- Размер столбцов (через запятую)<br>
	 * );
	 * @return string Табличное представление двухмерного массива
	 */
	public function print_table_2d_array($array = [], $column_name = [], $setting = []) {

		# Нулевые настройки функции
		$setting_t = array(
			'class'				=> false,		# Имя класса таблицы (по умолчанию используется стиль)
			'not_print'			=> false,		# Список столбцов, которые не надо печатать (через запятую)
			'column_size'		=> false,		# Размер столбцов (через запятую)
			'line_key'			=> false,		# Печатать номер строки по ключу - true, или просто номер строки - false
			'column_place'		=> false,		# Порядок вывода столбцов
		);

		# Накладываем присланные настрйки функции
		$setting_t = array_merge($setting_t, $setting);

		# Используем класс
		if ($setting_t['class']) {
			$class = ' class="' . $setting_t['class'] . '"';
			$style = '';
			# или стиль
		} else {
			$class = '';
			$style = ' style="border: 1px solid #ddd;"';
		}

		# Нулевой массив формируймой таблицы
		$content = [];

		# имена столбцов всегда маленькими буквамии без пробелов
		$setting_t['not_print'] = preg_replace("/\s/is", '', $setting_t['not_print']);

		# создаём массив столбцов, которые не надо писать
		$arr_not_print = $this->string_divide_to_array($setting_t['not_print']);

		$content[] = '<table' . $class . $style . '>';

		# Если передан массив данных и он не пустой
		if (is_array($array)
			&& !empty($array)
			&& is_array(current($array))) {
			//echo 1234567890;
			# Выстраиваем столбцы в заданном порядке
			if (!empty($setting_t['column_place'])) {
				# Формируем массив из строки
				$arr_place_column = $this->string_divide_to_array ($setting_t['column_place']);
				//				# Разбиваем строку на массив
				//				$arr_place_column = explode(",", $setting_t['column_place']);
				//				# Меняем местами ключи и значения
				//				$arr_place_column = array_flip($arr_place_column);

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
			$content[] = '<th' . $style . '><b>#</b></th>';

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
						$content[] = '<th' . $style . '><b>' . $key_2 . "</b></th>";
					}
				}
				# Прерываем проход по массиву
				break;
			}

			$content[] = '</tr>';
			$content[] = "</thead>\n";

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
						$content[] = '<td' . $style . '><b><span style=\'color: #f00;\'>' . $key . "</span></b></td>";
						# Если требуется выводить номер строки
					} else {
						$content[] = '<td' . $style . '><b>' . ++$i . "</b></td>";
					}
					$content[] = '<td' . $style . '>' . implode("</td>\n<td" . $style . ">", $value) . "</td>";
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

	/** Функция возвращает html-код таблицы сформированной на основе древовидного многоуровневого массива
	@param array $array Древовидный многоуровневый массив массив.
	@return string Табличное представление древовидного массива
	@param string $class Имя класса таблицы (по умолчанию присваевается стиль).
	 */
	public function print_table_tree_array($array = [], $class = false) {

		if ($class) {
			$class = ' class="' . $class . '"';
			$style = '';
		} else {
			$class = '';
			$style = ' style="border: 1px solid #ddd;"';
		}

		$content = [];

		$content[] = '<table' . $class . $style . '>';

		if (is_array($array)) {
			if (empty($array)) {
				$content[] = '<tr>';
				$content[] = '<td' . $style . '> <i>Пустой массив</i> </td>';
				$content[] = '</tr>';
			} else {
				foreach($array as $key => $value) {
					$content[] = '<tr>';
					if (is_array($value)) {
						$content[] = '<td style="border: 1px solid #ddd; color: #f0f;">=><b>[' . $key . ']</b></td>';
						$content[] = '<td' . $style . '>';
						$content[] = $this->print_table_tree_array($value);
						$content[] = '</td>';
					} else {
						$content[] = '<td style="border: 1px solid #ddd; color: #00f;">=><b>[' . $key . ']</b></td>';
						$content[] = '<td' . $style . '>' . $value . '</td>';
					}
					$content[] = '</tr>';
				}
			}
		} else {
			$content[] = '<tr>';
			$content[] = '<td style="border: 1px solid #fdd; color: #f00"><b>ОШИБКА:</b> Переданные данные не являются массивом.';
			$content[] = '</td>';
			$content[] = '</tr>';
		}

		$content[] = '</table>';

		$result = implode("\n", $content);

		# Возвращаем результат
		return $result;
	}

	/** Функция сортировки двухмерного массива по заданному ключу
	@param array $array Двухмерный массив.
	@param string $key Ключ, по которому будет производиться сортировка.
	@param bool $desc Маркер сортировки в обратном порядке.
	@return array Отсортированный массив
	 */
	public function sort_2d_array($array, $key, $desc = false) {
		$result = $array;
		# Если переданная переменная - это массив
		if (is_array($result)) {
			# Если включена обратная сортировка
			if ($desc) {
				# Вызываем сортировку DESC (пользовательская)
				usort($result, $this->sort_2d_array_desc($key));
			} else {
				# Вызываем сортировку ASC (пользовательская)
				usort($result, $this->sort_2d_array_asc($key));
			}
		}

		# Возвращаем результат
		return $result;
	}

	/* Функция для сортировки ASC (вариант 1) */
	private function sort_2d_array_asc($key) {
		/* Сравниваем значение по ключу date_reg */
		return function ($a, $b) use ($key) {
			if (isset($a[$key]) && isset($b[$key])) {
				return strnatcmp($a[$key],$b[$key]);
			} else {
				return -1;
			}
		};
	}

	/* Функция для сортировки ASC (вариант 1) */
	private function sort_2d_array_desc($key) {
		/* Сравниваем значение по ключу date_reg */
		return function ($a, $b) use ($key) {
			if (isset($a[$key]) && isset($b[$key])) {
				return strnatcmp($b[$key],$a[$key]);
			} else {
				return 1;
			}
		};
	}

	/** Функция возвращает отсортированный по заданному полю двухмерный массив (не рекомендовано).
	@param array $array Массив, который требуется обработать.
	@param string $key Имя столбца, по которому будет происходить сортировка.
	@param bool $desc Маркер обратной сортировки.
	@return array $result Отсортированный массив.
	 */
	public function sort_2d_array_key($array, $key, $desc = false) {
		# Значение результата по-умолчанию
		$result = [];
		if (is_array($array)) {
			$array_sort = [];
			# Создаём второй массив состоящий из значений определённого поля
			foreach($array as &$value) {
				if (isset($value[$key])) {
					# Выбираем поле, по которому будем сортировать массив
					$array_sort[] = $value[$key];
				} else {
					# Выбираем поле, по которому будем сортировать массив
					$array_sort[] = 127;
				}
			}

			# Выбираем тип сортировки и сортируем
			if (false == $desc) {
				array_multisort($array_sort, SORT_ASC, $array);
			} else {
				array_multisort($array_sort, SORT_DESC, $array);
			}
			$result = $array;
		}
		# Возвращаем результат
		return $result;
	}

	/** Функция подмены ключей в массиве<br>
	 * Условия корректной работы:<br>
	 * 1) Новые ключи не должны совпадать со ключами основного массива<br>
	 * 2) Старые ключи не должны совпадать с новыми ключами
	@param array $array Обрабатываемый массив
	@param array $arr_couple_keys Массив подмены ключей.<br>Формат - array($старый ключ => $новый_ключ, ...)
	@return array Массив с новыми кючами
	 */
	public function rename_keys($array, $arr_couple_keys = []) {

		# Значение результата по-умолчанию
		$result = false;

		# Если массив пустой, или не массив, то возвращаем входящие данные
		if (empty($array)) {
			# Возвращаем результат
			return $array;
		} elseif (!is_array($array)) {
			# Возвращаем результат
			return $array;
		}

		# Если массив ключей пустой, или не массив, то возвращаем входящие данные
		if (empty($arr_couple_keys)) {
			# Возвращаем результат
			return $array;
		} elseif (!is_array($arr_couple_keys)) {
			# Возвращаем результат
			return $array;
		}

		# Получаем массив новых ключей
		$arr_old_keys = array_flip(array_keys($arr_couple_keys));
		$arr_new_keys = array_flip(array_keys(array_flip($arr_couple_keys)));

		# Получаем ключи основного массива
		$arr_keys = array_flip(array_keys($array));

		# Если в основном массиве есть ключи, которые есть в списке новых ключей, то останавливаем функцию и возвращаем исходные данные,что бы не потерять данные
		$result_control_1 = array_intersect_key($arr_keys, $arr_new_keys);
		if (0 < count($result_control_1)) {
			# Возвращаем результат
			return $array;
		}

		# Если в массиве новых ключей есть ключи, которые есть в списке старых ключей, то останавливаем функцию и возвращаем исходные данные,что бы не потерять данные
		$result_control_2 = array_intersect_key($arr_new_keys, $arr_old_keys);
		if (0 < count($result_control_2)) {
			# Возвращаем результат
			return $array;
		}

		# Меняем ключи $key - старый ключ, $value - новый ключ
		foreach ($arr_couple_keys as $key => $value) {
			# Если ключ в массиве найден
			if(isset($array[$key])) {
				# Создаём новый элемент и присваеваем ему значение старого
				$array[$value] = $array[$key];
				# Удаляем старый элемент
				unset($array[$key]);
			}
		}

		# Возвращаем результат
		return $array;
	}

	/** Функция пересборки двухмерного массива с ключом из заданного столбца
	@param array $array Обрабатываемый массив
	@param string $column_name Имя столбца, данные из которого будут использоваться в качестве ключей
	@return array Массив с новыми кючами
	 */
	public function rebuild_array_key($array, $column_name) {

		# Значение результата по-умолчанию
		$result = [];

		# Если переменная - массив, и она не пустая
		if (is_array($array)
			&& !empty($array)) {
			# Проходим по массиву
			foreach ($array as $value) {
				# Если ключ в массиве найден
				if(isset($value[$column_name])) {
					# Заносим новую строку в новый массив
					$result[$value[$column_name]] = $value;
				}
			}
		}

		# Возвращаем результат
		return $result;
	}

	/** Функция собирает одномерный массив с данными из двухмерного массива.
	@param array $array Обрабатываемый массив
	@param string $column_value Имя столбца, данные из которого будут использоваться в качестве значений
	@param bool/string $column_key Имя столбца, данные из которого будут использоваться в качестве ключей (по-умолчанию не используется)
	@return array Новый массив
	 */
	public function create_subarray($array, $column_value, $column_key = false) {

		# Значение результата по-умолчанию
		$result = [];

		# Если переменная - массив, и она не пустая
		if (is_array($array)
			&& !empty($array)) {
			# Проходим по массиву
			foreach ($array as $key => $value) {
				if ($column_key) {
					# Если ключ в массиве найден
					if (isset($value[$column_value])
						&& isset($value[$column_key])) {
						# Заносим новую строку в новый массив
						$result[$value[$column_key]] = $value[$column_value];
					}
				} else {
					if (isset($value[$column_value])) {
						# Заносим новую строку в новый массив
						$result[] = $value[$column_value];
					}
				}
			}
		}

		# Возвращаем результат
		return $result;
	}

	/** Функция возвращает массив на основе строки (с разделителем ',') (Пробелы удаляются)
	@param string $string Строка с элементами, на основе которых формируется массив (через запятую)
	@return array Новый массив (ключи и значени имеют одинаковые значения)
	 */
	private function string_divide_to_array ($string) {
		# Чистим строку от пробелов
		$string_t = preg_replace("/\s/i", '', $string);
		# Из строки получаем массив
		$array = explode(",", $string_t);
		# Создаём массив, где ключи и значения имеют одно значение
		$result = array_combine($array, $array);
		# Возвращаем результат
		return $result;
	}


/**/
}
