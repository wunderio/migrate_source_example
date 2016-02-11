<?php
/**
 * @file
 * Contains \Drupal\migrate_source_example\EventSubscriber\MigrateSourceExampleUrlAliasSubscriber.
 */
namespace Drupal\migrate_source_example\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\migrate\Event\MigrateEvents;
use Drupal\migrate\Event\MigrateRowDeleteEvent;
use Drupal\Core\Url;

/**
 * Removes URL alias.
 */
class MigrateSourceExampleUrlAliasSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  static function getSubscribedEvents() {
    $events[MigrateEvents::POST_ROW_DELETE][] = ['removeUrlAlias'];
    return $events;
  }

  /**
   * Removes URL alias of a rolled back entity.
   *
   * This fills the gap of URL aliases not being deleted when a entity is
   * removed. This event listener should be removed when
   * https://www.drupal.org/node/2539634 is fixed.
   *
   * @param \Drupal\migrate\Event\MigrateRowDeleteEvent $event
   * @param $name
   */
  public function removeUrlAlias(MigrateRowDeleteEvent $event, $name) {
    $migration = $event->getMigration();
    $entity_type_id = substr($migration->getDestinationPlugin()->getPluginDefinition()['id'], strlen('entity:'));
    $entity_type_manager = \Drupal::entityTypeManager()->getDefinition($entity_type_id);

    // Check if canonical route is available.
    if ($entity_type_manager->getLinkTemplate('canonical')) {
      // Get destination entity id.
      $destination_entity_id = $event->getDestinationIdValues()[$entity_type_manager->getKeys()['id']];
      // Get Url object of the entity path.
      $url = Url::fromRoute("entity.$entity_type_id.canonical", [$entity_type_id => $destination_entity_id]);
      // Remove URL alias of the destination entity.
      \Drupal::service('path.alias_storage')->delete(array('source' => '/' . $url->getInternalPath()));
    }
  }

}
