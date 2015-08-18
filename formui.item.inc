<?php
/**
 * Base class for form API items
 */
class FormUIItem {
  /**
   * Internal array of options for this item
   *
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
   * Set a form item attribute.
   *
   * This will set/override the attribute set.
   */
  public function setAttribute($attribute, $value) {
    if (!isset($this->itemOptions['attributes'])) {
      $this->itemOptions['attributes'] = array();
    }

    $this->itemOptions['attributes'][$attribute] = $value;
    return $this;
  }

  /**
   * Set an option
   */
  public function setOption($option, $value) {
    $this->itemOptions[$option] = $value;
    return $this;
  }

  /**
   * Set multiple options
   */
  public function setOptions($options) {
    foreach ($options as $key => $value) {
      $this->setOption($key, $value);
    }
    return $this;
  }

  /**
   * Set the prefix/suffix for this element based on the provided notation.
   */
  public function wrap($wrap = 'div') {
    // parse the wrap notation
    $mode = 'tag';
    $tag = $id = $class = '';
    $classes = array();

    for ($i = 0, $len = strlen($wrap); $i < $len; $i++) {
      $char = substr($wrap, $i, 1);
      if (!in_array($char, array('.', '#'))) {
        if ($mode === 'tag') {
          $tag .= $char;
        }
        elseif ($mode === 'id') {
          $id .= $char;
        }
        elseif ($mode === 'class') {
          $class .= $char;
        }
      }
      elseif ($char === '.') {
        $mode = 'class';

        // if a class name has been started, add it to the classes
        if ($class) {
          $classes[] = $class;
          $class = '';
        }
      }
      elseif ($char === '#') {
        $mode = 'id';

        // if a class name has been started, add it to the classes
        if ($class) {
          $classes[] = $class;
          $class = '';
        }
      }
    }
    // if a class name has been started, add it to the classes
    if ($class) {
      $classes[] = $class;
      $class = '';
    }

    // save the suffix
    $prefix = '<' . $tag;
    if ($id) {
      $prefix .= ' id="' . $id .'"';
    }
    if ($classes) {
      $prefix .= ' class="' . implode(' ', $classes) . '"';
    }
    $prefix .= '>';
    $this->setOption('prefix', $prefix);

    // save the suffix
    $this->setOption('suffix', '</' . $tag . '>');

    return $this;
  }

}

class FormUIButton extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'button');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('value', $options[0]);
        return $this;
      }

      parent::setOptions($options);
    }
    return $this;
  }
}

/**
 * Checkbox item
 */
class FormUICheckbox extends FormUIItem {

  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'checkbox');
  }
}

/**
 * Checkboxes item
 */
class FormUICheckboxes extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'checkboxes');
  }

  /**
   * Set the options
   */
  public function setOptions($options) {
    if (count($options) > 0) {
      if (isset($options[0])) {
        $this->setOption('options', $options[0]);
      }
      else {
        parent::setOptions($options);
      }

      if (isset($options[1])) {
        $this->setOption('default_value', $options[1]);
      }
    }
    return $this;
  }
}

/**
 * Add an email field.
 *
 * Fallsback to textfield.
 */
class FormUIEmail extends FormUITextfield {
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

/**
 * File upload item
 */
class FormUIFile extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'file');
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
 * File upload item
 */
class FormUIManagedFile extends FormUIFile {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'managed_file');
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
      if (isset($options[0])) {
        $this->setOption('markup', $options[0]);
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }
}

/**
 * Radios item
 */
class FormUIRadios extends FormUICheckboxes {
  /**
   * Constructor.
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'radios');
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
      if (isset($options[0])) {
        $this->setOption('options', $options[0]);
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }
}

/**
 * Submit button
 */
class FormUISubmit extends FormUIButton {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOptions(func_get_args());
    $this->setOption('type', 'submit');
  }
}

/**
 * Implement a table element
 */
class FormUITable extends FormUIItem {
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
      if (isset($options[0])) {
        $this->setOption('header', $options[0]);
        if (isset($options[1])) {
          $this->setOption('options', $options[1]);
          if (isset($options[2])) {
            $this->setOption('empty', $options[2]);
          }
        }
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }
}

/**
 * Textarea element
 */
class FormUITextarea extends FormUITextfield {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'textarea');
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
      if (isset($options[0])) {
        if (is_array($options[0])) {
          $options[0] = implode(',', $options[0]);
        }
        $this->setOption('default_value', $options[0]);
      }
      else {
        parent::setOptions($options);
      }
    }
    return $this;
  }
}
