<?php

namespace Drupal\smile_entity\Entity\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\examples\Utility\DescriptionTemplateTrait;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

class EntityController extends ControllerBase {

  use DescriptionTemplateTrait;

  protected function getModuleName() {
    return 'smile_entity';
  }

  public function entity($id) {
    if (!is_numeric($id)) {
      throw new AccessDeniedHttpException();
    }
    $entity_type = 'smile_entity';
	  $view_mode = 'teaser';
    $view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
    $storage = \Drupal::entityTypeManager()->getStorage($entity_type);
    $entity = $storage->load($id);
    $build = $view_builder->view($entity, $view_mode);
    $output = \Drupal::service('renderer')->renderRoot($build)->__toString();;

    return [
        '#markup' => $output
    ];
  }

}
