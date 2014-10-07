# FormUI

The FormUI is a developer API meant to help build Drupal forms with Drupal's Form API. In short, it's an object-oriented wrapper around the Form API that is boiled down to an actual form array.

This is an alpha "release" and this API can change at any time!

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

You can also use fieldsets:

    $formui
      ->addFieldset('fieldset_name')
      ->add('fieldset_name', 'some_field', $formui->textfield());

## Types

* Checkbox
* Checkboxes
* File
* Hidden
* Markup
* Select
* Submit
* Table
* Tableselect
* Textfield

## Making new types

You can load new types by creating your own classes:

    class MyCustomItem extends FormUIItem {
      /**
       * Constructor
       */
      public function __construct() {
        parent::__construct();
        $this->setOption('type', 'custom_thing');
      }
    }

Instead of calling `$formui->thing()` use:

    $formui->add('thing', new MyCustomItem());
