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
              ->setOption('label', 'Some Field')
          )
          ->add('another_field', $formui->select($options))
          ->add('submit', $formui->submit());

        return $formui->generate();
    }

## Types

* Checkbox
* Checkboxes
* Hidden
* Markup
* Select
* Submit
* Table
* Tableselect
* Textfield
