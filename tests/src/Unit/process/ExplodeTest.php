<?php
/**
 * @file
 * Contains \Drupal\Tests\migrate_source_example\Unit\process\ExplodeTest.
 */

namespace Drupal\Tests\migrate_source_example\Unit\process;

  use Drupal\Tests\UnitTestCase;
  use Drupal\migrate\MigrateExecutable;
  use Drupal\migrate\Row;
  use Drupal\migrate_source_example\Plugin\migrate\process\Explode;

  /**
   * Tests the Explode process plugin.
   *
   * @group migrate_source_example
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


  public function aadditionProvider()
    {
        return array(
          array(',', '1,2,3,4,5', ['1','2','3','4','5']),
          array('/', '1/2/3/4/5', ['1','2','3','4','5']),
          array('_', '1_2_3_4_5', ['1','2','3','4','5']),
          array('|', '1|2|3|4|5', ['1','2','3','4','5']),
          array(':', '1:2:3:4:5', ['1','2','3','4','5']),
          array('+', '1+2+3+4+5', ['1','2','3','4','5']),
          array('::|', '1::|2::|3::|4::|5', ['1','2','3','4','5']),
        );
    }

  /**
   * Tests Explode with valid data.
   *
   * @dataProvider aadditionProvider
   *
   * @covers Drupal\migrate_source_example\Plugin\migrate\process\Explode::transform
   */
  public function testExplodeWithValidData($delimiter, $provided, $expected) {
    $configuration = [
      'delimiter' => $delimiter
    ];
    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();
    $row = new Row(array(), array());
    $explode = new Explode($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);
    $this->assertSame($explode->transform($provided, $migrate_executable, $row, 'test'), $expected);
  }

  /**
   * Tests Explode with invalid data.
   *
   * @test
   *
   * @covers Drupal\migrate_source_example\Plugin\migrate\process\Explode::transform
   *
   */
  public function testExplodeWithInvalidData() {
    $configuration = [
      'delimiter' => ","
    ];
    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();
    $row = new Row(array(), array());

    $value = 'lol/bob';
    $expected = ['lol/bob'];
    $explode = new Explode($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);

    $this->assertSame($explode->transform($value, $migrate_executable, $row, 'test'), $expected);
  }
}
