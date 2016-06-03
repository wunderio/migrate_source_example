<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\FilterMigratedSourceValues.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filter only migrated source values in given migration.
 *
 * @MigrateProcessPlugin(
 *   id = "filter_migrated_source_values",
 *   handle_multiples = TRUE
 * )
 */
class FilterMigratedSourceValues extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The migration plugin manager.
   *
   * @var \Drupal\migrate\Plugin\MigrationPluginManagerInterface $migrationPluginManager
   */
  protected $migrationPluginManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, MigrationPluginManagerInterface $migration_plugin_manager) {
    if (empty($configuration['migration']) || !is_string($configuration['migration'])) {
      throw new MigrateException('Migration is not defined or is not a string.');
    }

    $this->migrationPluginManager = $migration_plugin_manager;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $migration,
      $container->get('plugin.manager.migration')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $scalar = FALSE;

    if (!is_array($value)) {
      $value = [$value];
      $scalar = TRUE;
    }

    // Trim values.
    $value = array_map('trim', $value);

    foreach ($value as $key => $value_item) {
      // Unset the element in $value if it's not migrated in given migration.
      if (!$this->destinationIdExists($value_item, $this->configuration['migration'])) {
        unset($value[$key]);
      }
    }

    if ($scalar) {
      $value = reset($value);
    }

    return $value;
  }

  /**
   * Returns TRUE if destination value exists for provided migrations.
   *
   * @param array $value
   * @param $migration_name
   *
   * @return bool
   */
  protected function destinationIdExists($value, $migration_name) {
    /** @var \Drupal\migrate\Plugin\MigrationInterface $migration */
    $migration = $this->migrationPluginManager->createInstance($migration_name);

    // If destination ID is found, return TRUE.
    if ($destination_ids = $migration->getIdMap()->lookupDestinationID([$value])) {
      return TRUE;
    }

    return FALSE;
  }

}
