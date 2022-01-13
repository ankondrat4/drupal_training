<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Show textfields or link google based on AJAX-enabled checkbox clicks.
 */
class AjaxCheckboxForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_checkbox_form';
  }

  /**
   * {@inheritdoc}
   *
   * This form has two checkboxes which the user can check in order to then doing next:
   * When checkbox #1 is checked the fieldset with two additional textfields are shown,
   * when we check another checkbox - the URL to google.com is shown at the bottom of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This form demonstrates changing the status of form elements through AJAX requests.'),
    ];
    $form['first'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show fieldset with two additional textfields'),
      '#ajax' => [
        'callback' => '::textfieldsCallback',
        'wrapper' => 'textfields-container',
        'effect' => 'fade',
      ],
    ];
    $form['second'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show the URL to google.com'),
      '#ajax' => [
        'callback' => '::textfieldsCallback',
        'wrapper' => 'textfields-container',
        'effect' => 'fade',
      ],
    ];

    // Wrap textfields in a container. This container will be replaced through
    // AJAX.
    $form['textfields_container'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'textfields-container'],
    ];

    // This form is rebuilt on all requests, so whether or not the request comes
    // from AJAX, we should rebuild everything based on the form state.
    // Checkbox values are expressed as 1 or 0, so we have to be sure to compare
    // type as well as value.
    if ($form_state->getValue('first', NULL) === 1) {
      $form['textfields_container']['textfields'] = [
        '#type' => 'fieldset',
        '#title' => $this->t("Generated text fields for first and last name"),
        '#description' => $this->t('This is where we put automatically generated textfields'),
      ];
      $form['textfields_container']['textfields']['first_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Name'),
        '#required' => TRUE,
      ];
      $form['textfields_container']['textfields']['last_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last Name'),
        '#required' => TRUE,
      ];
    }
    if ($form_state->getValue('second', NULL) === 1) {
      $form['textfields_container']['textfields'] = [
        '#type' => 'item',
        '#description' => $this->t('<a href = "http://google.com">Go to GOOGLE<a/>'),
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Callback for ajax_example_autotextfields.
   *
   * Selects the piece of the form we want to use as replacement markup and
   * returns it as a form (renderable array).
   */
  public function textfieldsCallback($form, FormStateInterface $form_state) {
    return $form['textfields_container'];
  }

}
