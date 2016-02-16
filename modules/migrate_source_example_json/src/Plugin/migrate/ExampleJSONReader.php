<?php

/**
 * @file
 * Contains Drupal\migrate_source_json\Plugin\migrate\JSONReader.
 *
 * This reader can traverse multidimensional arrays and retrieve results
 * by locating subarrays that contain a known identifier field at a known depth.
 * It can locate id fields that are nested in the results and pull out all other
 * content that is at the same level. If that content contains additional nested
 * arrays or needs other manipulation, extend this class and massage the data further
 * in the getSourceFields() method.
 *
 * For example, a file that adheres to the JSON API might look like this:
 *
 * Source:
 * [
 *   links [
 *     self: http://example.com/this_path.json
 *   ],
 *   data [
 *     entry [
 *       id: 1
 *       value1: 'something'
 *       value2: [
 *         0: green
 *         1: blue
 *       ]
 *     ]
 *     entry [
 *       id: 2
 *       value1: 'something else'
 *       value2: [
 *         0: yellow
 *         1: purple
 *       ]
 *     ]
 *   ]
 * ]
 *
 * The resulting source fields array, using identifier = 'id' and identifierDepth = 2, would be:
 * [
 *   0 [
 *     id: 1
 *     value1: 'something'
 *     value2: [
 *       0: green
 *       1: blue
 *     ]
 *   ]
 *   1 [
 *     id: 2,
 *     value1: 'something else'
 *     value2: [
 *       0: yellow
 *       1: purple
 *     ]
 *   ]
 * ]
 *
 * In the above example, the id field and the value1 field would be transformed
 * to top-level key/value pairs, as required by Migrate. The value2 field,
 * if needed, might require further manipulation by extending this class.
 *
 * @see http://php.net/manual/en/class.recursiveiteratoriterator.php
 */

namespace Drupal\migrate_source_example_json\Plugin\migrate;

use Drupal\migrate\MigrateException;
use GuzzleHttp\Exception\RequestException;
use Drupal\migrate_source_json\Plugin\migrate\JSONReaderInterface;

/**
 * Object to retrieve and iterate over JSON data.
 */
class ExampleJSONReader implements JSONReaderInterface {

  /**
   * The client class to create the HttpClient.
   *
   * @var string
   */
  protected $clientClass = '';

  /**
   * The HTTP Client
   *
   * @var JSONClientInterface resource
   */
  protected $client;

  /**
   * The request headers.
   *
   * @var array
   */
  protected $headers = [];

  /**
   * Source configuration
   *
   * @var array
   */
  protected $configuration;

  /**
   * The field name that is a unique identifier.
   *
   * @var string
   */
  protected $identifier = '';

  /**
   * The depth of the identifier in the source data.
   *
   * @var string
   */
  protected $identifierDepth = '';

  /**
   * Set the configuration created by the JSON source.
   *
   * @param array $configuration
   *   The source configuration.
   *
   * @throws \Drupal\migrate\MigrateException
   */
  public function __construct(array $configuration) {

    // Pull out the values this reader will care about.
    if (!isset($configuration['identifier'])) {
      throw new MigrateException('The source configuration must include the identifier.');
    }
    if (!isset($configuration['identifierDepth'])) {
      throw new MigrateException('The source configuration must include the identifierDepth.');
    }
    $this->identifier = $configuration['identifier'];
    $this->identifierDepth = $configuration['identifierDepth'];

    // Store the rest of the configuration.
    $this->configuration = $configuration;

    // Use the passed in clientClass value to set the http client.
    $this->clientClass = !isset($this->configuration['clientClass']) ? '\Drupal\migrate_source_json\Plugin\migrate\JSONClient' : $this->configuration['clientClass'];
    $this->setClient();
  }

  /**
   * {@inheritdoc}
   */
  public function setClient() {
    $this->client = new $this->clientClass();
    $this->client->setRequestHeaders($this->configuration['headers']);
  }

  /**
   * {@inheritdoc}
   */
  public function getClient() {
    return $this->client;
  }

  /**
   * Return the identifier for this JSON source.
   */
  public function getIdentifier() {
    return isset($this->identifier) ? $this->identifier : '';
  }

  /**
   * Return the identifier depth for this JSON source.
   */
  public function getIdentifierDepth() {
    return isset($this->identifierDepth) ? $this->identifierDepth : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFields($url) {

    $iterator = $this->getSourceData($url);

    // Recurse through the result array. When there is an array of items at the
    // expected depth that has the expected identifier as one of the keys, pull that
    // array out as a distinct item.
    $identifier = $this->getIdentifier();
    $identifierDepth = $this->getIdentifierDepth();
    $items = array();
    while ($iterator->valid()) {
      $iterator->next();
      $item = $iterator->current();
      if (is_array($item) && array_key_exists($identifier, $item)
        && $iterator->getDepth() == $identifierDepth) {
        $items[] = $item;
      }
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public function getSourceFieldsIterator($url) {
    $source_fields = $this->getSourceFields($url);
    $iterator = new \ArrayIterator($source_fields);
    return $iterator;
  }

  /**
   * Get the source data for reading.
   *
   * @param string $url
   *   The URL to read the source data from.
   *
   * @return \RecursiveIteratorIterator|resource
   *
   * @throws \Drupal\migrate\MigrateException
   */
  public function getSourceData($url) {
    try {
      $response = $this->client->getResponseContent($url);
      // The TRUE setting means decode the response into an associative array.
      $array = json_decode($response, TRUE);

      // array walk from defined root element only.
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
