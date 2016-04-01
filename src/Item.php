<?php
namespace Drupal\formui;

/**
 * Base class for form API items
 */
class Item {
  /**
   * Internal array of options for this item
   *
   * @var array
   */
  public $itemOptions = array();

  /**
   * Constructor
   */
  public function __construct() {
    $this->setOptions(func_get_args());
  }

  /**
   * Handle calls for option based functions
   */
  public function __call($method, $arguments) {
    $this->setOption($method, $arguments[0]);
    return $this;
  }

  /**
   * Default item generator
   */
  public function generate() {
    $item = array();
    foreach ($this->itemOptions as $key => $value) {
      $item['#' . $key] = $value;
    }
    return $item;
  }

  /**
   * Set a form item attribute.
   *
   * This will set/override the attribute set.
   */
  public function setAttribute($attribute, $value) {
    if (!isset($this->itemOptions['attributes'])) {
      $this->itemOptions['attributes'] = array();
    }

    $this->itemOptions['attributes'][$attribute] = $value;
    return $this;
  }

  /**
   * Set an option
   */
  public function setOption($option, $value) {
    $this->itemOptions[$option] = $value;
    return $this;
  }

  /**
   * Set multiple options
   */
  public function setOptions($options) {
    foreach ($options as $key => $value) {
      $this->setOption($key, $value);
    }
    return $this;
  }

  /**
   * Set the prefix/suffix for this element based on the provided notation.
   */
  public function wrap($wrap = 'div') {
    // parse the wrap notation
    $mode = 'tag';
    $tag = $id = $class = '';
    $classes = array();

    for ($i = 0, $len = strlen($wrap); $i < $len; $i++) {
      $char = substr($wrap, $i, 1);
      if (!in_array($char, array('.', '#'))) {
        if ($mode === 'tag') {
          $tag .= $char;
        }
        elseif ($mode === 'id') {
          $id .= $char;
        }
        elseif ($mode === 'class') {
          $class .= $char;
        }
      }
      elseif ($char === '.') {
        $mode = 'class';

        // if a class name has been started, add it to the classes
        if ($class) {
          $classes[] = $class;
          $class = '';
        }
      }
      elseif ($char === '#') {
        $mode = 'id';

        // if a class name has been started, add it to the classes
        if ($class) {
          $classes[] = $class;
          $class = '';
        }
      }
    }
    // if a class name has been started, add it to the classes
    if ($class) {
      $classes[] = $class;
      $class = '';
    }

    // save the suffix
    $prefix = '<' . $tag;
    if ($id) {
      $prefix .= ' id="' . $id .'"';
    }
    if ($classes) {
      $prefix .= ' class="' . implode(' ', $classes) . '"';
    }
    $prefix .= '>';
    $this->setOption('prefix', $prefix);

    // save the suffix
    $this->setOption('suffix', '</' . $tag . '>');

    return $this;
  }

}
