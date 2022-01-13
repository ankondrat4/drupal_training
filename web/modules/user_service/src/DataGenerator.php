<?php

namespace Drupal\user_service;

use Drupal\Core\Database\Connection;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class DataGenerator
 *
 * @package Drupal\user_service
 */

class DataGenerator {

  protected Connection $connection;
  protected AccountInterface $currentUser;
  protected EntityTypeManagerInterface $entityTypeManager;

  public function __construct(Connection $connection, AccountInterface $currentUser,
                              EntityTypeManagerInterface $entityTypeManager) {
    $this->connection = $connection;
    $this->currentUser = $currentUser;
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Generate count of active user
   */
  public function getActiveUsers() {
    return $this->connection->select('users_field_data')
      ->condition('status','1','=')
      ->countQuery()
      ->execute()
      ->fetchField();
  }

  /**
   * Find position of registration user
   */
  public function getPositionRegistration(): int {
    $user = $this->currentUser->id();
    $result = $this->connection->select('users_field_data', 't')
      ->fields('t',['uid','name'])
      ->orderBy('t.created')
      ->execute();
    $info = $result->fetchAll();
    $pos = 0;
    $pos_current_user = 0;
    foreach ($info as $key) {
      $pos++;
      if ($key->uid == $user) {
        $pos_current_user = $pos;
      }
    }
    return $pos_current_user;
  }

  /**
   * Return random node in teaser type format.
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getNode(): array {
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple();
    $id_node = array_rand($nodes,1);
    $node = $this->entityTypeManager->getStorage('node')->load($id_node);
    return $this->entityTypeManager->getViewBuilder('node')->view($node,'teaser');
  }
}

