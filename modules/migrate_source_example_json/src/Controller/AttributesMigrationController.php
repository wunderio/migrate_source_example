<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\AttributesMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AttributesMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class AttributesMigrationController extends ControllerBase {

  /**
   * Returns example source content.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function attributesContent() {
    $path = drupal_get_path('module', 'migrate_source_example_json');
    $attributes_file_content = file_get_contents($path.'/'.'source/attributes.json');

    return new JsonResponse( json_decode($attributes_file_content) );
  }
}
