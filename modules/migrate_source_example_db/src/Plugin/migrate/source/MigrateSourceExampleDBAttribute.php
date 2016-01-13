<?php

/**
 * @file
 * Contains \Drupal\migrate_source_example_db\Plugin\migrate\source\MigrateSourceExampleDBAttribute.
 */

namespace Drupal\migrate_source_example_db\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\MigrateException;

/**
 * Attribute migration.
 *
 * @MigrateSource(
 *   id = "migrate_source_example_db_attribute"
 * )
 */
class MigrateSourceExampleDBAttribute extends SqlBase {

  /**
   * @var string $table The name of the database table.
   */
  protected $table = 'migrate_source_example_db_atributes';

  /**
   * {@inheritdoc}
   */
  public function query() {
    // Attribute type base path is required.
    if (empty($this->configuration['attribute_type'])) {
      throw new MigrateException('You must declare the "attribute_type" to define the attribute being migrated.');
    }

    return $this->select($this->table, '_table_alias')
      ->fields('_table_alias')
      ->condition('type', $this->configuration['attribute_type']);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'aid' => $this->t('Attribute ID'),
      'type' => $this->t('Attribute type'),
      'name' => $this->t('Attribute name'),
      'alias' => $this->t('Attribute alias'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'aid' => [
        'type' => 'integer',
      ],
    ];
  }

}
