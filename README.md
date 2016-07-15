# README

This module provides basic integration with GitHub. GitHub API doesn't require authentication, but then is limited for
60 requests/hour. You can authenticate using [Personal access tokens][0] More information about GitHub API can be found
[here][1].

## How to use?
* Enable module and go to /admin/config/github_api_example/config
* Generate and save token. You should see owner of the token if token is valid.
* Go to your user profile and edit it. Enter your GitHub username into `GitHub username` field.
* Go to your profile and click on GitHub profile tab. You should see your public information there.

[0]: https://github.com/settings/tokens
[1]: https://developer.github.com