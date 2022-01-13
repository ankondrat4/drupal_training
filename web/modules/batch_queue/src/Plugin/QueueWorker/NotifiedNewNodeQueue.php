<?php

namespace Drupal\batch_queue\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;

/**
 * A worker which logs into “DB logs'' information about users that should be
 * notified during any node creation during Queue item processing.
 *
 * @QueueWorker(
 *   id = "NotifiedNewNodeQueue",
 *   title = @Translation("Worker with logs creation nodes"),
 *   cron = {"time" = 10}
 * )
 */
class NotifiedNewNodeQueue extends QueueWorkerBase {

  /**
   * {@inheritdoc}
   */
  public function processItem($data) {
    if (!empty($data)) {
      \Drupal::logger('batch_queue')->notice('User @username should be notified about new node @node_title[@node_id]', [
        '@username' => $data['author'],
        '@node_title' => $data['title'],
        '@node_id' => $data['nid'],
      ]);
    }
  }

}
