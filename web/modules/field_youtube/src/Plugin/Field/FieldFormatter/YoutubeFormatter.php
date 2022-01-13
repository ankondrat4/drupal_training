<?php

namespace Drupal\field_youtube\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'field_youtube_video' formatter.
 *
 * @FieldFormatter(
 *   id = "field_youtube_video",
 *   module = "field_youtube",
 *   label = @Translation("Youtube text-based formatter"),
 *   field_types = {
 *     "field_youtube"
 *   }
 * )
 */
class YoutubeFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        // We create a render array to produce the desired markup,
        // <iframe width="560" height="315" src="https://www.youtube.com/embed/$item->value" frameborder="0"></iframe>.
        // See theme_html_tag().
        '#type' => 'html_tag',
        '#tag' => 'iframe',
        '#attributes' => [
          'width' => '560',
          'height' => '315',
          'frameborder' => '0',
          'src' => "https://www.youtube.com/embed/$item->value",
        ],
        ];
    }

    return $elements;
  }

}
