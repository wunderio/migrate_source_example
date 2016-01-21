<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\SkipOnEmptyMigrationDestination.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\MigrateSkipProcessException;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Entity\MigrationInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\migrate\Plugin\MigratePluginManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * If the source evaluates to empty, we skip processing or the whole row.
 *
 * @MigrateProcessPlugin(
 *   id = "skip_on_empty_migration_destination"
 * )
 */
class SkipOnEmptyMigrationDestination extends ProcessPluginBase implements ContainerFactoryPluginInterface {

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
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->migrationStorage = $storage;
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
  public function row($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$this->destinationIdExists($value, $migrate_executable, $row, $destination_property)) {
      throw new MigrateSkipRowException();
    }
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function process($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!$this->destinationIdExists($value, $migrate_executable, $row, $destination_property)) {
      throw new MigrateSkipProcessException();
    }
    return $value;
  }

  /**
   * Returns TRUE if destination value exists for provided migrations.
   *
   * @param $value
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   * @param \Drupal\migrate\Row $row
   * @param $destination_property
   *
   * @return bool
   */
  protected function destinationIdExists($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {$migration_ids = $this->configuration['migration'];
    if (!is_array($migration_ids)) {
      $migration_ids = array($migration_ids);
    }

    $migrations = $this->migrationStorage->loadMultiple($migration_ids);
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
