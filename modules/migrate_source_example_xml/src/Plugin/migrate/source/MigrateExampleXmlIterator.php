<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_xml\Plugin\migrate\source\MigrateExampleXmlIterator.
 */

namespace Drupal\migrate_source_example_xml\Plugin\migrate\source;

use Drupal\migrate_source_xml\Plugin\migrate\source\MigrateXmlIterator;

/**
 * {@inheritdoc}
 */
class MigrateExampleXmlIterator extends MigrateXmlIterator {

  /**
   * {@inheritdoc}
   */
  public function getReaderClassName() {
    return 'Drupal\migrate_source_xml\Plugin\migrate\source\MigrateXmlReader';
  }

}
