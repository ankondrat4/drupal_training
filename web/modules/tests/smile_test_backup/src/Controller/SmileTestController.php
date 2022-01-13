<?php

namespace Drupal\smile_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\examples\Utility\DescriptionTemplateTrait;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
/**
 * Controller routines for page example routes.
 */
class SmileTestController extends ControllerBase {

  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'smile_test';
  }

  /**
   * Constructs a simple page.
   *
   * The router _controller callback, maps the path
   * 'examples/page-example/simple' to this method.
   *
   * _controller callbacks return a renderable array for the content area of the
   * page. The theme system will later render and surround the content with the
   * appropriate blocks, navigation, and styling.
   */
  public function simple() {
    return [
      '#markup' => '<p>' . $this->t('Simple page: The quick brown fox jumps over the lazy dog.') . '</p>',
    ];
  }
  
    public function simple2() {
    return [
      '#markup' => '<p>' . $this->t('Simple page: The quick brown fox jumps over the lazy dog.') . '</p>',
    ];
  }
  
    public function smile_test() {
    return [
      '#markup' => '<p>' . $this->t('It is my first route ever') . '</p>',
    ];
  }
  
  
     public function my_menu() {
    return [
      '#markup' => '<p>' . $this->t('Test for menu') . '</p>',
    ];
  }

  /**
   * A more complex _controller callback that takes arguments.
   *
   * This callback is mapped to the path
   * 'examples/page-example/arguments/{first}/{second}'.
   *
   * The arguments in brackets are passed to this callback from the page URL.
   * The placeholder names "first" and "second" can have any value but should
   * match the callback method variable names; i.e. $first and $second.
   *
   * This function also demonstrates a more complex render array in the returned
   * values. Instead of rendering the HTML with theme('item_list'), content is
   * left un-rendered, and the theme function name is set using #theme. This
   * content will now be rendered as late as possible, giving more parts of the
   * system a chance to change it if necessary.
   *
   * Consult @link http://drupal.org/node/930760 Render Arrays documentation
   * @endlink for details.
   *
   * @param string $first
   *   A string to use, should be a number.
   * @param string $second
   *   Another string to use, should be a number.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *   If the parameters are invalid.
   */
  public function arguments($first, $second) {
    // Make sure you don't trust the URL to be safe! Always check for exploits.
    if (!is_numeric($first) || !is_numeric($second)) {
      // We will just show a standard "access denied" page in this case.
      throw new AccessDeniedHttpException();
    }

    $list[] = $this->t("First number was @number.", ['@number' => $first]);
    $list[] = $this->t("Second number was @number.", ['@number' => $second]);
    $list[] = $this->t('The total was @number.', ['@number' => $first + $second]);

    $render_array['page_example_arguments'] = [
      // The theme function to apply to the #items.
      '#theme' => 'item_list',
      // The list itself.
      '#items' => $list,
      '#title' => $this->t('Argument Information'),
    ];
    return $render_array;
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
