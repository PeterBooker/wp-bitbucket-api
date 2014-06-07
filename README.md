# WP Bitbucket

WP Bitbucket handles communication between WordPress and the Bitbucket API. It can be included in your theme/plugin or the /mu-plugins/ folder. It is designed to allow easy fetching of data from Bitbucket to use and/or display on your WordPress site.

It is licensed under [GPL v2](http://www.gnu.org/licenses/gpl-2.0.html).

You can see more information about NewRelic's v2 API here: https://confluence.atlassian.com/display/BITBUCKET/Version+2

## Authentication

Your personal username and password are used to authenticate with Bitbucket. There is currently no easy method to generate personal oAuth tokens, so this is by far the easiest option.

## Supported Endpoints

Not all Endpoints are supported yet, but all GET Endpoints are planned. PUT and DELETE Endpoints will be considered if there is interest. You can access the following methods:

* get_user_profile( $username ) - *Gets the Profile of given Username.*

* get_user_followers( $username ) - *Lists the Followers of given Username.*

* get_user_following( $username ) - *Lists the Accounts the given Username is following.*

* get_team_members( $team ) - *List the Members of given Team.*

* get_team_followers( $team ) - *List the Followers of given Team.*

* get_team_following( $team ) - *Lists the Accounts the given Team is following.*

* get_team_repositories( $team ) - *List the Repositories of given Team.*

* get_account_repositories( $account ) - *List the Repositories of given Account.*

* get_repository_commits( $account, $repository ) - *List the Commits of given Account and Repository.*

## Usage Examples

To get started check the examples below.

### Basic Example

This basic example shows you how to fetch the details of your User Profile.

```php
<?php

// Prepare the Credentials
$username = 'XXXXXXXXX';
$password = 'XXXXXXXXX';

// Init Object with Credentials
$bitbucket = new WP_Bitbucket( $username, $password );

// Prepare the Account Name
$username = 'XXXXXXXXXX';

// Perform the API Request
$response = $bitbucket->get_user_profile( $username );

print_r( $response );

?>
```

### Advanced Example

```php
<?php

This advanced example shows you how to fetch the Commits for a particular Repository and optionally setting pagination options.

// Prepare the Credentials
$username = 'XXXXXXXXX';
$password = 'XXXXXXXXX';

// Init Object with Credentials
$bitbucket = new WP_Bitbucket( $username, $password );

// Set Items per Page (default 25)
$bitbucket->set_page_length( 100 );

// Prepare the Account Name
$account = 'XXXXXXXXX';

// Prepare the Repository Name
$repository = 'XXXXXXXXX';

// Perform the API Request
$response = $bitbucket->get_repository_commits( $account, $repository, $page = 1 );

print_r( $response );

?>
```