<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\BooksMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class BooksMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class BooksMigrationController extends ControllerBase {

  public function booksContent() {
    $build = array();
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Books'),
    );
     return $build;
  }
}
