<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\File;

/**
 * File upload item
 */
class ManagedFile extends File {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'managed_file');
  }

}
