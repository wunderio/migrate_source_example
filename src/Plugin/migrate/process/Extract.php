<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\Extract.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "extract",
 * )
 */
class Extract extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   *
   * @throws MigrateException
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (!isset($this->configuration['key'])) {
      throw new MigrateException('Key of expected array element is not defined.');
    }

    if (!isset($value[$this->configuration['key']])) {
      throw new MigrateException(sprintf('Element with key "%s" is not set in given value.', $this->configuration['key']));
    }

    return $value[$this->configuration['key']];
  }

}
