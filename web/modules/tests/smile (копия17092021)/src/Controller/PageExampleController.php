<?php

namespace Drupal\smile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\examples\Utility\DescriptionTemplateTrait;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;

class PageExampleController extends ControllerBase {

  use DescriptionTemplateTrait;

  protected function getModuleName() {
    return 'smile';
  }

    public function smile_test() {
    return [
      '#markup' => '<p>' . $this->t('It is my first route ever') . '</p>',
    ];
  }
  
    public function node($nid) {
    // Make sure you don't trust the URL to be safe! Always check for exploits.
    if (!is_numeric($nid)) {
      // We will just show a standard "access denied" page in this case.
      throw new AccessDeniedHttpException();
    }


 
    	$entity_type = 'node';
	$view_mode = 'teaser';

	$view_builder = \Drupal::entityTypeManager()->getViewBuilder($entity_type);
	$storage = \Drupal::entityTypeManager()->getStorage($entity_type);
	$node = $storage->load($nid);
	$build = $view_builder->view($node, $view_mode);
	$output = \Drupal::service('renderer')->renderRoot($build);
	$nodeHtml = $output->__toString();
	    return array(
	    '#markup' => $output
	    ); 
    }
    
    
  public function access(AccountInterface $account) {
    // Check permissions and combine that with any custom access checking needed. Pass forward
    // parameters from the route and/or request as needed.
    return AccessResult::allowedIf($account->hasPermission('Administer account settings') );
  }

}
