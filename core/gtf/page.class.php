<?php
namespace gtf;

/**
 * Gtf Page Template. 
 * A simple template class aims to be fast and flexible.
 *
 * @author yfwz100
 * @version 0.6
 */
class Page {

  private $page;    # the page block element
  private $tags;    # the tag stack
  private $templ;   # the extends template name
  private $var;     # the user-defined variables
  private $baseUri; # the base URI

  public function __construct() {
    $this->page = array();
    $this->tags = array();
    $this->baseUri = dirname($_SERVER['SCRIPT_NAME']);
  }

  protected function resUri($path) {
    return $this->baseUri."/$path";
  }

  /** Define the name with the value.
   * @param $name the name.
   * @param $value the value.
   */
  public function def($name, $value) {
    $this->var[$name] = $value;
  }

  /** Extend a template.
   * @param $template the extended template.
   */
  protected function ext($template) {
    $this->templ = $template;
  }

  /** Include a template located at view folder.
   * @param $template the included view.
   */
  protected function inc($template) {
    include dirname(__FILE__).'/../view/'.$template;
  }

  /** Create a block with a specific name.
   * The content of the block is defined by the first time. The other will be replaced by the first definition. Blocks can be nested.
   * @param $name the name of the block.
   */
  protected function block($name) {
    $this->tags[] = $name;
    ob_start();
  }

  /** End the definition of a block.
   */
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

  /** Output the template with this page object.
   * @param $file the template file path relative to view folder.
   */
  public function template($file) {
    $var = $this->var; # make the variable name shorter.
    include $file;
    if ($this->templ != null) {
      $templ = $this->templ;
      $this->templ = null;
      $this->template($templ);
    }
  }
} 

