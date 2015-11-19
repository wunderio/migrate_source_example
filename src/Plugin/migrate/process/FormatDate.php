<?php

/**
 * @file
 * Contains \Drupal\csv_migration_example\Plugin\migrate\callback\FormatDate.
 */

namespace Drupal\csv_migration_example\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * @MigrateProcessPlugin(
 *   id = "format_date",
 * )
 */
class FormatDate extends ProcessPluginBase {
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($this->configuration['format'])) {
      throw new MigrateException('Expected date format is not defined.');
    }

    if ($timestamp = strtotime($value)) {
      return date($this->configuration['format'], $timestamp);
    }
  }
}
