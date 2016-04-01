<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Select item
 */
class Select extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'select');
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
    }
    return $this;
  }

}
