<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\UserMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\String;

/**
 * Class UserMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class UserMigrationController extends ControllerBase {

  public function userContent() {

    $path = drupal_get_path('module', 'migrate_source_example_json');
    $users_file_content = file_get_contents($path.'/'.'source/users.json');

    return new JsonResponse( json_decode($users_file_content) );
  }
}
