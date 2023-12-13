<?php

namespace RusaDrako\debug\view;

class ConsoleView extends abs_View{

	public $style = '';

	public $template = <<<HTML

================================================================================
:title:

:backtrace:
--------------------------------------------------------------------------------
:description:
================================================================================
HTML;

}