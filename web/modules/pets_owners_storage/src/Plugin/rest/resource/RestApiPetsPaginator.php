<?php

namespace Drupal\pets_owners_storage\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * @RestResource(
 *   id = "restapipetspaginator",
 *   label = @Translation("Rest API for PetsOwners Entity with paginator (age,page)"),
 *   uri_paths = {
 *     "canonical" = "/api/v1/get-pets/{age}/{page}"
 *   }
 * )
 */
class RestApiPetsPaginator extends ResourceBase {

  /**
   * Load full list of records with pagination possibility and filtering
   * option by age (load all pets owner data where age is set to requested parameter).
   * Pagination is functionality that allows you to put data into chunks - you
   * specify the page number and response will include 5 items from N pages.
   */
  public function get(int $age, int $page) {
    $paginator = 5;
    $start = $page*$paginator-$paginator;
    $query = \Drupal::database()->select('pets_owners_storage')
      ->fields('pets_owners_storage')
      ->condition('age', $age)
      ->range($start, $paginator)
      ->execute();
    $count = 0;
    while($record = $query->fetchAssoc()) {
      $count++;
      $response["$count"] = $record;
    }
    if ($count != 0) {
      return new ResourceResponse($response, 200);
    }
    else {
      $response['message'] = 'Record with provided AGE or PAGE is not found.';
      return new ResourceResponse($response, 400);
    }
  }

}
