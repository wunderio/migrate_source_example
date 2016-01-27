<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\ImageMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Component\Utility\String;

/**
 * Class ImageMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class ImageMigrationController extends ControllerBase {

  public function imageContent() {

    $path = drupal_get_path('module', 'migrate_source_example_json');
    $images_file_content = file_get_contents($path.'/'.'source/images.json');

    return new JsonResponse($images_file_content);
  }
}
