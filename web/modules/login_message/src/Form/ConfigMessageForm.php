<?php
namespace Drupal\login_message\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigMessageForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_config_login_message';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['help'] = [
      '#markup' => $this->t('This form for config message for
      users when sign in site.'),
    ];

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message for user'),
      '#default_value' => \Drupal::state()->get('login_message'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::state()->set('login_message',$form_state->getValue('message'));
    \Drupal::messenger()->addMessage($this->t('The configuration has been saved.'));
  }

}
