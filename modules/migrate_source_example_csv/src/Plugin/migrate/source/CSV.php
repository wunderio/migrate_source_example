<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_csv\Plugin\migrate\source\MigrateSourceExampleCSV.
 */

namespace Drupal\migrate_source_example_csv\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\migrate_source_csv\CSVFileObject;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV as MigrateSourceCSV;

/**
 * Book base migration.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_csv"
 * )
 */
class CSV extends MigrateSourceCSV {

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

    if (!empty($configuration['constants']['source_base_path'])) {
      $configuration['constants']['source_base_path'] = $base_module_path . '/' . $configuration['constants']['source_base_path'];
    }

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

  /**
   * Attaches "nid" property to a row if row "bid" points to a
   *
   * @param \Drupal\migrate\Row $row
   *
   * @return bool
   * @throws \Exception
   */
  function prepareRow(Row $row) {
    // Storage for destination ID indexes.
    static $destinationNidIndex = [];
    // Storage for book migration. Translations require destination ID of the
    // content source.
    static $bookMigration;
    // Storage for migration destination configuration.
    static $destinationConfiguration;

    $migrationId = $this->migration->id();

    if (!isset($destinationConfiguration[$migrationId])) {
      $destinationConfiguration[$migrationId] = $this->migration->getDestinationConfiguration();
    }

    if (!empty($destinationConfiguration[$migrationId]['translations'])) {
      if (!isset($bookMigration)) {
        $bookMigration = \Drupal::service('plugin.manager.config_entity_migration')->createInstance('migrate_source_example_csv_book');
      }

      // Get the index of "nid" field for destination on the migration map.
      if (!isset($destinationNidIndex[$migrationId])) {
        $ids = $this->migration->getDestinationPlugin()->getIds();
        $destinationNidIndex[$migrationId] = array_search('nid', array_keys($ids));
      }

      // Get the destination ID of the book.
      if ($destination = $bookMigration->getIdMap()->lookupDestinationIds(['id' => $row->getSourceProperty('id')])) {
        $destination = reset($destination);
        $row->setDestinationProperty('nid', $destination[$destinationNidIndex[$migrationId]]);
      }

    }
    return parent::prepareRow($row);
  }

}
