<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\FilterMigratedSourceValues.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigratePluginManager;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\migrate\Entity\MigrationInterface;
use Drupal\Core\Entity\EntityStorageInterface;
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
   * The entity storage manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $migrationStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, EntityStorageInterface $storage, MigratePluginManager $process_plugin_manager) {
    if (empty($configuration['migration']) || !is_string($configuration['migration'])) {
      throw new MigrateException('Migration is not defined or is not a string.');
    }

    $this->migrationStorage = $storage;
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
      $container->get('entity.manager')->getStorage('migration'),
      $container->get('plugin.manager.migrate.process')
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
    $migrations = $this->migrationStorage->loadMultiple([$migration_name]);
    /** @var MigrationInterface $migration */
    foreach ($migrations as $migration_id => $migration) {
      // If destination ID is found, return TRUE.
      if ($destination_ids = $migration->getIdMap()->lookupDestinationID([$value])) {
        return TRUE;
      }
    }

    return FALSE;
  }

}
