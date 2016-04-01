<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Markup item
 */
class Markup extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'markup');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('markup', $options[0]);
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }

}
