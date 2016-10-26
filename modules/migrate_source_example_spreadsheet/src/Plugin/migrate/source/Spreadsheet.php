<?php

namespace Drupal\migrate_source_example_spreadsheet\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_spreadsheet\Plugin\migrate\source\Spreadsheet as MigrateSpreadsheet;

/**
 * Base spreadsheet migration.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_spreadsheet"
 * )
 */
class Spreadsheet extends MigrateSpreadsheet {

  /**
   * Migration source file.
   */
  protected $source_file;

  /**
   * Spreadsheet constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param MigrationInterface $migration
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    $spreadsheet_module_path = drupal_get_path('module', 'migrate_source_example_spreadsheet');

    $configuration['file'] = $spreadsheet_module_path . '/source/' . $configuration['file'];

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

}
