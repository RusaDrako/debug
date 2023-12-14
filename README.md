# Debug

[![Version](http://poser.pugx.org/rusadrako/debug/version)](https://packagist.org/packages/rusadrako/debug)
[![Total Downloads](http://poser.pugx.org/rusadrako/debug/downloads)](https://packagist.org/packages/rusadrako/debug/stats)
[![License](http://poser.pugx.org/rusadrako/debug/license)](./LICENSE)

Вывод данных при откладке


## Установка (composer)
```sh
composer require 'rusadrako/debug'
```


## Установка (manual)
- Скачать и распоковать библиотеку.
- Добавить в код инструкцию:
```php
require_once('/debug/src/autoload.php')
```


## Функции вывода данных

```php
use RusaDrako\debug\DebugExpansion;

/** Вывод блока данных с добавлением backtrace (html) */
print_info($data, $title, $view);
// или
DebugExpansion::info($data, $title, $view);

/** Вывод блока данных с добавлением backtrace (console) */
print_info_app($data, $title, $view);
// или
DebugExpansion::info_app($data, $title, $view);

/** Вывод блока данных в формате var_dump с добавлением backtrace (html) */
print_dump($data, $title, $view);
// или
DebugExpansion::dump($data, $title, $view);

/** Вывод табличного массива с добавлением backtrace */
print_table($data, $title, $view);
// или
DebugExpansion::table($data, $title, $view);

/** Вывод древовидного массива/объекта с добавлением backtrace */
print_tree($data, $title, $view);
// или
DebugExpansion::tree($data, $title, $view);

/** Стилизованное сообщение */
print_style($style, $data, $title, $view);
// или
DebugExpansion::style($style, $data, $title, $view);
```
- **$data** - Содержимое блока
- **$title** - Заголовок блока (по умолчанию null)
- **$view** - Показать блок (по умолчанию true)
- **$style** - Стиль формы
