<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Button form item.
 */
class Button extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'button');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('value', $options[0]);
        return $this;
      }

      parent::setOptions($options);
    }
    return $this;
  }

}
