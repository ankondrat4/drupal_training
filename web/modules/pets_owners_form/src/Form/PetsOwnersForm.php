<?php

namespace Drupal\pets_owners_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Implements pets_owners_form controller.
 */
class PetsOwnersForm extends FormBase {

  /**
   * buildForm with content
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Input data about pets and owners:'),
    ];

    //name
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
    ];

    // Gender radios.
    $form['gender']['active'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gender'),
      '#options' => ['Male' => $this->t('Male'), 'Female' => $this->t('Female'), 'Unknown' => $this->t('Unknown')],
      '#default_value' => 'Unknown',
    ];

    // Prefix select.
    $form['prefix'] = [
      '#type' => 'select',
      '#title' => $this->t('Prefix:'),
      '#options' => [
        'mr' => $this->t('mr'),
        'mrs' => $this->t('mrs'),
        'ms' => $this->t('ms'),
      ],
      '#empty_option' => $this->t('-select-'),
    ];

    // Age
    $form['age'] = [
      '#type' => 'number',
      '#title' => $this->t('Age'),
    ];

    // Parents (fieldset collapsed)
    $form['parents'] = [
      '#type' => 'details',
      '#title' => 'Parents Info',
    ];

    $form['parents']['f_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Father`s name'),
    ];

    $form['parents']['m_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Mother`s name'),
      '#tree' => TRUE,
    ];

    $form['somepets'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Have you some pets?'),
    );

    ///Start Ajax Form for names pets
    // Gather the number of names in the form already.
    $num_names = $form_state->get('num_names');
    // We have to ensure that there is at least one name field.
    if ($num_names === NULL) {
      $num_names = 1;
    }

    $form['names_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Names(s) of your pet(s)'),
      '#prefix' => '<div id="names-fieldset-wrapper">',
      '#suffix' => '</div>',
      '#states' => array(       //if checked
        'visible' => array(
          ':input[name="somepets"]' => array('checked' => TRUE),
        ),
      ),
    ];

    for ($i = 0; $i < $num_names; $i++) {
      $form['names_fieldset']['name'][$i] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
      ];
    }

    $form['names_fieldset']['actions'] = [
      '#type' => 'actions',
    ];

    $form['names_fieldset']['actions']['add_name'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add one more'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'names-fieldset-wrapper',
      ],
    ];

    // If there is more than one name, add the remove button.
    if ($num_names > 1) {
      $form['names_fieldset']['actions']['remove_name'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove one'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'names-fieldset-wrapper',
        ],
      ];
    }
    ///End Ajax Form for names pets

    // Email
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#description' => $this->t('Submit, #type = submit'),
    ];

    // Add a reset button that handles the submission of the form.
    $form['actions']['reset'] = [
      '#type' => 'button',
      '#button_type' => 'reset',
      '#value' => $this->t('Reset'),
      '#description' => $this->t('Submit, #type = button, #button_type = reset, #attributes = this.form.reset();return false'),
      '#attributes' => [
        'onclick' => 'this.form.reset(); return false;',
      ],
    ];

    return $form;
  }

  public function getFormId() {
    return 'my_first_pets_owners_form';
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $age = $form_state->getValue('age');
    $name = $form_state->getValue('name');
    $email = $form_state->getValue('email');
    if ($age < 0 || $age > 120) {
      // Set an error for the form element with a key of "age".
      $form_state->setErrorByName('age', $this->t('The age should be more than 0 and less than 120.'));
    }
    if (strlen($name) > 100) {
      // Set an error for the form element with a key of "age".
      $form_state->setErrorByName('name', $this->t('The name should be 100 symbols max.'));
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Invalid email format'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $parents = $form_state->getValue('parents');
    $f_name = $parents['f_name'];
    $m_name = $parents['m_name'];
    $somepets = $values["names_fieldset"]["name"];
    if (isset($somepets[0])) $somepets1 = $somepets[0];
    else $somepets1 ='';
    if (isset($somepets[1])) $somepets2 = $somepets[1];
    else $somepets2 ='';
    $genders = $form_state->getValue('gender');
    $gender = $genders['active'];

    // Message where submitted.
    $this->messenger()->addMessage($this->t('Thank you'));

    //Insert data into our schema pets_owners_storage.
    $entry =
      [
        'name' => $values['name'],
        'prefix' => $values['prefix'],
        'gender' => $gender,
        'age' => $values['age'],
        'fathersname' => $f_name,
        'mothersname' => $m_name,
        'somepets1' => $somepets1,
        'somepets2' => $somepets2,
        'email' => $values['email'],
      ];
    $connection = \Drupal::database();
    $connection->insert('pets_owners_storage')->fields($entry)->execute();
  }

  /**
   * Callback for both ajax-enabled buttons.
   * Selects and returns the fieldset with the names in it.
   */
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['names_fieldset'];
  }

  /**
   * Submit handler for the "add-one-more" button.
   *
   * Increments the max counter and causes a rebuild.
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    $add_button = $name_field + 1;
    $form_state->set('num_names', $add_button);
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

  /**
   * Submit handler for the "remove one" button.
   *
   * Decrements the max counter and causes a form rebuild.
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $name_field = $form_state->get('num_names');
    if ($name_field > 1) {
      $remove_button = $name_field - 1;
      $form_state->set('num_names', $remove_button);
    }
    // Since our buildForm() method relies on the value of 'num_names' to
    // generate 'name' form elements, we have to tell the form to rebuild. If we
    // don't do this, the form builder will not call buildForm().
    $form_state->setRebuild();
  }

}
