<?php
namespace Drupal\dummy\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Class NewsBlock.
 *
 * @Block(
 *   id = "newsblock",
 *   admin_label = @Translation("News Block")
 * )
 */
class UserBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $count_active = \Drupal::service('dummy.random_drupal_message')->setActiveUsers();
    $list['info']['count_active'] = $this->t("$count_active");
    $position_of_registration = \Drupal::service('dummy.random_drupal_message')->setPositionRegistration();
    $list['info']['position_of_registration'] = $this->t("$position_of_registration");

    //return ['#markup' => $this->t("$text")];

    return [
      '#theme' => 'dummy_theme',
      '#list' => $list,
      '#attached'=>[
        'library'=>['dummy/block'],
      ],
    ];
  }

  /**
   * @return int
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
