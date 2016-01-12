<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\Explode.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "explode",
 * )
 */
class Explode extends ProcessPluginBase {
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $result = explode($this->configuration['delimiter'], $value);
    return $result;
  }
}
