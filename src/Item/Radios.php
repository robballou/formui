<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\Checkboxes;

/**
 * Radios item
 */
class Radios extends Checkboxes {
  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'radios');
  }

}
