<?php

namespace Drupal\form_render\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements form_reder controller.
 */
class FormRender extends FormBase {

  /**
   * buildForm with content
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $default_value = "";
    $form['keys'] = [
      '#type' => 'textfield',
      '#title' => 'Поисковый запрос',
      '#description' => 'Введите ключевые слова для поиска.',
      '#placeholder' => 'Введите поисковый запрос',
      '#required' => TRUE,
      '#default_value' => $default_value,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => 'Поиск',
    ];

    return $form;
  }

  public function getFormId() {
    return 'form_render';
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
