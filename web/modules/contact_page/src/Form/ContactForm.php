<?php
namespace Drupal\contact_page\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Form for send message.
 */
class ContactForm extends FormBase implements FormInterface, ContainerInjectionInterface {

  use MessengerTrait;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected AccountProxyInterface $currentUser;

  /**
   * Entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Connection to DB.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected Connection $connection;

  /**
   * Service to send email.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected MailManagerInterface $mailManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): ContactForm {
    $form = new static(
      $container->get('entity_type.manager'),
      $container->get('current_user'),
      $container->get('database'),
      $container->get('plugin.manager.mail'),
    );

    $form->setStringTranslation($container->get('string_translation'));
    $form->setMessenger($container->get('messenger'));
    return $form;
  }

  /**
   * Constructor for form object.
   */
  public function __construct(EntityTypeManagerInterface $repository,
                              AccountProxyInterface $current_user,
                              Connection $database,
                              MailManagerInterface $mail_manager) {
    $this->connection = $database;
    $this->entityTypeManager = $repository;
    $this->currentUser = $current_user;
    $this->mailManager = $mail_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'contact_page_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $user_id = $this->currentUser->id();
    $user_email = $this->currentUser->getEmail();

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('Send message to support.'),
    ];

    // If user authenticated
    if ($user_id && $user_email != '') {
      // Email hidden.
      $form['email'] = [
        '#type' => 'hidden',
        '#title' => $this->t('Email'),
        '#default_value' => $user_email,
        '#required' => TRUE,
      ];
    }
    else {
      // Email for input.
      $form['email'] = [
        '#type' => 'email',
        '#title' => $this->t('Email'),
        '#default_value' => '',
        '#required' => TRUE,
      ];
    }

    // Phone number.
    $form['phone_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Phone number'),
      '#description' => $this->t('Your phone must start from 380..'),
      '#required' => TRUE,
      '#attributes' => [
        'placeholder' => '380...',
      ],
    ];

    // Message.
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      //'#attributes' => ['class' => ['ckeditor-toolbar-textarea']],
      '#required' => TRUE,
    ];

    $dataTaxonomy = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree('submission');
    $categories = [];
    foreach ($dataTaxonomy as $item) {
      $categories[$item->name] = $item->name;
    }
    // Submission category.
    $form['category'] = [
      '#type' => 'select',
      '#title' => $this->t('Submission category:'),
      '#options' => $categories,
      '#empty_option' => $this->t('-none-'),
    ];

    $form['button'] = [
      '#type' => 'submit',
      '#value' => 'Send',
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $site_mail = $this->config('system.site')->get('mail');
    // Save info in DB from Contact Form.

      $return = $this->connection->insert('contact_page')
        ->fields([
          'user_id' => $this->currentUser->id(),
          'email' => $form_state->getValue('email'),
          'phone_number' => $form_state->getValue('phone_number'),
          'message' => $form_state->getValue('message'),
          'category' => $form_state->getValue('category'),
          'date_of_create' => time(),
        ])
        ->execute();

    if ($return) {
      //Prepare parameters for send to email
      $params = [
        'from' => $form_state->getValue('email'),
        'to' => $site_mail,
        'message' => $form_state->getValue('message'),
        'category' => $form_state->getValue('category'),
      ];
      $this->mailManager->mail(
        'contact_page',
        'contact_page_mail',
        $site_mail,
        'en',
        $params,
        $reply = NULL,
        $send = TRUE
      );
      $this->messenger()->addMessage($this->t('Thanks for your message to support!'));
      $form_state->setRedirect('contact_page.contact_form');
    }
  }

  /**
   * Method to validate data on form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

    // Validate email.
    if(!filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)){
        $form_state->setErrorByName('email', $this->t('Your email is not correct!'));
    }

    // Validate number on length.
    if (!is_numeric($form_state->getValue('phone_number')) || strlen($form_state->getValue('phone_number')) != 12 ) {
      $form_state->setErrorByName('phone_number', $this->t('Number must be correct!'));
    }

    // Validate type of message.
    if ($form_state->getValue('category') == '') {
      $form_state->setErrorByName('category', $this->t('Submission category must be selected!'));
    }
  }

}
