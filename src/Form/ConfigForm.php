<?php

namespace Drupal\github_api_example\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\github_api_example\GithubInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class ConfigForm.
 *
 * @package Drupal\github_api_example\Form
 */
class ConfigForm extends ConfigFormBase {

  protected $github;

  /**
   * Constructs a \Drupal\system\ConfigFormBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory, GithubInterface $github) {
    $this->setConfigFactory($config_factory);
    $this->github = $github;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('github_api_example.github')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('github_api_example.config');
    $form['personal_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Personal Token'),
      '#description' => $this->t('You can generate personal access token in https://github.com/settings/tokens'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('personal_token'),
      '#required' => TRUE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
    // Save token.
    $this->config('github_api_example.config')
      ->set('personal_token', $form_state->getValue('personal_token'))
      ->save();
    // Verify token.
    $user_info = $this->github->getUser();
    // If OK, return owner of token, else check log message.
    if (!empty($user_info)) {
      drupal_set_message($this->t('Owner is @owner', ['@owner' => $user_info->name]));
    }
    else {
      drupal_set_message($this->t('Error occurred, please check log for reason'), 'error');
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'github_api_example.config',
    ];
  }

}
