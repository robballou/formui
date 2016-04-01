<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Checkbox item
 */
class Checkbox extends Item {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'checkbox');
  }

}
