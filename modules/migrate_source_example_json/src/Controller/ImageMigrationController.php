<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\ImageMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class ImageMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class ImageMigrationController extends ControllerBase {

  public function imageContent() {
    $build = array();
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Images'),
    );
     return $build;
  }
}
