<?php

namespace Drupal\formui;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

use Drupal\formui\Form;

/**
 * Create a form base using FormUI.
 */
abstract class FormUiBase extends FormBase {

  public $form = NULL;

  /**
   *
   */
  public function __call($method, $arguments) {
    return call_user_func_array([$this->form, $method], $arguments);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $this->form = new Form($form);

    $args = array_slice(func_get_args(), 2);
    $this->buildFormUi($form_state, $args);

    $form = $this->form->generate();
    // dpm($form);
    return $form;
  }

  /**
   * Build the form using form UI.
   *
   * The form instance can be accessed at `$this->formui`.
   */
  public function buildFormUi(FormStateInterface $form_state) {
  }

}
