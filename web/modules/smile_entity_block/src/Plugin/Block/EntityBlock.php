<?php
namespace Drupal\smile_entity_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "smile_entity_block",
 *   admin_label = @Translation("Smile Entity Block")
 *)
 */

class EntityBlock extends BlockBase {

  public function build() {
    $config = $this->getConfiguration();
    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();

    $query = \Drupal::entityQuery('smile_entity');
    $entity_ids = $query->condition('role', $roles, 'IN')
      ->sort('created', 'DESC')
      ->range(0, $config['items'])
      ->execute();
    $entity_type_manager = \Drupal::entityTypeManager();
    $entity_view_builder = $entity_type_manager->getViewBuilder('smile_entity');
    $smile_entities = $entity_type_manager->getStorage('smile_entity')->loadMultiple($entity_ids);

    $list = [];
    foreach ($smile_entities as $entity) {
      $list['entity'][$entity->id()] = $entity_view_builder->view($entity, 'teaser');
    }

    return [
      '#theme' => 'smile_entity_block_theme',
      '#list' => $list,
      '#attached'=>[
        'library'=>['smile_entity_block/table']
      ],
      '#cache' => [
        'tags' => ['smile_entity_list'],
        'contexts' => ['user.roles'],
      ],
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['items_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Count of entities:'),
      '#default_value' => $config['items'] ?? '',
    ];

    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    //setting value for items in config
    $this->setConfigurationValue('items', $form_state->getValue('items_count'));
  }

}
