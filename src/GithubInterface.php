<?php

namespace Drupal\github_api_example;

/**
 * Interface GithubInterface.
 *
 * @package Drupal\github_api_example
 */
interface GithubInterface {

  /**
   * Fetch information about token owner or about user passed in parameter.
   *
   * @param null $username
   *   Username of user we want to fetch information about.
   *
   * @return mixed
   *   Object with information about user.
   */
  public function getUser($username = NULL);

  /**
   * Get public repositories of user passed as parameter.
   *
   * @param $username
   *   Username of user we want to fetch information about.
   *
   * @return array
   *   Array of objects with user's publis repositories.
   */
  public function getPublicRepos($username);

  /**
   * Get organisations with public membership for user passed in parameter.
   *
   * @param $username
   *   Username of user we want to fetch information about.
   *
   * @return array
   *   Array of objects with user's publis repositories.
   */
  public function getOrganisations($username);
}
