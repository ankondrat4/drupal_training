<?php
namespace Drupal\smile_entity_events\EventSubscriber;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class MyEventsSubscriber implements EventSubscriberInterface{

  /**
   * Current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * Messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected MessengerInterface $messenger;

  /**
   * Route.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected RouteMatchInterface $route;

  /**
   * {@inheritdoc}
   */
  public function __construct(MessengerInterface $messenger,
                              AccountInterface $currentUser,
                              RouteMatchInterface $route) {
    $this->account = $currentUser;
    $this->route = $route;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['checkReferer'],
    ];
  }

  /**
   * Implements for checkReferer Event for all users whose HTTP Header "Referer"
   * not equal to [site-url].
   */
  public function checkReferer(RequestEvent $event) {
    //routes of /smile/[id]
    $routes = [
      'entity.smile_entity.canonical',
    ];
    $referer = $event->getRequest()->headers->get('referer');
    if (strpos($referer, $event->getRequest()->getHost()) === FALSE &&
        in_array($this->route->getRouteName(), $routes)) {
      throw new AccessDeniedHttpException();
    }
  }



}
