<?php

namespace Drupal\contact_page\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;


class BuildListController extends ControllerBase {

  /**
   * Current User.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $user;

  /**
   * Connection for DB.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): BuildListController {
    $controller = new static(
      $container->get('database'),
      $container->get('current_user'),
    );
    $controller->setStringTranslation($container->get('string_translation'));
    return $controller;
  }

  public function __construct(Connection $database, AccountProxyInterface $user) {
    $this->connection = $database;
    $this->user = $user;
  }

  /**
   * Return page with data table.
   */
  public function buildData() {

    $content['message'] = [
      '#markup' => $this->t('Your message to support'),
    ];

    // Headers of table.
    $headers = [
      $this->t('Date of create'),
      $this->t('Submission category'),
      $this->t('Message'),
    ];

    $query = $this->connection->select('contact_page', 'cp')
      ->fields('cp', ['date_of_create', 'category', 'message'])
      ->condition('user_id', $this->user->id(), '=')
      ->orderBy('date_of_create', 'DESC')
      ->execute();

    $count = 0;
    while($record = $query->fetchAssoc()) {
      $count++;
      $response[$count] = $record;
      $response[$count]['date_of_create'] = date('Y-m-d H:i:s', $response[$count]['date_of_create']);
    }

    $content['table'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $response,
      '#empty' => $this->t('No submitted messages from you.'),
    ];
    return $content;
  }


}
