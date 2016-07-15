<?php

namespace Drupal\github_api_example;

use Drupal\Core\Config\ConfigFactoryInterface;
use GuzzleHttp\Client;


/**
 * Class Github.
 *
 * @package Drupal\github_api_example
 */
class Github implements GithubInterface {

  protected $client;

  protected $config;

  /**
   * Github constructor.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('github_api_example.config');
    if (!empty($token = $this->config->get('personal_token'))) {
      // Create client with authentication.
      // We are using authentication only for dismissing limits on API.
      // All method we are using are available also without API key.
      $this->client = new Client(
        [
          'base_uri' => 'https://api.github.com',
          'headers' => [
            'Accept' => 'application/vnd.github.v3+json',
          ],
          'auth' => [
            'github',
            $token,
          ]
        ]
      );
    }
    else {
      // Create basic client.
      $this->client = new Client(
        array(
          'base_uri' => 'https://api.github.com',
          'headers' => array(
            'Accept' => 'application/vnd.github.v3+json',
          ),
        )
      );
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getUser($username = NULL) {
    if (empty($username)) {
      $endpoint = 'user';
      if (empty($this->config->get('personal_token'))) {
        throw new \Exception('Token needs to be filled in for getting owner user');
      }
    }
    else {
      $endpoint = 'users/' . $username;
    }
    try {
      $res = $this->client->get($endpoint);
      if ($res->getStatusCode() === 200) {
        return json_decode($res->getBody());
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('github')->error($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getPublicRepos($username) {
    $endpoint = 'users/' . $username . '/repos';
    try {
      $res = $this->client->get($endpoint);
      if ($res->getStatusCode() === 200) {
        return json_decode($res->getBody());
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('github')->error($e->getMessage());
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getOrganisations($username) {
    $endpoint = 'users/' . $username . '/orgs';
    try {
      $res = $this->client->get($endpoint);
      if ($res->getStatusCode() === 200) {
        return json_decode($res->getBody());
      }
    }
    catch (\Exception $e) {
      \Drupal::logger('github')->error($e->getMessage());
    }
  }

}
