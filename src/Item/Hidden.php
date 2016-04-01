<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\Textfield;

/**
 * Hidden item
 */
class Hidden extends Textfield {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'hidden');
  }

}
