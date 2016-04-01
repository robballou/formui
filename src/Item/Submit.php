<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\Button;

/**
 * Submit button
 */
class Submit extends Button {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'submit');
  }

}
