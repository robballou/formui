<?php

namespace Drupal\formui;

/**
 * Parent Form UI class
 */
class Form {
  /**
   * Container for the items in this form.
   *
   * @var array
   */
  public $items = array();

  /**
   * The current item weight, if we're autosetting it.
   *
   * @var mixed
   */
  public $weight = NULL;

  /**
   * Weight increment.
   *
   * @var int
   */
  public $weightIncrement = 10;

  /**
   * Create the form
   */
  public function __construct($form = array()) {
    $this->items = $form;
  }

  /**
   * Update the form and return the generated array.
   *
   * Updates the form without needing to manually create a Form object and
   * generate the form array. The updates can either be an array of updates or
   * a callable that performs the updates on the generated Form object.
   *
   * The array option would look like:
   *
   *     Form::update($form, [
   *       ['addSubmitHandler', 'my_submit_handler'],
   *     ]);
   *
   * The callback option would look like:
   *
   *     Form::update($form, function($form) {
   *       $form->addSubmitHandler('my_submit_handler');
   *     });
   *
   * @param array $form
   *   The form array to update.
   * @param mixed $updates
   *   Either an array of updates or a callback that will receive the
   *   \Drupal\formui\Form object.
   *
   * @return array
   *   The updated form array.
   */
  public static function update(array &$form, $updates) {
    $formui = new static($form);

    if (is_array($updates)) {
      foreach ($updates as $update) {
        $method = array_shift($update);
        $arguments = $update;
        call_user_func_array([$formui, $method], $arguments);
      }
    }
    elseif (is_callable($updates)) {
      $updates($formui);
    }

    $form = $formui->generate();
    return $form;
  }

  /**
   * Handle calling for items.
   *
   * You can call $form->[type]() to generate a form
   */
  public function __call($method, $arguments) {
    $add = FALSE;
    if (preg_match('/^add(.+)$/', $method, $matches)) {
      $add = TRUE;
      $method = $matches[1];
      $key = array_shift($arguments);
    }

    $class_name = '\Drupal\formui\Item\\' . ucfirst($method);
    if (class_exists($class_name)) {
      $instance = new $class_name();
      $instance->setOptions($arguments);
      if ($add) {
        return $this->add($key, $instance);
      }
      return $instance;
    }
    throw new \Exception('Method or form item does not exist: ' . $class_name);
  }

  /**
   * Add an item.
   *
   * This can be used in one of three ways:
   *
   * 1. Add a FormUIItem:
   *
   *     $item = new FormUIMarkup('<p>markup</p>');
   *     $form_ui->add('key', $item);
   *
   * 2. Use a FormUIItem generator:
   *
   *     $form_ui->add('key', $form_ui->markup('<p>markup</p>'));
   *
   * 3. For markup, you can use a shortcut:
   *
   *     $form_ui->add('key', '<p>markup</p>');
   */
  public function add($key, $item) {
    $args = func_get_args();
    $arg_count = count($args);

    switch ($arg_count) {
      case 2:
        $fieldset = NULL;
        $key = $args[0];
        $item = $args[1];
        break;

      case 3:
        $fieldset = $args[0];
        $key = $args[1];
        $item = $args[2];
        break;
    }

    if (is_string($item)) {
      $item = new \Drupal\formui\Item\Markup($item);
    }

    if ($this->weight !== NULL) {
      $item->setOption('weight', $this->weight);
      $this->incrementWeight();
    }

    $this->items[$key] = $item;
    if ($fieldset) {
      if (is_object($this->items[$fieldset])) {
        $this->items[$fieldset]->$key = $item;
      }
      elseif (is_array($this->items[$fieldset]) && isset($this->items[$fieldset]['widget'])) {
        $index = array_reduce(array_keys($this->items[$fieldset]['widget']), function($carry, $item) {
          if (is_int($item) && $item > $carry) {
            return $item;
          }
          return $carry;
        }, 0);

        $this->items[$fieldset]['widget'][($index + 1)] = $item->generate();
      }
      else {
        $this->items[$fieldset][$key] = $item->generate();
      }
      unset($this->items[$key]);
    }
    return $this;
  }

  /**
   * Add a class or classes to the form attributes.
   *
   * @param mixed $class
   *   A single class name string or an array of class names.
   */
  public function addClass($class) {
    if (!isset($this->items['#attributes'])) {
      $this->items['#attributes'] = [];
    }
    if (!isset($this->items['#attributes']['class'])) {
      $this->items['#attributes']['class'] = [];
    }
    if (!in_array($this->items['#attributes']['class'])) {
      if (!is_array($class)) {
        $class = [$class];
      }
      foreach ($class as $this_class) {
        $this->items['#attributes']['class'][] = $this_class;
      }
    }
    return $this;
  }

  /**
   * Add a container.
   */
  public function addContainer($name, array $options = array()) {
    $this->add($name, $this->container($options));
    return $this;
  }

