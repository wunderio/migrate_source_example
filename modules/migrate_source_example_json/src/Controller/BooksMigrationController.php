<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Controller\BooksMigrationController.
 */

namespace Drupal\migrate_source_example_json\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class BooksMigrationController.
 *
 * @package Drupal\migrate_source_example_json\Controller
 */
class BooksMigrationController extends ControllerBase {

  /**
   * Returns example source content.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function booksContent() {
    $path = drupal_get_path('module', 'migrate_source_example_json');
    $books_file_content = file_get_contents($path.'/'.'source/books.json');

    return new JsonResponse( json_decode($books_file_content) );
  }
}
