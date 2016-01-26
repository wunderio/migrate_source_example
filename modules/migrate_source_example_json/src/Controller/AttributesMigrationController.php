<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\AttributesMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class AttributesMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class AttributesMigrationController extends ControllerBase {

  public function attributesContent() {
    $build = array();
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Attributes'),
    );
     return $build;
  }
}
