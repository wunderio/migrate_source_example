<?php
/**
 * @file
 * Contains \Drupal\Tests\migrate_source_example\Unit\process\FormatDateTest.
 */

namespace Drupal\Tests\migrate_source_example\Unit\process;

use Drupal\Tests\UnitTestCase;
use Drupal\migrate\Row;
use Drupal\migrate_source_example\Plugin\migrate\process\FormatDate;

/**
 * Tests the FormatDate process plugin.
 *
 * @group migrate_source_example
 */
class FormatDateTest extends UnitTestCase {

  /**
   * The plugin id.
   *
   * @var string
   */
  protected $pluginId;

  /**
   * The plugin definition.
   *
   * @var array
   */
  protected $pluginDefinition;

  /**
   * The mock migration plugin.
   *
   * @var \Drupal\migrate\Entity\MigrationInterface
   */
  protected $plugin;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->pluginId = 'format_date';
    $this->pluginDefinition = [];
    $this->plugin = $this->getMock('\Drupal\migrate\Entity\MigrationInterface');
  }

  /**
   * @return array
   */
  public function additionProvider() {
    return [
      ['U', '2016-01-27 13:15:29', '1453900529'],
      ['U', '2016-01-27 01:15:29 PM', '1453900529'],
      ['U', '2016-01-27T13:15:29', '1453900529'],
      ['U', 'January 27 2016 13:15:29', '1453900529'],
      // Test with specific timezone.
      ['U', '27th January 2016 13:15:29 CET', '1453896929'],
      ['U', '27-Jan-16 13:15:29', '1453900529'],
    ];
  }

  /**
   * Tests FormatDate with valid data.
   *
   * @dataProvider additionProvider
   *
   * @covers Drupal\migrate_source_example\Plugin\migrate\process\FormatDate::transform
   */
  public function testFormatDateWithValidData($format, $provided, $expected) {
    $configuration = [
      'format' => $format,
    ];

    // Make sure test converts time to GTM timezone.
    date_default_timezone_set('GMT');

    /** @var \Drupal\migrate\MigrateExecutable $migrate_executable */
    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();
    $row = new Row([], []);
    $formatdate = new FormatDate($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);
    $this->assertSame($formatdate->transform($provided, $migrate_executable, $row, 'test'), $expected);
  }

}
