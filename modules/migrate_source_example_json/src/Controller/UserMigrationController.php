<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\UserMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class UserMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class UserMigrationController extends ControllerBase {

  public function userContent() {
    $build = array();
    $build = array(
      '#type' => 'markup',
      '#markup' => t('Users'),
    );
     return $build;
  }
}
