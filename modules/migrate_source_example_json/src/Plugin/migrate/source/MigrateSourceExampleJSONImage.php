<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Plugin\migrate\source\MigrateSourceExampleJSONImage.
 */

namespace Drupal\migrate_source_example_json\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Row;

/**
 * A source class for JSON files.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_json_image"
 * )
 */
class MigrateSourceExampleJSONImage extends MigrateSourceExampleJSON {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);

    // Get filename property value.
    $filename = $row->getSourceProperty('filename');
    // Prepare base path.
    $base_path = trim($this->configuration['source_base_path'], '/');
    // Set "full_path" property to point to the full path of the image.
    $base_module_path = drupal_get_path('module', 'migrate_source_example');
    $row->setSourceProperty('full_path', $base_module_path . '/' . $base_path . '/' . $filename);
  }

}
