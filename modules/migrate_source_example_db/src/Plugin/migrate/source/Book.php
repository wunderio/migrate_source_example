<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\Book.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;

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
      ->fields('b', array('id', 'bid', 'langcode', 'title',  'body', 'body_format', 'image', 'author', 'category', 'user', 'created', 'alias'));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields['bid'] = $this->t('Bid');
    $fields['langcode'] = $this->t('Langcode');
    $fields['title'] = $this->t('Title');
    $fields['body'] = $this->t('Body');
    $fields['body_format'] = $this->t('Body format');
    $fields['image'] = $this->t('Image');
    $fields['author'] = $this->t('Author');
    $fields['category'] = $this->t('Category');
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
      'id' => array(
        'type' => 'integer',
      ),
      // Store book ID in map to have a reference for translations.
      // See self::prepareRow()
      'bid' => array(
        'type' => 'integer',
      ),
    );
  }

  /**
   * Attaches "nid" property to a row if row "bid" points to a
   *
   * @param \Drupal\migrate\Row $row
   *
   * @return bool
   * @throws \Exception
   */
  function prepareRow(Row $row) {
    static $destinationNidIndex;

    // Get the index of "nid" field for destination on the migration map.
    if (!isset($destinationNidIndex)) {
      $ids = $this->migration->getDestinationPlugin()->getIds();
      $destinationNidIndex = array_search('nid', array_keys($ids));
    }

    // Check the map if other translation has been imported. Query is done by
    // "bid" key which is mapped for this source.
    if ($destination = $this->idMap->lookupDestinationIds(['bid' => $row->getSourceProperty('bid')])) {
      $destination = reset($destination);
      $row->setSourceProperty('nid', $destination[$destinationNidIndex]);
    }

    return parent::prepareRow($row);
  }

}
