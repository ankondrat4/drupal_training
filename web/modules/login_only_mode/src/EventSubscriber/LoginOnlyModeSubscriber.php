<?php
namespace Drupal\login_only_mode\EventSubscriber;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginOnlyModeSubscriber implements EventSubscriberInterface{

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
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Constructor for use Drupal servises
   */
  public function __construct(MessengerInterface $messenger,
                              AccountInterface $account,
                              RouteMatchInterface $route,
                              ConfigFactoryInterface $config) {
    $this->account = $account;
    $this->messenger = $messenger;
    $this->route = $route;
    $this->config = $config;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      KernelEvents::REQUEST => ['page_load'],
    ];
  }

  /**
   * Implements for page_load Event.
   */
  public function page_load(RequestEvent $event) {
    if ($this->config->get('enabled')) {
      $adress_anonym = [
        'user.login',
        'user.pass',
        'user.reset.login',
        'user.reset',
        'user.reset.form',
      ];
      // Is Anonymous than redirect on login page.
      if ($this->account->isAnonymous() &&
          !in_array($this->route->getRouteName(), $adress_anonym)) {
        $event->setResponse(new RedirectResponse('/user/login', 302));
      }

      $adress_authoriz = [
        'user.page',
        'user.logout',
        'entity.user.canonical',
        'contact_page.contact_form',
        'contact_page.contact_list',
      ];

      /*
       * Is Authenticated than redirect to user page.
       * Allow all pages for Administrator.
       */
      if ($this->account->isAuthenticated() &&
          !in_array('administrator', $this->account->getRoles()) &&
          !in_array($this->route->getRouteName(), $adress_authoriz)) {
        $url = '/user/'.$this->account->id();
        $event->setResponse(new RedirectResponse($url, 302));
      }
    }
  }

}
