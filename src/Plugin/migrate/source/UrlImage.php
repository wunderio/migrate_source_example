<?php

namespace Drupal\migrate_source_example\Plugin\migrate\source;

use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * A source class for Url resources that deal with images.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_url_image"
 * )
 */
class UrlImage extends Url {

  /**
   * UrlImage constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *
   * @throws MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    // Image migrations need absolute location of the image storage.
    if (!empty($configuration['constants']['source_base_path'])) {
      $configuration['constants']['source_base_path'] = drupal_get_path('module', 'migrate_source_example') . '/' . $configuration['constants']['source_base_path'];
    }

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

}
