<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * File upload item
 */
class File extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'file');
  }
}
