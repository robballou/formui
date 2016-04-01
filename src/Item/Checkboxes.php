<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Checkboxes item
 */
class Checkboxes extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'checkboxes');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('options', $options[0]);
      }
      else {
        parent::setOptions($options);
      }

      if (isset($options[1])) {
        $this->setOption('default_value', $options[1]);
      }
    }
    return $this;
  }

}
