<?php
/**
 * @file
 * Contains \Drupal\Tests\migrate_source_example\Unit\process\ExplodeTest.
 */

namespace Drupal\Tests\migrate_source_example\Unit\process;

use Drupal\Tests\UnitTestCase;
use Drupal\migrate\Row;
use Drupal\migrate_source_example\Plugin\migrate\process\Explode;

/**
 * Tests the Explode process plugin.
 *
 * @group migrate_source_example
 *
 * @coversDefaultClass Drupal\migrate_source_example\Plugin\migrate\process\Explode
 */
class ExplodeTest extends UnitTestCase {

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
    $this->pluginId = 'explode';
    $this->pluginDefinition = [];
    $this->plugin = $this->getMock('\Drupal\migrate\Entity\MigrationInterface');
  }

  /**
   * @return array
   */
  public function additionProvider() {
    return [
      [',', '1,2,3,4,5', ['1','2','3','4','5']],
      ['/', '1/2/3/4/5', ['1','2','3','4','5']],
      ['_', '1_2_3_4_5', ['1','2','3','4','5']],
      ['|', '1|2|3|4|5', ['1','2','3','4','5']],
      [':', '1:2:3:4:5', ['1','2','3','4','5']],
      ['+', '1+2+3+4+5', ['1','2','3','4','5']],
      ['::|', '1::|2::|3::|4::|5', ['1','2','3','4','5']],
    ];
  }

  /**
   * Tests Explode with valid data.
   *
   * @dataProvider additionProvider
   *
   * @covers ::transform
   */
  public function testExplodeWithValidData($delimiter, $provided, $expected) {
    $configuration = [
      'delimiter' => $delimiter,
    ];

    /** @var \Drupal\migrate\MigrateExecutable $migrate_executable */
    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();

    $row = new Row([], []);
    $explode = new Explode($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);
    $this->assertSame($explode->transform($provided, $migrate_executable, $row, 'test'), $expected);
  }

  /**
   * Tests Explode with invalid data.
   *
   * @test
   *
   * @covers ::transform
   *
   */
  public function testExplodeWithInvalidData() {
    $configuration = [
      'delimiter' => ',',
    ];

    /** @var \Drupal\migrate\MigrateExecutable $migrate_executable */
    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();

    $row = new Row([], []);
    $value = 'lol/bob';
    $expected = ['lol/bob'];
    $explode = new Explode($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);

    $this->assertSame($explode->transform($value, $migrate_executable, $row, 'test'), $expected);
  }

}
