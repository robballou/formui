<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item;

/**
 * Implement a table element
 */
class Table extends Item {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('attributes', array());
    $this->setOption('empty', '');
    $this->setOption('caption', '');
    $this->setOption('colgroups', array());
    $this->setOption('sticky', FALSE);
    $this->setOption('theme', 'table');
    $this->setOptions(func_get_args());
  }

  /**
   * Set options.
   *
   * Usage: $header, $rows
   */
  public function setOptions($options) {
    if (isset($options[0])) {
      $this->setOption('header', $options[0]);
      if (isset($options[1])) {
        $this->setOption('rows', $options[1]);
      }
    }
    else {
      parent::setOptions($options);
    }
    return $this;
  }

}
