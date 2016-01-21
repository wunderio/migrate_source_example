<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\Book.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Extract users from database.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_db_book"
 * )
 */

class Book extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('migrate_source_example_db_books', 'b')
      ->fields('b', array('bid', 'title',  'body', 'body_format', 'image', 'attributes', 'user', 'created', 'alias'));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields['bid'] = $this->t('Bid');
    $fields['title'] = $this->t('Title');
    $fields['body'] = $this->t('Body');
    $fields['body_format'] = $this->t('Body format');
    $fields['image'] = $this->t('Image');
    $fields['attributes'] = $this->t('Attributes');
    $fields['user'] = $this->t('User');
    $fields['created'] = $this->t('Created');
    $fields['alias'] = $this->t('Alias');
    return $fields;
  }


  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return array(
      'bid' => array(
        'type' => 'integer',
        'alias' => 'b',
      ),
    );
  }

}