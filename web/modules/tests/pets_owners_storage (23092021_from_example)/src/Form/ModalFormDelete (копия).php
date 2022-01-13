<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Url;

/**
 * Implements the ModalForm form for delete item.
 */
class ModalFormDelete extends FormBase {

  public static function create(ContainerInterface $container) {
    // Create a new form object and inject its services.
    $form = new static();
    $form->setRequestStack($container->get('request_stack'));
    $form->setStringTranslation($container->get('string_translation'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  public function getFormId() {
    return 'form_pets_owners_storage_modal_form';
  }

  /**
   * Helper method so we can have consistent dialog options.
   * @return string[]  An array of jQuery UI elements to pass on to our dialog form.
   */
  protected static function getDataDialogOptions() {
    return [
      'width' => '50%',
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    // Add the core AJAX library.
    $form['#attached']['library'][] = 'core/drupal.ajax';

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('You are really want delete record with id -> '.$id.'?'),
    ];

    //id item for delete
    $form['id'] = [
      '#type' => 'hidden',
      '#value' => $id,
    ];

    // Group submit handlers in an actions element with a key of "actions"
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button=Yes that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Yes'),
    ];

    // Add a cancel button=No that handles the submission of the form.
    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value' => $this->t('No'),
      //'#attributes' => array('onClick' => 'history.go(-2); return true;'),
      //'#submit' => array('post_owners_storage_form_cancel'),
      /*'#attributes' => [
        'onclick' => 'this.dialog.close();',
      ],*/
      '#attributes' => [
        'class' => [
          'use-ajax',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'closeModalForm'],
        'event' => 'click',
      ],
      /*'#attributes' => [
        'onclick' => 'this.dialog.reset(); return false;',
      ],*/
    ];

    return $form;
  }
  /**
   * Custom cancel button callback.
   */
public function post_owners_storage_form_cancel($form, $form_state) {
    $form_state->setRedirect('pets_owners_storage.main');
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->delete($form_state->getValue('id'));
    $form_state->setRedirect('pets_owners_storage.main');
  }

  /**
   * for close button
   */
  public function closeModalForm() {
    /*$command = new CloseModalDialogCommand();
    $response = new AjaxResponse();
    $response->addCommand($command);
    return $response;*/
    return Url::fromRoute('pets_owners_storage.main');
  }

  /*
   * delete record from BD
   */
  public function delete($id) {
    $query = \Drupal::database();
    $select = $query->select('pets_owners_storage')
    ->fields('pets_owners_storage')
    ->condition('id', $id)
    ->execute();
    $data = $select->fetchField();
    if ($data != false) {
      $query->delete('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
      $text = 'Record id = ' . $id . ' was deleted from database.';
      \Drupal::messenger()->addMessage($text);
    }
    else {
      // page no found - 404
      throw new NotFoundHttpException();
    }
  }

}
