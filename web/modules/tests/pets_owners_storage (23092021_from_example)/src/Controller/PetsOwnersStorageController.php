<?php

namespace Drupal\pets_owners_storage\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\pets_owners_storage\PetsOwnersStorageRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * create DB}
   */
  public static function create(ContainerInterface $container) {
    $controller = new static($container->get('pets_owners_storage.repository'));
    $controller->setStringTranslation($container->get('string_translation'));
    return $controller;
  }

  /**
   * Construct a new controller.
   *
   * @param \Drupal\dbtng_example\DbtngExampleRepository $repository
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
      '#markup' => $this->t('Generate a list of all entries in the database. There is no filter in the query.'),
    ];

    $rows = [];
    $headers = [
      $this->t('Id'),
      $this->t('uid'),
      $this->t('Name'),
      $this->t('Surname'),
      $this->t('Age'),
    ];

    $entries = $this->repository->load();

    foreach ($entries as $entry) {
      // Sanitize each entry.
      $rows[] = array_map('Drupal\Component\Utility\Html::escape', (array) $entry);
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

}
