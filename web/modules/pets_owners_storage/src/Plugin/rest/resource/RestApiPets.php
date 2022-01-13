<?php

namespace Drupal\pets_owners_storage\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * @RestResource(
 *   id = "restapipets",
 *   label = @Translation("Rest API for PetsOwnersStorage"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-pets/{id}"
 *   }
 * )
 */
class RestApiPets extends ResourceBase {

  /**
   * Responds to GET specific record (by row Primary Key - ID).
   */
  public function get(int $id) {
    $query = \Drupal::database()->select('pets_owners_storage')
      ->fields('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
    $result = $query->fetchAssoc();
    if ($result) {
      return new ResourceResponse($result, 200);
    }
    else {
      $response['message'] = 'Record with provided ID is not found.';
      return new ResourceResponse($response, 400);
    }
  }

}
