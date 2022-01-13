<?php
namespace Drupal\my_events\Event;

use Symfony\Contracts\EventDispatcher\Event;

class SaveNodeEvent extends Event {

  /**
   * Save node event
   */
  const SAVE_NODE = 'my_events.save_node';

  /**Current node
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $node;

  /**
   * @param \Drupal\Core\Entity\EntityInterface $node
   */
  public function __construct($node) {
    $this->node = $node;
  }

  /**
   * Get curent node
   *
   * @return \Drupal\Core\Entity\EntityInterface
   */
  public function get_node() {
    return $this->node;
  }

}
