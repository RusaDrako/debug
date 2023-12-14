<?php

namespace RusaDrako\debug\view;

class HTMLView extends abs_View{

	public $style = <<<HTML
<style>
.block_print_info {display: block; position: relative; min-height: 20px; color: #000; border: 2px dashed #000; padding: 10px 20px; margin: 10px 15px; font-size: 12px;}
.block_print_info .block_print_info_show{ display: none;}
.block_print_info input[type=checkbox]:checked + .block_print_info_show { display: block;}
</style>
HTML;

	public $template = <<<HTML
<pre class="block_print_info" style=":styleBackground:"><label for="debug_print_:key:"><span style="font-size: 120%;:styleTitle:"><b>&#9660; :title: &#9660;</b></span></label><input id="debug_print_:key:" class="input_print_info" type="checkbox" style="display: none;"><div class="block_print_info_show"><hr>:backtrace::description:</div></pre>
HTML;

	public function setBacktrace($value){
		if ($value) {
			parent::setBacktrace($value);
			$value=$this->set[':backtrace:'].'<hr>';
		}
		$this->set[':backtrace:'] = $value;
		return $this;
	}

	public function setTitleStyle($value){
		$this->set[':styleTitle:'] = $value;
		return $this;
	}

	public function setBacktraceStyle($value){
		$this->set[':styleBackground:'] = $value;
		return $this;
	}
}