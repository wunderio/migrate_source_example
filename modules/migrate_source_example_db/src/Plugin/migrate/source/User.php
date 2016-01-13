<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\User.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\SourceEntityInterface;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Extract users from database.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_db_user"
 * )
 */

class User extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('migrate_source_example_db_users', 'u')
      ->fields('u', array('name','roles','email'));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields['name'] = $this->t('Name');
    $fields['roles'] = $this->t('Roles');
    $fields['email'] = $this->t('Email');
    return $fields;
  }


  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return array(
      'uid' => array(
        'type' => 'integer',
        'alias' => 'u',
      ),
    );
  }

}
