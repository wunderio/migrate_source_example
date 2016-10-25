<?php

namespace Drupal\migrate_source_example_xml\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * This plugin extracts attributes.
 *
 * @MigrateProcessPlugin(
 *   id = "extract_attribute_values",
 *   handle_multiples = TRUE
 * )
 */
class ExtractAttributeValues extends ProcessPluginBase {

  /**
   * Extracts attribute values.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $prefix = $this->configuration['prefix'];
    $delimiter = $this->configuration['delimiter'];

    if (substr($value, 0, strlen($prefix)) == $prefix) {
      return explode($delimiter, substr($value, strlen($prefix)));
    }

    return [];
  }

}
