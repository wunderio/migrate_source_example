<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_csv\Plugin\migrate\source\MigrateSourceExampleCSV.
 */

namespace Drupal\migrate_source_example_csv\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_source_csv\CSVFileObject;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;

/**
 * Book base migration.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_csv"
 * )
 */
class MigrateSourceExampleCSV extends CSV {

  /**
   * Migration source file.
   */
  protected $source_file;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $base_module_path = drupal_get_path('module', 'migrate_source_example');
    $csv_module_path = drupal_get_path('module', 'migrate_source_example_csv');

    $configuration['path'] = $csv_module_path . '/source/' . $configuration['path'];
    $configuration['constants']['source_base_path'] = $base_module_path . '/source/images';

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   *
   * Enable multi-line values in CSV files.
   */
  public function initializeIterator() {
    $file = parent::initializeIterator();
    // Exclude CSVFileObject::DROP_NEW_LINE flag to retain the first newline in
    // a multi-line value.
    $file->setFlags(CSVFileObject::READ_CSV | CSVFileObject::READ_AHEAD | CSVFileObject::SKIP_EMPTY);
    return $file;
  }

}
