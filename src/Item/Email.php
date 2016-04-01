<?php
namespace Drupal\formui\Item;

use Drupal\formui\Item\Textfield;

/**
 * Add an email field.
 *
 * Fallsback to textfield.
 */
class Email extends Textfield {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    if (module_exists('elements')) {
      $this->setOption('type', 'emailfield');
    }
  }

}
