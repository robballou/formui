# FormUI

The FormUI is a developer API meant to help build Drupal forms with Drupal's Form API. In short, it's an object-oriented wrapper around the Form API that is boiled down to an actual form array.

This is an alpha release and this API can change at any time! Questions and issues welcome.

## Installation

### Drush

    drush dl formui && drush en formui

### Git

    git clone git@github.com:robballou/formui.git --branch=8.x-1.x formui
    drush en formui

## Usage

```php
use Drupal\formui\Form;
function example_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  module_load_include('module', 'formui');
  $formui = new Form();

  $options = array('example1' => 'Example 1', 'example2' => 'Example 2');

  $formui
    ->add(
      'some_field',
      $formui->textfield()
        ->setOption('title', 'Some Field')
        ->setAttribute('class', array('some-class'))
    )
    ->add('another_field', $formui->select($options))
    ->add('submit', $formui->submit('Submit'));

  return $formui->generate();
}
```

For `FormUIItem` instances, you can use `setOption()` or use a method for the option you want to set. For example, to set the title, you can also just run: `$formui->textfield()->title('Thing')`.

You can also use the new `Form::update()` pattern as well. This will eliminate the need to create the form object and return the array.

```php
use Drupal\formui\Form;
function example_form_alter(&$form, \Drupal\Core\Form\FormStateInterface $form_state) {
  Form::update($form, function($form) use ($form_state) {
    $options = array('example1' => 'Example 1', 'example2' => 'Example 2');

    $formui
      ->add(
        'some_field',
        $formui->textfield()
          ->setOption('title', 'Some Field')
          ->setAttribute('class', array('some-class'))
      )
      ->add('another_field', $formui->select($options))
      ->add('submit', $formui->submit('Submit'));
  });
}
```

## Types

* Checkbox
* Checkboxes
* Container
* Fieldset
* File
* Hidden
* Managedfile
* Markup
* Radios
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

## Fieldsets and containers

You can make fieldsets (or containers), too:

```php
$formui
  ->addFieldset('group1')
  ->addContainer('group2')
  // add 'item' to this group
  ->add('group1', 'item', $formui->textfield())
  ->add('group2', 'item2', $formui->textfield());
```

## Wrapping form items

You can wrap form items (set the `#prefix` and `#suffix` options) with the `wrap()` method:

```php
// wrap the textfield with <div class="my-class"></div>
$formui
  ->add('example', $formui->textfield()->wrap('div.my-class'));
```

This defaults wrapping with a plain `div`.

## Automatically set item weight

FormUI can make managing form item weights easier too:

```php
$formui
  // turns on the weight setting plus sets the default weight
  ->setWeight(10)
  // will get a #weight of 10
  ->add('item1', $formui->textfield())
  // will get a #weight of 20
  ->add('item2', $formui->textfield())
```

## Debug form IDs

Often you want to create a form alter for a specific form ID, so FormUI also includes a shortcut for finding form id's on a page. If you have devel enabled, you can add `?debug_form_id=1` to your query string to get a DPM for each form ID on a page.
