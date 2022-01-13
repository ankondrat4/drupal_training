<?php

namespace Drupal\pets_owners_storage\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ModifiedResourceResponse;

/**
 * @RestResource(
 *   id = "restapipetsedit",
 *   label = @Translation("Rest API for edit record from PetsOwnersStorage"),
 *   uri_paths = {
 *     "create" = "/api/v1/edit-pets"
 *   }
 * )
 */
class RestApiPetsEdit extends ResourceBase {

  /**
   * Responds to EDIT specific record (by row Primary Key - ID).
   */
  public function post($data) {
    $id = \Drupal::request()->query->get('id');
    $count = \Drupal::database()->select('pets_owners_storage')
      ->condition('id', $id)
      ->countQuery()
      ->execute()
      ->fetchField();

    if ($count != 0) {
      $name = $data['name'];
      if (!empty($name)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
              'name' => $name,
            ])
          ->condition('id', $id)
          ->execute();      }
      $gender = $data['gender'];
      if (!empty($gender)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'gender' => $gender,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $prefix = $data['prefix'];
      if (!empty($prefix)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'prefix' => $prefix,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $age = $data['age'];
      if (!empty($age)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'age' => $age,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $fathersname = $data['fathersname'];
      if (!empty($fathersname)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'fathersname' => $fathersname,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $mothersname = $data['mothersname'];
      if (!empty($mothersname)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'mothersname' => $mothersname,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $somepets1 = $data['somepets1'];
      if (!empty($somepets1)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'somepets1' => $somepets1,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $somepets2 = $data['somepets2'];
      if (!empty($somepets2)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'somepets2' => $somepets2,
          ])
          ->condition('id', $id)
          ->execute();
      }
      $email = $data['email'];
      if (!empty($email)){
        \Drupal::database()->update('pets_owners_storage')
          ->fields([
            'email' => $email,
          ])
          ->condition('id', $id)
          ->execute();
      }

      $response['message'] = 'Record with provided ID update.';
      return new ModifiedResourceResponse($response, 200);
    }
    else {
      $response['message'] = 'Record with provided ID is not found.';
      return new ModifiedResourceResponse($response, 400);
    }
  }

}
