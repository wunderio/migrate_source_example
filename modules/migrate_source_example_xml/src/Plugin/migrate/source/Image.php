<?php
/**
 * @file
* Contains \Drupal\migrate_source_example_xml\Plugin\migrate\source\Image.
 */
namespace Drupal\migrate_source_example_xml\Plugin\migrate\source;


use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate_source_xml\Plugin\migrate\source\XmlBase;
use Drupal\migrate\Row;


/**
 *
 * @MigrateSource(
 *   id = "migrate_source_example_xml_image"
 * )
 */
class Image extends MigrateSourceExampleXML {

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);

    // Get filename property value.
    $filename = $row->getSourceProperty('@filename');
    // Prepare base path.
    $base_path = trim($this->configuration['source_base_path'], '/');
    // Set "full_path" property to point to the full path of the image.
    $base_module_path = drupal_get_path('module', 'migrate_source_example');
    $row->setSourceProperty('full_path', $base_module_path . '/' . $base_path . '/' . $filename);
  }

}
