<?php

namespace Drupal\smile_entity;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the contact entity.
 */
class SmileEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   *
   * Link the activities to the permissions. checkAccess() is called with the
   * $operation as defined in the routing.yml file.
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {

    $user_role = $entity->get('role')->value;
    $current_roles = $account->getRoles();
    if (in_array($user_role, $current_roles)) {
      // Check the admin_permission as defined in your @ContentEntityType
      // annotation.
      $admin_permission = $this->entityType->getAdminPermission();
      if ($account->hasPermission($admin_permission)) {
        return AccessResult::allowed();
      }
      switch ($operation) {
        case 'view':
          return AccessResult::allowedIfHasPermission($account, 'view smile_entity entity');

        case 'update':
          return AccessResult::allowedIfHasPermission($account, 'edit smile_entity entity');

        case 'delete':
          return AccessResult::allowedIfHasPermission($account, 'delete smile_entity entity');
      }
      return AccessResult::neutral();
    }
    return AccessResult::forbidden("You don't have access to this page");

  }

  /**
   * {@inheritdoc}
   *
   * Separate from the checkAccess because the entity does not yet exist. It
   * will be created during the 'add' process.
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    // Check the admin_permission as defined in your @ContentEntityType
    // annotation.
    $admin_permission = $this->entityType->getAdminPermission();
    if ($account->hasPermission($admin_permission)) {
      return AccessResult::allowed();
    }
    return AccessResult::allowedIfHasPermission($account, 'add smile_entity entity');
  }

}
