<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\User.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\SourceEntityInterface;
use Drupal\migrate\Row;
use Drupal\migrate_drupal\Plugin\migrate\source\DrupalSqlBase;

/**
 * Extract users from database.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_db"
 * )
 */

class User extends DrupalSqlBase implements SourceEntityInterface {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('migrate_source_example_db_users', 'm')
      ->fields('m', array_keys($this->baseFields()))
      ->condition('uid', 0, '>');
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = $this->baseFields();
    $fields['uid'] = $this->t('Uid');
    $fields['name'] = $this->t('Name');
    // $fields['roles'] = $this->t('Roles');
    $fields['email'] = $this->t('Email');
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    // uid
    $result = $this->getDatabase()->query('
      SELECT
        uid
      FROM
        migrate_source_example_db_schema_users
      ');
    foreach ($result as $record) {
      $row->setSourceProperty('uid', $record->uid );
    }

    // name
    $result = $this->getDatabase()->query('
      SELECT 
        name
      FROM
        migrate_source_example_db_schema_users
      ');
    foreach ($result as $record) {
      $row->setSourceProperty('name', $record->name );
    }

    // roles
    // $result = $this->getDatabase()->query('
    //   SELECT 
    //     roles
    //   FROM
    //     migrate_source_example_db_schema_users
    //   ');
    // foreach ($result as $record) {
    //   $row->setSourceProperty('roles', $record->roles );
    // }

    // email
    $result = $this->getDatabase()->query('
      SELECT 
        email
      FROM
        migrate_source_example_db_schema_users
      ');
    foreach ($result as $record) {
      $row->setSourceProperty('email', $record->email );
    }

    return parent::prepareRow($row);
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

/**
   * Returns the user base fields to be migrated.
   *
   * @return array
   *   Associative array having field name as key and description as value.
   */
  protected function baseFields() {
     $fields = array(
      'uid' => $this->t('User ID'),
      'name' => $this->t('Name'),
      // 'roles' => $this->t('Roles'),
      'email' => $this->t('Email'),
      );
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function bundleMigrationRequired() {
    return FALSE;
  }
 
  /**
   * {@inheritdoc}
   */
  public function entityTypeId() {
    return 'user';
  }

}
