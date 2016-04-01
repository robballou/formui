<?php

namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Container item.
 */
class Container extends Item {
  public $items = [];

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'container');
  }

  /**
   * Get a subitem if it exists.
   */
  public function __get($key) {
    return $this->items[$key];
  }

  /**
   * Set a subitem.
   */
  public function __set($key, $item) {
    $this->items[$key] = $item;
  }

  /**
   * Overload Item::generate() to include subitems.
   */
  public function generate() {
    $item = parent::generate();
    foreach ($this->items as $key => $subitem) {
      $item[$key] = $subitem->generate();
    }
    return $item;
  }

}
