<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Implement a tableselect element
 */
class Tableselect extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('attributes', array());
    $this->setOption('type', 'tableselect');
    $this->setOptions(func_get_args());
  }

  /**
   * Set the options
   *
   * Usage: setOptions($header, $options, $empty)
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('header', $options[0]);
        if (isset($options[1])) {
          $this->setOption('options', $options[1]);
          if (isset($options[2])) {
            $this->setOption('empty', $options[2]);
          }
        }
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }

}
