<?php

class Page {
	
	private $page;
	private $tags;
	private $templ;
	private $var;

	public function __construct() {
		$this->page = array();
		$this->tags = array();
	}
	public function def($name, $value) {
		$this->var[$name] = $value;
	}
	private function ext($template) {
		$this->templ = $template;
	}
	private function block($name) {
		$this->tags[] = $name;
		ob_start();
	}
	private function end() {
		$name = array_pop($this->tags);
		if (! array_key_exists($name, $this->page)) {
			$this->page[$name] = ob_get_clean();
		} else {
			ob_end_clean();
		}
		if ($this->templ == null) {
			echo $this->page[$name];
		}
	}
	public function template($file) {
		$var = $this->var;
		include $file;
		if ($this->templ != null) {
			$templ = $this->templ;
			$this->templ = null;
			$this->template($templ);
		}
	}
}

$page = new Page();
$page->template('view/test.phtml');
