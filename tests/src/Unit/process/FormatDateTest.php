<?php
/**
 * @file
 * Contains \Drupal\Tests\migrate_source_example\Unit\process\FormatDateTest.
 */

namespace Drupal\Tests\migrate_source_example\Unit\process;

  use Drupal\Tests\UnitTestCase;
  use Drupal\migrate\MigrateExecutable;
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

    $this->pluginId = 'formatdate';
    $this->pluginDefinition = [];
    $this->plugin = $this->getMock('\Drupal\migrate\Entity\MigrationInterface');
  }


  public function additionProvider()

    {
        return array(
          array('U', '2016-01-27 13:15:29', '1453900529'),
          array('U', '2016-01-27 01:15:29 PM', '1453900529'),
          array('U', '2016-01-27T13:15:29', '1453900529'),
          array('U', 'January 27 2016 13:15:29', '1453900529'),
          array('U', '27th January 2016 13:15:29 CET', '1453896929'), //Central Europe timezone
          array('U', '27-Jan-16 13:15:29', '1453900529'),

        );
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
      'format' => $format
    ];

    date_default_timezone_set('GMT'); //to make sure test converts time to GTM timezone

    $migrate_executable = $this->getMockBuilder('Drupal\migrate\MigrateExecutable')
                     ->disableOriginalConstructor()
                     ->getMock();
    $row = new Row(array(), array());
    $formatdate = new FormatDate($configuration, $this->pluginId, $this->pluginDefinition, $this->plugin);
    $this->assertSame($formatdate->transform($provided, $migrate_executable, $row, 'test'), $expected);
  }
}
