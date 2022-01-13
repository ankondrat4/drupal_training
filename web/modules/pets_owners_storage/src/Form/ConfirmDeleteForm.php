<?php

namespace Drupal\pets_owners_storage\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class ConfirmDeleteForm extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   * @var int
   */
  protected $id;

  /*
   * @inheritdoc
   */
  public function buildForm(array $form, FormStateInterface $form_state, string $id = NULL) {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /*
   * @inheritdoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->delete($this->id);
    $form_state->setRedirect('pets_owners_storage.main');
    $text = 'Record id = ' . $this->id . ' was deleted from database.';
    \Drupal::messenger()->addMessage($text);
  }

  /*
   * @inheritdoc
   */
  public function getFormId() : string {
    return "confirm_delete_form";
  }

  /*
   * @inheritdoc
   */
  public function getCancelUrl() {
    return new Url('pets_owners_storage.edit', ['id' => $this->id]);
  }

  /*
   * @inheritdoc
   */
  public function getQuestion() {
    return $this->t('Do you want to delete record -> %id?', ['%id' => $this->id]);
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
    }
    else {
      // page no found - 404
      throw new NotFoundHttpException();
    }
  }
}
