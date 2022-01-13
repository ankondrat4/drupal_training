<?php

namespace Drupal\dummy;

/**
 * Class DataGenerator
 *
 * @package Drupal\dummy
 */
class RandomMessageGenerator {

  // Массив с сообщениями.
  private $messages;

  /**
   * {@inheritdoc}
   */
  public function __construct() {
    // Записываем сообщения в свойство.
    $this->setMessages();
  }

  /**
   * Здесь мы просто задаем все возможные варианты сообщений.
   */
  private function setMessages() {
    $this->messages = [
      'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
      'Phasellus maximus tincidunt dolor et ultrices.',
      'Maecenas vitae nulla sed felis faucibus ultricies. Suspendisse potenti.',
      'In nec orci vitae neque rhoncus rhoncus eu vel erat.',
      'Donec suscipit consequat ex, at ultricies mi venenatis ut.',
      'Fusce nibh erat, aliquam non metus quis, mattis elementum nibh. Nullam volutpat ante non tortor laoreet blandit.',
      'Suspendisse et nunc id ligula interdum malesuada.',
    ];
  }

  /**
   * Метод, который возвра
   */
  public function getRandomMessage() {
    $random = rand(0, count($this->messages));
    //return $this->messages[$random];
    return $this->messages[0];
  }

  public function getActiveUsers() {
    $connection = \Drupal::service('database');
    $count_all_users = $connection->select('users_field_data')
      ->condition('status','1','=')
      ->countQuery()
      ->execute()
      ->fetchField();
    return $count_all_users;
  }
/*
  public function getPositionRegistration() {
    $connection = \Drupal::service('database');
    $count_all_users = $connection->select('users_field_data')
      ->condition('status','1','=')
      ->countQuery()
      ->execute()
      ->fetchField();
    return $count_all_users;
  }
*/

  public function getPositionRegistration() {
    $user = \Drupal::currentUser()->id();
    $connection = \Drupal::service('database');
    $result = $connection->select('users_field_data', 't')
      ->fields('t',['uid','name'])
      ->orderBy('t.created')
      ->execute();
    $info = $result->fetchAll();
    $pos = 0;
    $pos_curent_user = null;
    foreach($info as $key) {
      $pos++;
      if($key->uid == $user) $pos_curent_user = $pos;
    }
    return $pos_curent_user;
  }
}
