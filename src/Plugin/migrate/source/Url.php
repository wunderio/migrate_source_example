<?php

namespace Drupal\migrate_source_example\Plugin\migrate\source;

use Drupal\Component\Utility\UrlHelper;
use Drupal\migrate\MigrateException;
use Drupal\migrate_plus\Plugin\migrate\source\Url as MigratePlusSourceUrl;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * A source class for Url resources.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_url"
 * )
 */
class Url extends MigratePlusSourceUrl {

  /**
   * Url constructor.
   *
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *
   * @throws MigrateException
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    // Make URLs required.
    if (!isset($configuration['urls'])) {
      throw new MigrateException('Resource URLs are not defined');
    }

    // Cast URLs to array.
    if (!is_array($configuration['urls'])) {
      $configuration['urls'] = [$configuration['urls']];
    }

    // Source URLs can be defined as relative. In that case current website's
    // base path is attached to it.
    $urlAssembler = \Drupal::service('unrouted_url_assembler');
    foreach ($configuration['urls'] as &$url) {
      if (!UrlHelper::isExternal($url)) {
        $url = $urlAssembler->assemble('base:' . $url, array('absolute' => TRUE));
      }
    }

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

}
