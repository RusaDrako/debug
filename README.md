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
/** Вывод блока данных с добавлением backtrace (html) */
function print_info($data, $title=false, $view=true){}

/** Вывод блока данных с добавлением backtrace (console) */
function print_info_app($data, $title=false, $view=true){}

/** Вывод блока данных в формате var_dump с добавлением backtrace (html) */
function print_dump($data, $title=false, $view=true){}

/** Вывод табличного массива */
function print_table($data, $title=false, $view=true){}

/** Вывод древовидного массива */
function print_tree($data, $title=false, $view=true){}

/** Пользовательское сообщение - ошибка (html) */
function print_style($style, $data, $title=false, $view=true){}
```
- **$data** - Содержимое блока
- **$title** - Заголовок блока
- **$view** - Показать блок
- **$style** - Стиль формы
