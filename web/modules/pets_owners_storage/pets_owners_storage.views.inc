<?php

/**
 * @file
 * Provide views data for pets_owners_storage.module.
 */

/**
 * Implements hook_views_data().
 */
function pets_owners_storage_views_data() {
  $data = [];

  $data['pets_owners_storage']['table']['group'] = t('pets_owners_storage');

  $data['pets_owners_storage']['table']['base'] = [
    'field' => 'id',
    'title' => t('pets_owners_storage'),
    'help' => t('Contains a list of table pets_owners_storage.'),
  ];

  $data['pets_owners_storage']['id'] = [
    'title' => t('ID'),
    'help' => t('Unique pets_owners_storage event ID.'),
    'field' => [
      'id' => 'standard',
    ],
    'filter' => [
      'id' => 'serialized',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['name'] = [
    'title' => t('Name'),
    'help' => t('Name of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['gender'] = [
    'title' => t('Gender'),
    'help' => t('Gender of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['prefix'] = [
    'title' => t('Prefix'),
    'help' => t('Prefix of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'sex',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['age'] = [
    'title' => t('Age'),
    'help' => t('Age of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'numeric',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['fathersname'] = [
    'title' => t('Fathers name'),
    'help' => t('Fathers name of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['mothersname'] = [
    'title' => t('Mothers name'),
    'help' => t('Mothers name of pets.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['somepets1'] = [
    'title' => t('Some pets-1 name'),
    'help' => t('Some pets-1 name.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['somepets2'] = [
    'title' => t('Some pets-2 name'),
    'help' => t('Some pets-2 name.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  $data['pets_owners_storage']['email'] = [
    'title' => t('E-mail'),
    'help' => t('E-mail of owner.'),
    'field' => [
      'id' => 'standard',
    ],
    'argument' => [
      'id' => 'string',
    ],
    'filter' => [
      'id' => 'string',
    ],
    'sort' => [
      'id' => 'standard',
    ],
  ];

  return $data;
}
