<?php

namespace Drupal\pets_owners_storage\Plugin\views\filter;

use Drupal\views\Plugin\views\filter\InOperator;

/**
 * Exposes filter for sex pets.
 *
 * @ViewsFilter("sex")
 */
class SexType extends InOperator {

  /**
   * {@inheritdoc}
   */

  public function getValueOptions() {
    if (isset($this->valueOptions)) {
      return $this->valueOptions;
    }

    $this->valueOptions = [
      'mr' => $this-> t ('Man'),
      'mrs|ms' => $this-> t ('Woman'),
    ];
    // Array keys are used to compare with the table field values Sex.
    /*
     * if group by Views UI
    $this->valueOptions = [
      'mr' => 'mr',
      'mrs' => 'mrs',
      'ms' => 'ms',
    ];
*/
    return $this->valueOptions;
  }

}
