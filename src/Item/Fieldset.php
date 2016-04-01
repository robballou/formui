<?php

namespace Drupal\formui\Item;

use Drupal\formui\Item\Container;

/**
 * Fieldset item.
 */
class Fieldset extends Container {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'fieldset');
  }

}
