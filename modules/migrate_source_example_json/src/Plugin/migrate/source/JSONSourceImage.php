<?php

/**
 * @file
 * Contains Drupal\migrate_source_example_json\Plugin\migrate\source\JSONSourceImage.
 */

namespace Drupal\migrate_source_example_json\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;

/**
 * A source class for JSON files.
 *
 * @MigrateSource(
 *   id = "json_source_image"
 * )
 */
class JSONSourceImage extends SourcePluginBase {

  /**
   * The path to the JSON source.
   *
   * @var string
   */
  protected $path = '';

  /**
   * The request headers.
   *
   * @var array
   */
  protected $headers = [];

  /**
   * An array of source fields.
   *
   * @var array
   */
  protected $fields = [];

  /**
   * The field name that is a unique identifier.
   *
   * @var string
   */
  protected $identifier = '';

  /**
   * The reader class to read the JSON source file.
   *
   * @var string
   */
  protected $readerClass = '';

  /**
   * The JSON reader.
   *
   * @var resource
   */
  protected $reader;

  /**
   * The client class to create the HttpClient.
   *
   * @var string
   */
  protected $clientClass = '';

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration, array $namespaces = array()) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    $config_fields = array(
      'path',
      'headers',
      'fields',
      'identifier',
    );

    // Store the configuration data.
    foreach ($config_fields as $config_field) {
      if (isset($configuration[$config_field])) {
        $this->{$config_field} = $configuration[$config_field];
      }
      else {
        // Throw Exception
        throw new MigrateException('The source configuration must include ' . $config_field . '.');
      }
    }

    // Allow custom reader and client classes to be passed in as configuration settings.
    $this->clientClass = !isset($configuration['clientClass']) ? '\Drupal\migrate_source_json\Plugin\migrate\JSONClient' : $configuration['clientClass'];
    $this->readerClass = !isset($configuration['readerClass']) ? '\Drupal\migrate_source_json\Plugin\migrate\JSONReader' : $configuration['readerClass'];

    // Create the JSON reader that will process the request, and pass it configuration.
    $this->reader = new $this->readerClass($configuration);
  }

  /**
   * Return a count of all available source records.
   *
   * @return int
   *   The number of available source records.
   */
  public function _count($url) {
    return count($this->reader->getSourceFields($url));
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return $this->_count($this->path);
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    $ids = array();
    $ids[$this->identifier]['type'] = 'string';
    return $ids;
  }

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return $this->path;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return $this->fields;
  }

  /**
   * Get protected values.
   */
  public function get($item) {
    return $this->{$item};
  }

  /**
   * {@inheritdoc}
   */
  protected function initializeIterator() {
    $iterator = $this->reader->getSourceFieldsIterator($this->path);
    return $iterator;
  }

    /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    parent::prepareRow($row);

    // Get filename property value.
    $filename = $row->getSourceProperty('filename');
    // Prepare base path.
    $base_path = trim($this->configuration['source_base_path'], '/');
    // Set "full_path" property to point to the full path of the image.
    $base_module_path = drupal_get_path('module', 'migrate_source_example');
    $row->setSourceProperty('full_path', $base_module_path . '/' . $base_path . '/' . $filename);
  }

}
