<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_json\Plugin\migrate\source\MigrateSourceExampleJSON.
 */

namespace Drupal\migrate_source_example_json\Plugin\migrate\source;

use Drupal\migrate\Annotation\MigrateSource;
use Drupal\migrate\Entity\MigrationInterface;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate_source_json\Plugin\migrate\JSONReaderInterface;

/**
 * A source class for JSON files.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_json"
 * )
 */
class MigrateSourceExampleJSON extends SourcePluginBase {

  /**
   * The path to the JSON source.
   *
   * @var string $path
   */
  protected $path = '';

  /**
   * The request headers.
   *
   * @var array $headers
   */
  protected $headers = [];

  /**
   * An array of source fields.
   *
   * @var array $fields
   */
  protected $fields = [];

  /**
   * The field name that is a unique identifier.
   *
   * @var string $identifier
   */
  protected $identifier = '';

  /**
   * The reader class to read the JSON source file.
   *
   * @var string $readerClass
   */
  protected $readerClass = '';

  /**
   * The JSON reader.
   *
   * @var JSONReaderInterface $reader
   */
  protected $reader;

  /**
   * The client class to create the HttpClient.
   *
   * @var string $clientClass
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

    // If no external path is given, it is local path.
    if (strpos($configuration['path'], 'http') === FALSE) {
        $url_assembler = \Drupal::service('unrouted_url_assembler');
        $configuration['path'] = $url_assembler->assemble('base:' . $configuration['path'], array('absolute' => TRUE));
    }

    // Store the configuration data.
    foreach ($config_fields as $config_field) {
      if (isset($configuration[$config_field])) {
        $this->{$config_field} = $configuration[$config_field];
      }
      else {
        throw new MigrateException(sprintf('The source configuration must include "%s" configuration item.', $config_field));
      }
    }

    // Set client and reader classes.
    $this->clientClass = 'Drupal\migrate_source_json\Plugin\migrate\JSONClient';
    $this->readerClass = 'Drupal\migrate_source_example_json\Plugin\migrate\MigrateExampleJSONReader';

    // Create the JSON reader that will process the request and pass it configuration.
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

}
