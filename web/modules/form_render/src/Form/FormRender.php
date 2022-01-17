<?php

namespace Drupal\form_render\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\Messenger;

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

    // Загружаем настройки модули из формы CollectPhoneSettings.
    $config = \Drupal::config('form_render.settings');
    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Your phone number'),
      '#default_value' => $config->get('phone')
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
    // Выводим имя введенное в форме.
    \Drupal::messenger()->addMessage($this->t('Your name is @name', array('@name' => $form_state->getValue('name'))));
  }

}
