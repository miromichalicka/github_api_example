<?php

namespace Drupal\github_api_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\github_api_example\GithubInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for profile action.
 */
class UserController extends ControllerBase {

  protected $github;

  /**
   * {@inheritdoc}
   */
  public function __construct(GithubInterface $githubInterface) {
    $this->github = $githubInterface;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('github_api_example.github')
    );
  }

  /**
   * Get profile page for user.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user account.
   *
   * @return array
   *   Return render array.
   */
  public function showProfile(UserInterface $user) {
    $github_username = $user->github_username->value;
    if (empty($github_username)) {
      return [
        '#markup' => $this->t('You have to fill in your username first.'),
      ];
    }
    $user_info = $this->github->getUser($github_username);
    if (!empty($user_info)) {
      if ($user_info->public_repos > 0) {
        $repositories = $this->github->getPublicRepos($github_username);
      }
      else {
        $repositories = [];
      }
      $organisations = $this->github->getOrganisations($github_username);
      return [
        '#theme' => 'github_info',
        '#account' => $user_info,
        '#repositories' => $repositories,
        '#organisations' => $organisations
      ];
    }
  }
}