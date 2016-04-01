<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\Textfield;

/**
 * Textarea element
 */
class Textarea extends Textfield {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'textarea');
  }

}
