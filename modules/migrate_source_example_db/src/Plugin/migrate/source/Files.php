<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\Files.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Extract files from database.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_db_files"
 * )
 */

class Files extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    return $this->select('migrate_source_example_db_files', 'u')
      ->fields('u', array('fid','filename'));
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields['fid'] = $this->t('Fid');
    $fields['filename'] = $this->t('Filename');
    return $fields;
  }


  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return array(
      'fid' => array(
        'type' => 'integer',
        'alias' => 'u',
      ),
    );
  }

}
