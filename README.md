# WP Bitbucket

WP Bitbucket handles communication between WordPress and the Bitbucket API. It can be added to your theme/plugin or the /mu-plugins/ folder.

You can see more information about NewRelic's v2 API here: [https://confluence.atlassian.com/display/BITBUCKET/Use+the+Bitbucket+REST+APIs)

## Supported Endpoints

Not all Endpoints are supported yet, but all GET Endpoints are planned. PUT and DELETE Endpoints will be considered if there is interest. The following are supported:

* servers-list
* servers-show (Requires Server ID)

* applications-list
* applications-show (Requires Application ID)

* key_transactions-list
* key_transactions-show (Requires Key Transaction ID)

## Usage Examples

To get started check the examples below.

### Basic Example

```php
<?php

// Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

// Find the Call Type from here - https://rpm.newrelic.com/api/explore/
$newrelic->set_call_type( 'applications-list' );

$response = $newrelic->make_request();

print_r( $response );

?>
```

### Advanced Example

```php
<?php

// Replace with real API Key - http://docs.newrelic.com/docs/apis/api-key
$api_key = 'XXXXXXXXXXXXXXXXXXXXXXX';

$newrelic = new WP_NewRelic( $api_key );

// Find the Call Type from here - https://rpm.newrelic.com/api/explore/
$newrelic->set_call_type( 'applications-show' );

$app_id = 000000;

$newrelic->set_resource_id( $app_id );

// WP HTTP API Args
$args = array(
    'sslverify' => false,
);

$response = $newrelic->make_request( $args );

print_r( $response );

?>
```