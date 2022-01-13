<?php

namespace Drupal\smile_entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Smile Entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup smile_entity
 */
interface SmileEntityInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
