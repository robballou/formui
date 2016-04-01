<?php

namespace Drupal\formui;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\formui\Form;

/**
 * Create a form base using FormUI.
 */
abstract class FormUiBase extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $formui = new Form($form);

    $args = [$formui, $form_state];
    $function_args = func_get_args();
    $args = $args + array_slice($function_args, 2);
    call_user_func_array([$this, 'buildFormUi'], $args);
    $form = $formui->generate();
  }

  /**
   * Build the form using form UI.
   */
  public function buildFormUi(Form $form, FormStateInterface $form_state) {
  }

}
