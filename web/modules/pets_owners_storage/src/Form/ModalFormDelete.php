<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Implements the ModalForm form for delete item.
 */
class ModalFormDelete extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_pets_owners_storage_modal_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   * @param string|null $id
   *   Record ID to delete.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    // Prevent form building if ID hasn't found in DB.
    $query = \Drupal::database()
      ->select('pets_owners_storage')
      ->fields('pets_owners_storage')
      ->condition('id', $id)
      ->execute();

    if (empty($query->fetchField())) {
      throw new NotFoundHttpException();
    }

    // Add the core AJAX library.
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    // Define generic form ID for errors handling.
    $form['#prefix'] = '<div id="pets_owners_modal_delete">';
    $form['#suffix'] = '</div>';

    // Description.
    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('You are really want delete record with id -> '.$id.'?'),
    ];

    // ID item for delete.
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
      '#attributes' => [
        'class' => [
          'use-ajax',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'submitModalFormAjax'],
        'event' => 'click',
      ],
    ];

    // Add a cancel button=No that handles the submission of the form.
    $form['actions']['cancel'] = [
      '#type' => 'submit',
      '#value' => $this->t('No'),
      '#attributes' => [
        'class' => [
          'use-ajax',
        ],
      ],
      '#ajax' => [
        'callback' => [$this, 'closeModalForm'],
        'event' => 'click',
      ],
    ];

    return $form;
  }

  /**
   * Delete AJAX handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function submitModalFormAjax(array $form, FormStateInterface $form_state): AjaxResponse
  {
    $response = new AjaxResponse();

    // If there are any form errors, re-display the form.
    if ($form_state->hasAnyErrors()) {
      $response->addCommand(new ReplaceCommand('#pets_owners_modal_delete', $form));
    }
    else {
      // Handle delete action.
      $this->delete($form_state->getValue('id'));
      //Close the modal.
      $command = new CloseModalDialogCommand();
      $response->addCommand($command);
      $url = Url::fromRoute('pets_owners_storage.main')->toString();
      $response->addCommand(new RedirectCommand($url));
    }
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Close modal form handler.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response.
   */
  public function closeModalForm(): AjaxResponse
  {
    $command = new CloseModalDialogCommand();
    $response = new AjaxResponse();
    $response->addCommand($command);
    return $response;
  }

  /**
   * Delete record by ID.
   *
   * @param string $id
   *   Record ID to delete.
   */
  public function delete(string $id) {
    \Drupal::database()
      ->delete('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
    $text = 'Record id = ' . $id . ' was deleted from database.';
    \Drupal::messenger()->addMessage($text);
  }

}
