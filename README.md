# WP Bitbucket

WP Bitbucket handles communication between WordPress and the Bitbucket API. It can be included in your theme/plugin or the /mu-plugins/ folder.

It is licensed under (GPL v2)[http://www.gnu.org/licenses/gpl-2.0.html].

You can see more information about NewRelic's v2 API here: [https://confluence.atlassian.com/display/BITBUCKET/Use+the+Bitbucket+REST+APIs)

## Supported Endpoints

Not all Endpoints are supported yet, but all GET Endpoints are planned. PUT and DELETE Endpoints will be considered if there is interest. You can access the following methods:

* get_user_profile( $username ) - Gets the Profile of given Username.

* get_user_followers( $username ) - Lists the Followers of given Username.

* get_user_following( $username ) - Lists the Accounts the given Username is following.

## Usage Examples

To get started check the examples below.

### Basic Example

```php
<?php

// Prepare the Credentials
$username = 'XXXXXXXXX';
$password = 'XXXXXXXXX';

// Init Object with Credentials
$bitbucket = new WP_Bitbucket( $username, $password );

// Prepare the Account Name
$account = 'XXXXXXXXXX';

// Perform the API Request
$response = $bitbucket->get_account_repositories( $account );

print_r( $response );

?>
```

### Advanced Example

```php
<?php

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