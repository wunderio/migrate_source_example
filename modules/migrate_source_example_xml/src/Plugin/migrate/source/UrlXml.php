<?php

namespace Drupal\migrate_source_example_xml\Plugin\migrate\source;

use Drupal\migrate\MigrateException;
use Drupal\migrate_source_example\Plugin\migrate\source\Url as MigrateSourceExampleUrl;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * A source class for XML files.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_url_xml"
 * )
 */
class UrlXml extends MigrateSourceExampleUrl {

  /**
   * UrlXml constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *
   * @throws MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    // Add migration provider.
    $configuration['provider'] = 'migrate_source_example_xml';

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

}
