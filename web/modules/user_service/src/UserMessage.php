<?php

namespace Drupal\user_service;

/**
 * Class UserMessage
 *
 * @package Drupal\user_service
 */
class UserMessage {

  private DataGenerator $data_generator;

  public function __construct(DataGenerator $data_generator) {
    $this->data_generator = $data_generator;
  }

  /**
   * Return count of active user
   */
  public function setActiveUsers() {
    return $this->data_generator->getActiveUsers();
  }

  /**
   * Return position of registration user
   */
  public function setPositionRegistration(): int {
    return $this->data_generator->getPositionRegistration();
  }

  /**
   * Return random node in teaser format
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function setRandomNode(): array {
    return $this->data_generator->getNode();
  }

}
