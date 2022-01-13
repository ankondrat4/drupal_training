<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pets_owners_storage\PetsOwnersStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Controller for pets_owners_storage.
 */
class PetsOwnersStorageController extends ControllerBase {

  /**
   * The repository for our specialized queries.
   *
   * @var \Drupal\pets_owners_storage\PetsOwnersStorageRepository
   */
  protected $repository;

  /**
   * create DB
   */
  public static function create(ContainerInterface $container) {
    $controller = new static($container->get('pets_owners_storage.repository'));
    $controller->setStringTranslation($container->get('string_translation'));
    return $controller;
  }

  /**
   * Construct a new controller.
   *
   * @param \Drupal\pets_owners_storage\PetsOwnersStorageRepository $repository
   *   The repository service.
   */
  public function __construct(PetsOwnersStorageRepository $repository) {
    $this->repository = $repository;
  }

  /**
   * Render a list of entries in the database.
   */
  public function entryList() {
    $content = [];
    $content['message'] = [
      '#markup' => $this->t('List of all entries in the database from submitted '),
    ];
    // Render link.
    $content['link'] = [
      '#title' => $this->t('Pets Owners Form'),
      '#type' => 'link',
      '#url' => Url::fromRoute('pets_owners_form.main'),
    ];
    $rows = [];
    $headers = [
      $this->t('Id'),
      $this->t('Name'),
      $this->t('Gender'),
      $this->t('Prefix'),
      $this->t('Age'),
      $this->t('Fathers name'),
      $this->t('Mothers name'),
      $this->t('Some pets 1'),
      $this->t('Some pets 2'),
      $this->t('E-mail'),
      $this->t('Delete'),
      $this->t('Edit'),
    ];
    $entries = $this->repository->load();
    foreach ($entries as $value) {
      //$delete = Url::fromUserInput('/pets_owners_storage/delete/'.$value->id);
      $edit = Url::fromUserInput('/pets_owners_storage/edit/'.$value->id);
      $id = $value->id;
      $rows[] = [
        $value->id,
        $value->name,
        $value->gender,
        $value->prefix,
        $value->age,
        $value->fathersname,
        $value->mothersname,
        $value->somepets1,
        $value->somepets2,
        $value->email,
        'Delete'=>$this->t('<a href="/pets_owners_storage/modal_form_delete/'.$id.'" class="use-ajax" data-dialog-type="modal"}>Delete</a>'),
        //'Delete'=>Link::fromTextAndUrl('Delete', $delete),
        'Edit'=>Link::fromTextAndUrl('Edit', $edit)];
    }

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No entries available.'),
    ];
    // Don't cache this page.
    $content['#cache']['max-age'] = 0;

    return $content;
  }

  /**
   * Delete item from BD without Module Form
   */
  public function deleteItem(int $id) {
    $select = $this->repository->select($id);
    $data = $select->fetchField();
    if ($data != false) {
      $this->repository->delete($id);
      return $this->entryList();
    }
    else return [
      '#markup' => '<p>' . $this->t('404 not found') . '</p>',
    ];
  }

}
