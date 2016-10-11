<?php

/**
 * @file
 * Contains Drupal\migrate_source_json\Plugin\migrate\MigrateExampleJSONReader.
 */

namespace Drupal\migrate_source_example_json\Plugin\migrate;

use Drupal\migrate\MigrateException;
use GuzzleHttp\Exception\RequestException;
use Drupal\migrate_source_json\Plugin\migrate\JSONReader;

/**
 * Object to retrieve and iterate over JSON data.
 */
class MigrateExampleJSONReader extends JSONReader {

  /**
   * @inheritdoc
   */
  public function getSourceData($url) {
    try {
      $response = $this->client->getResponseContent($url);
      // The TRUE setting means decode the response into an associative array.
      $array = json_decode($response, TRUE);

      // Array walk from defined root element only.
      if (isset($this->configuration['root_element']) && !empty($this->configuration['root_element'])) {
        $array = $array[ $this->configuration['root_element'] ];
      }

      // Return the results in a recursive iterator that
      // can traverse multidimensional arrays.
      return new \RecursiveIteratorIterator(
        new \RecursiveArrayIterator($array),
        \RecursiveIteratorIterator::SELF_FIRST);
    } catch (RequestException $e) {
      throw new MigrateException($e->getMessage(), $e->getCode(), $e);
    }
  }
}
