<?php

namespace Drupal\batch_queue\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\Messenger\MessengerTrait ;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * A worker for unpublish nodes.
 *
 * @QueueWorker(
 *   id = "UnpublishNodeQueue",
 *   title = @Translation("Worker for unpublish nodes"),
 *   cron = {"time" = 30}
 * )
 */
class UnpublishNodeQueue extends QueueWorkerBase {
  use MessengerTrait;
  use StringTranslationTrait;
  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    $node_storage = \Drupal::entityTypeManager()->getStorage('node');
    if (!empty($data) && $node_storage->load($data) && $node_storage->load($data)->isPublished()) {
      $node_storage->load($data)
        ->setUnpublished()
        ->save();
      $this->messenger()->addMessage(
        $this->t('Unpublish nid: @node', [
          '@node' => $data,
        ])
      );
    }
  }

  /*
  public function processItem($data) {
    if (!empty($data)) {
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      if ($node = $node_storage->load($data)) {
        if ($node->isPublished()) {
          $node->setUnpublished();
          //$node->setPublished();
          $node->save();
          $this->messenger()->addMessage(
            $this->t('Unpublish nid: @node', [
              '@node' => $data,
            ])
          );
        }
      }
    }
  }*/

}
