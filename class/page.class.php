<?php

/**
 * Gtf Page Template. A small template engine in memory of Gt.
 *
 * @author yfwz100
 * @version 0.5
 */
class Page {
	
	private $page;  # the page block element
	private $tags;  # the tag stack
	private $templ; # the extends template name
	private $var;   # the user-defined variables

	public function __construct() {
		$this->page = array();
		$this->tags = array();
	}
	public function def($name, $value) {
		$this->var[$name] = $value;
	}
	protected function ext($template) {
		$this->templ = $template;
	}
	protected function block($name) {
		$this->tags[] = $name;
		ob_start();
	}
	protected function end() {
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

/* legacy code for page.
class Page {
	private $page;
	private $curtag;
	public function __construct() {
		$this->page = array();
	}
	public function def($name, $value=null) {
		if ($value == null) {
			$this->curtag = $name;
			ob_start();
		} else {
			$this->page[$name] = $value;
		}
	}
	public function block($name) {
		if (array_key_exists($name, $this->page)) {
			echo $this->page[$name];
		} else {
			echo '';
		}
	}
	public function end($clean=true) {
		$buffer = $clean ? ob_get_clean() : ob_get_contents();
		if ($buffer) {
			$this->page[$this->curtag] = $buffer ? $buffer : '';
			$this->curtag = null;
		} else {
			echo 'warning.';
		}
	}
	public function template($file) {
		include $file;
	}
}
*/