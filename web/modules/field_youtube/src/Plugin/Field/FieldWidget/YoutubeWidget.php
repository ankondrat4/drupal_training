<?php

namespace Drupal\field_youtube\Plugin\Field\FieldWidget;

use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'field_youtube_text' widget.
 *
 * @FieldWidget(
 *   id = "field_youtube_text",
 *   module = "field_youtube",
 *   label = @Translation("Youtube value as text link"),
 *   field_types = {
 *     "field_youtube"
 *   }
 * )
 */
class YoutubeWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = isset($items[$delta]->value) ? $items[$delta]->value : '';
    $value = $this->get_youtube_id_from_url($value);
    $element += [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#element_validate' => [
        [$this, 'validate'],
      ],
    ];
    return ['value' => $element];
  }

  /**
   * @param $url
   * @return mixed
   * ID video from youtube
   */
  public function get_youtube_id_from_url($url)  {
    preg_match_all("#(?<=v=|v\/|vi=|vi\/|youtu.be\/)[a-zA-Z0-9_-]{11}#", $url, $matches);
    return $matches[0];
  }

  /**
   * Validate the link youtube.
   */
  public function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];
    if (strlen($value) === 0) {
      $form_state->setValueForElement($element, '');
    }
  }

}
