<?php
namespace Drupal\my_events\EventSubscriber;

use Drupal\my_events\Event\SaveNodeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MyEventsSubscriber implements EventSubscriberInterface{

  /**
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $account;

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $route;

  /**
   * Constructor for use Drupal servises
   */
  public function __construct() {
    $this->account = \Drupal::currentUser();
    $this->messenger = \Drupal::service('messenger');
    $this->route = \Drupal::routeMatch();
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      SaveNodeEvent::SAVE_NODE => ['save_node'],
      KernelEvents::REQUEST => ['page_load'],
    ];
  }

  /**
   * Implements for save_node Event.
   */
  public function save_node(SaveNodeEvent $event) {
    $title = $event->get_node()->label();
    $type = $event->get_node()->getType();
    $this->messenger->addMessage("$type:$title saved!");
  }

  /**
   * Implements for page_load Event.
   */
  public function page_load(RequestEvent $event) {
    if ($this->account->isAnonymous() && $this->route->getRouteName() != 'user.login') {
      if ($this->route->getRouteName() == 'user.pass') {
        return;
      }
      $event->setResponse(new RedirectResponse('/user/login', 302));
    }
  }

}
