<?php

namespace Drupal\pets_owners_storage\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * @RestResource(
 *   id = "restapipetsdelete",
 *   label = @Translation("Rest API for delete record from PetsOwnersStorage"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/delete-pets/{id}"
 *   }
 * )
 */
class RestApiPetsDelete extends ResourceBase {

  /**
   * Responds to DELETE specific record (by row Primary Key - ID).
   */
  public function get(int $id) {
    $query = \Drupal::database()->delete('pets_owners_storage')
      ->condition('id', $id)
      ->execute();
    if ($query != 0) {
      $response['message'] = 'Record with provided ID deleted.';
      return new ResourceResponse($response, 200);
    }
    else {
      $response['message'] = 'Record with provided ID is not found.';
      return new ResourceResponse($response, 400);
    }
  }

}
