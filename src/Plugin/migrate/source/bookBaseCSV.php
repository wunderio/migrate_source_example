<?php

/**
 * @file
 * Contains \Drupal\book_migration\Plugin\migrate\source\bookBaseCSV.
 */

namespace Drupal\book_migration\Plugin\migrate\source;

use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate_source_csv\CSVFileObject;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;

/**
 * Book base migration.
 *
 * @MigrateSource(
 *   id = "book_base_csv"
 * )
 */
class bookBaseCSV extends CSV {

  /**
   * Migration source file.
   */
  protected $source_file;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $module_path = drupal_get_path('module', 'book_migration');

    $configuration['path'] = $module_path . '/source/' . $configuration['path'];
    $configuration['constants']['source_base_path'] = $module_path . '/source/images/';

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   *
   * Enable multi-line values in CSV files.
   */
  public function initializeIterator() {
    $file = parent::initializeIterator();
    $file->setFlags(CSVFileObject::READ_CSV | CSVFileObject::READ_AHEAD | CSVFileObject::SKIP_EMPTY);
    return $file;
  }

}
