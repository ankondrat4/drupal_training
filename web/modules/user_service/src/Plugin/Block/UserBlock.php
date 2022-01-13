<?php
namespace Drupal\user_service\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Class User Service Block.
 *
 * @Block(
 *   id = "user_service_block",
 *   admin_label = @Translation("User Service Block")
 * )
 */
class UserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build(): array {
    $list['info']['current_user'] = \Drupal::service('user_service.current_user_decorator')->getDisplayName();
    $list['info']['count_active'] = \Drupal::service('user_service.user_message')->setActiveUsers();
    $list['info']['position_of_registration'] = \Drupal::service('user_service.user_message')->setPositionRegistration();
    $list['info']['random_node'] = \Drupal::service('user_service.user_message')->setRandomNode();
    return [
      '#theme' => 'user_service_theme',
      '#list' => $list,
      '#attached'=>[
        'library'=>['user_service/style']
      ],
      '#cache' =>[
        'tags' => ['node_list'],
        'contexts' => ['user.roles'],
      ],
    ];
  }

  /**
   * @return int
   */
  /*public function getCacheMaxAge(): int {
    return 0;
  }*/

}
