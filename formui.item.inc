<?php
/**
 * Base class for form API items
 */
class FormUIItem {
  /**
   * Internal array of options for this item
   * @var array
   */
  public $itemOptions = array();

  /**
   * Constructor
   */
  public function __construct() {
    $this->setOptions(func_get_args());
  }

  /**
   * Handle calls for option based functions
   */
  public function __call($method, $arguments) {
    $this->setOption($method, $arguments[0]);
    return $this;
  }

  /**
   * Default item generator
   */
  public function generate() {
    $item = array();
    foreach ($this->itemOptions as $key => $value) {
      $item['#' . $key] = $value;
    }
    return $item;
  }

  /**
   * Set an option
   */
  public function setOption($option, $value) {
    $this->itemOptions[$option] = $value;
  }
}

/**
 * Hidden item
 */
class FormUIHidden extends FormUITextfield {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'hidden');
  }
}

/**
 * Markup item
 */
class FormUIMarkup extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'markup');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      $this->setOption('markup', $options[0]);
    }
  }
}

/**
 * Select item
 */
class FormUISelect extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'select');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      $this->setOption('items', $options[0]);
    }
  }
}

/**
 * Submit button
 */
class FormUISubmit extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'submit');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      $this->setOption('value', $options[0]);
    }
  }
}

/**
 * Implement a table element
 */
class FormUITable extends FormUIItem {
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
    }
    if (isset($options[1])) {
      $this->setOption('rows', $options[1]);
    }
    return $this;
  }
}

/**
 * Implement a tableselect element
 */
class FormUITableselect extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('attributes', array());
    $this->setOption('type', 'tableselect');
    $this->setOptions(func_get_args());
  }

  /**
   * Set the options
   *
   * Usage: setOptions($header, $options, $empty)
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      $this->setOption('header', $options[0]);
      if (isset($options[1])) {
        $this->setOption('options', $options[1]);
        if (isset($options[2])) {
          $this->setOption('empty', $options[2]);
        }
      }
    }
    return $this;
  }
}

/**
 * Textfield item
 */
class FormUITextfield extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'textfield');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (is_array($options[0])) {
        $options[0] = implode(',', $options[0]);
      }
      $this->setOption('default_value', $options[0]);
    }
    return $this;
  }
}
