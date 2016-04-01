<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Textfield item
 */
class Textfield extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'textfield');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        if (is_array($options[0])) {
          $options[0] = implode(',', $options[0]);
        }
        $this->setOption('default_value', $options[0]);
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }

}
