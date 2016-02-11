<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example\Plugin\migrate\process\Walk.
 */

namespace Drupal\migrate_source_example\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin emulates the working of array_walk() function by applying user
 * defined process plugins on each item in the list.
 *
 * @MigrateProcessPlugin(
 *   id = "walk",
 *   handle_multiples = TRUE
 * )
 */
class Walk extends ProcessPluginBase {

  /**
   * Runs a process pipeline on each destination list item.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $return = array();

    if (!is_array($value)) {
      $value = [$value];
    }

    // All processes are applied to a single field.
    $process = [$this->configuration['process']];

    foreach ($value as $key => $new_value) {
      $new_row = new Row(array(), array());
      $migrate_executable->processRow($new_row, $process, $new_value);
      $destination = $new_row->getDestination();
      // Getting the last value from the process chain.
      $return[$key] = end($destination);
    }
    return $return;
  }

}
