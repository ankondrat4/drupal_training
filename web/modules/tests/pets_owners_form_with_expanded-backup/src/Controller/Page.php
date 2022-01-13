<?php

namespace Drupal\pets_owners_form\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Simple page controller for drupal.
 */
class Page {

  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  public function getModuleName() {
    return 'pets_owners_form';
  }

}