  /**
   * Add a fieldset
   */
  public function addFieldset($name, array $options = array()) {
    $this->add($name, $this->fieldset($options));
    return $this;
  }

  /**
   * Append a submit handler if it doesn't already exist.
   */
  public function addSubmitHandler($handler) {
    $this->prepareSubmitHandlers();

    $submit_handlers =& $this->items['#submit'];
    if (isset($this->items['actions']['submit']['#submit']) && is_array($this->items['actions']['submit']['#submit'])) {
      $submit_handlers =& $this->items['actions']['submit']['#submit'];
    }

    // check that we're not adding redundant submit handlers
    if (in_array($handler, $submit_handlers)) {
      return $this;
    }

    array_push($submit_handlers, $handler);
    return $this;
  }

  /**
   * Append a validate handler if it doesn't already exist.
   */
  public function addValidateHandler($handler) {
    $this->prepareValidateHandlers();

    $validate_handlers =& $this->items['#validate'];
    if (isset($this->items['actions']['validate'])) {
      $validate_handlers =& $this->items['actions']['validate']['#validate'];
    }

    // check that we're not adding redundant validate handlers
    if (in_array($handler, $validate_handlers)) {
      return $this;
    }

    array_push($validate_handlers, $handler);
    return $this;
  }

  /**
   * Generate the Drupal compatible form API array
   */
  public function generate() {
    $form = array();
    foreach ($this->items as $key => $item) {
      if (is_array($item) && !$this->isOption($key)) {
        $form[$key] = array();
        foreach ($item as $item_key => $item_value) {
          // ignore #keys and $item_values that are arrays
          $form[$key][$item_key] = $item_value;
          if (!$this->isOption($item_key) && !is_array($item_value)) {
            $form[$key][$item_key] = $item_value->generate();
          }
        }
      }
      else {
        $form[$key] = $item;
        if (!$this->isOption($key)) {
          $form[$key] = $item->generate();
        }
      }
    }
    return $form;
  }

  /**
   * Is this thing a FAPI option
   *
   * @param string $key
   *   A string.
   *
   * @return bool
   *   TRUE if this is an option key
   */
  public function isOption($key) {
    return substr($key, 0, 1) === '#';
  }

  /**
   * Check if the item exists.
   *
   * @param string $key
   *   The item key to check.
   *
   * @return bool
   *   TRUE if the key exists, FALSE if not.
   */
  public function itemExists($key) {
    return isset($this->items[$key]);
  }

  /**
   * Increment the weight.
   */
  public function incrementWeight() {
    $this->weight += $this->weightIncrement;
    return $this;
  }

  /**
   * Make sure the form has submit handlers.
   *
   * Used internally, not chainable.
   */
  public function prepareSubmitHandlers() {
    if (!isset($this->items['actions']['submit']) && !isset($this->items['#submit'])) {
      $this->items['#submit'] = array();
    }
  }

  /**
   * Insert a submit handler at the start of the list
   */
  public function prependSubmitHandler($handler) {
    $this->prepareSubmitHandlers();

    $submit_handlers =& $this->items['#submit'];
    if (isset($this->items['actions']['submit'])) {
      $submit_handlers =& $this->items['actions']['submit']['#submit'];
    }

    array_unshift($handler, $submit_handlers);
    return $this;
  }

  /**
   * Prepare the validate handlers.
   */
  public function prepareValidateHandlers() {
    if (!isset($this->items['actions']['validate']) && !isset($this->items['#validate'])) {
      $this->items['#validate'] = array();
    }
  }

  /**
   * Remove a class or classes from the form attributes.
   */
  public function removeClass($class) {
    if (isset($this->items['#attributes']['class'])) {
      if (!is_array($class)) {
        $class = [$class];
      }
      $this->items['#attributes']['class'] = array_filter($this->items['#attributes']['class'], function($item) use ($class) {
        return in_array($item, $class);
      });
    }
  }

  /**
   * Set an existing item's options.
   *
   * @param string $item
   *   The item array key. This can also be a period-separated list, ex.
   *   'items.item'.
   * @param string $option
   *   The option to set.
   * @param mixed $value
   *   The option value.
   */
  public function set($item, $option, $value) {
    $parts = explode('.', $item);
    $this_item =& $this->items;
    foreach ($parts as $part) {
      if (!isset($this_item[$part])) {
        if (isset($this_item['#' . $part])) {
          $part = '#' . $part;
        }
        else {
          return $this;
        }
      }
      $this_item =& $this_item[$part];
    }
    $this_item[$option] = $value;
    return $this;
  }

  /**
   * Set a form option
   */
  public function setOption($option, $value) {
    $option = '#' . $option;
    $this->items[$option] = $value;
    return $this;
  }

  /**
   * Set multiple options
   */
  public function setOptions($options) {
    foreach ($options as $option => $value) {
      $this->setOption($option, $value);
    }
    return $this;
  }

  /**
   * Set the weight.
   */
  public function setWeight($weight) {
    $this->weight = $weight;
    return $this;
  }

}
