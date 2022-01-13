<?php
namespace Drupal\login_only_mode\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class ConfigLoginOnlyModeForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_config_login_only_mode';
  }

  /**
   * {@inheritdoc}
   */
  public function getEditableConfigNames() {
    return 'login_only_mode.resource';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = \Drupal::config('login_only_mode.resource');

    $form['help'] = [
      '#markup' => $this->t('This form for set the site into Login Only mode.'),
    ];

    $form['enabled'] = [
      '#type' => 'radios',
      '#title' => $this->t('Don you want enable?'),
      '#options' => ['Yes' => $this->t('Yes'), 'No' => $this->t('No')],
      '#default_value' => $site_config->get('enabled') == 0 ? 'No' : 'Yes',
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
    $site_config = \Drupal::configFactory()->getEditable('login_only_mode.resource');
    $site_config -> set('enabled', $form_state->getValue('enabled') == 'Yes' ? 1 : 0)
      -> save();

    parent::submitForm($form, $form_state);
  }

}
