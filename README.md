# FormUI

The FormUI is a developer API meant to help build Drupal forms with Drupal's Form API. In short, it's an object-oriented wrapper around the Form API that is boiled down to an actual form array.

This is an alpha release and this API can change at any time! Questions and issues welcome.

## Installation

### Drush

    drush dl formui && drush en formui

### Git

    git clone git@github.com:robballou/formui.git --branch=7.x-1.x formui
    drush en formui

## Usage

    function example_form($form, &$form_state) {
        module_load_include('module', 'formui');
        $formui = new FormUI();

        $options = array('example1' => 'Example 1', 'example2' => 'Example 2');

        $formui
          ->add(
            'some_field',
            $formui->textfield()
              ->setOption('title', 'Some Field')
          )
          ->add('another_field', $formui->select($options))
          ->add('submit', $formui->submit('Submit'));

        return $formui->generate();
    }

For `FormUIItem` instances, you can use `setOption()` or use a method for the option you want to set. For example, to set the title, you can also just run: `$formui->textfield()->title('Thing')`.

## Types

* Checkbox
* Checkboxes
* File
* Hidden
* Managedfile
* Markup
* Select
* Submit
* Table
* Tableselect
* Textarea
* Textfield

If the [Elements module](http://drupal.org/project/elements) is available, you can also use:

* Email

## Making new types

You can load new types by creating your own classes:

```php
class MyCustomItem extends FormUIItem {
  /**
   * Constructor
   */
  public function __construct() {
    parent::__construct();
    $this->setOption('type', 'custom_thing');
  }
}
```

Instead of calling `$formui->thing()` use:

```php
$formui->add('thing', new MyCustomItem());
```

## Options

The `setOption` and `setOptions` methods are wrappers around setting values in the form array for that item. As an example, this Form API code:

```php
$form['thing'] = array(
  '#type' => 'textfield',
  '#title' => t('Thing'),
  '#size' => 25,
);
```

Would be this in FormUI:

```php
$formui->add('thing', $formui->textfield()->setOption('size' => 25))
```

If you want to add something `#ajax` or `#attributes`, you can do that with:

```php
$formui->add(
  'thing',
  $formui->textfield()
    ->setOption('size' => 25)
    ->setOption('attributes' => array('class' => array('thing')))
  );
```

## Fieldsets

You can make fieldsets, too:

```php
$formui
  ->addFieldset('group1')
  // add 'item' to this group
  ->add('group1', 'item', $formui->textfield());
```
