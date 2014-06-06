<?php
/**
 * Bitbucket API
 *
 * Handles communication between WordPress and the Bitbucket API
 * Supports v2 of the API - https://confluence.atlassian.com/display/BITBUCKET/Use+the+Bitbucket+REST+APIs
 *
 * @version 1.0
 */

/**
 * Basic Usage Example
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_Bitbucket' ) ) {

    class WP_Bitbucket {

        /**
         * Bitbucket API URL
         *
         * @var string
         */
        private $api_url = 'https://bitbucket.org/api/2.0/';

        /**
         * Bitbucket Login Credentials
         *
         * @var array
         */
        private $credentials = array();

        /**
         * Custom WP HTTP API Args
         *
         * @var array
         */
        private $custom_http_args = array();

        /**
         * Gets the API URL
         *
         * @return string
         */
        public function get_api_url() {

            return $this->api_url;

        }

        /**
         * Sets the API URL
         *
         * @param string $api_url
         */
        public function set_api_url( $api_url ) {

            $this->api_url = $api_url;

        }

        /**
         * Gets Custom HTTP API Args
         * See defaults: http://codex.wordpress.org/Function_Reference/wp_remote_get#Default_Usage
         *
         * @return array
         */
        public function get_http_args() {

            return $this->custom_http_args;

        }

        /**
         * Sets the Custom HTTP API Args
         * See defaults: http://codex.wordpress.org/Function_Reference/wp_remote_get#Default_Usage
         *
         * @param array $custom_http_args
         */
        public function set_http_args( $custom_http_args ) {

            $this->custom_http_args = $custom_http_args;

        }

        /**
         * Constructor
         *
         * @param string $username
         * @param string $password
         */
        public function __construct( $username = null, $password = null ) {

            if ( ! is_null( $username ) && ! is_null( $password ) ) {

                $this->credentials = array(
                    'username' => $username,
                    'password' => $password,
                );

            }

        }

        /**
         * Gets the Profile of given Username.
         *
         * @param $username
         * @return array|mixed
         */
        public function get_user_profile( $username ) {

            $url = $this->api_url . 'users/' . $username;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Followers of given Username.
         *
         * @param $username
         * @return array|mixed
         */
        public function get_user_followers( $username ) {

            $url = $this->api_url . 'users/' . $username . '/followers';

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Accounts the given Username is following.
         *
         * @param $username
         * @return array|mixed
         */
        public function get_user_following( $username ) {

            $url = $this->api_url . 'users/' . $username . '/following';

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Gets the Profile of given Team.
         *
         * @param $team
         * @return array|mixed
         */
        public function get_team_profile( $team ) {

            $url = $this->api_url . 'teams/' . $team;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * List the Members of given Team.
         *
         * @param $team
         * @return array|mixed
         */
        public function get_team_members( $team ) {

            $url = $this->api_url . 'teams/' . $team . '/members';

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * List the Followers of given Team.
         *
         * @param $team
         * @return array|mixed
         */
        public function get_team_followers( $team ) {

            $url = $this->api_url . 'teams/' . $team . '/followers';

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Lists the Accounts the given Team is following.
         *
         * @param $team
         * @return array|mixed
         */
        public function get_team_following( $team ) {

            $url = $this->api_url . 'teams/' . $team . '/following';

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * List the Repositories of given Team.
         *
         * @param $team
         * @return array|mixed
         */
        public function get_team_repositories( $team ) {

            $url = $this->api_url . 'teams/' . $team . '/repositories';

            $response = $this->make_request( $url );

            return $response;

        }

        public function get_owner_repositories( $owner ) {

            $url = $this->api_url . 'repositories/' . $owner;

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Builds the URL
         *
         * @return string
         */
        private function build_url() {

            switch ( $this->call_type ) {

                // Users Endpoints
                case 'users-profile':
                    $url_args = 'users/' . $this->resource;
                    break;
                case 'users-followers':
                    $url_args = 'users/' . $this->resource . '/followers';
                    break;
                case 'users-following':
                    $url_args = 'users/' . $this->resource . '/following';
                    break;

                // Teams Endpoints
                case 'teams-profile':
                    $url_args = 'teams/' . $this->resource;
                    break;
                case 'teams-members':
                    $url_args = 'teams/' . $this->resource . '/members';
                    break;
                case 'teams-followers':
                    $url_args = 'teams/' . $this->resource . '/followers';
                    break;
                case 'teams-following':
                    $url_args = 'teams/' . $this->resource . '/following';
                    break;
                case 'teams-repositories':
                    $url_args = 'teams/' . $this->resource . '/repositories';
                    break;

                // Repositories Endpoints
                case 'repositories-owner':
                    $url_args = 'repositories/' . $this->resource_owner;
                    break;
                case 'repositories-commits':
                    $url_args = 'repositories/' . $this->resource_owner . '/' . $this->resource . '/commits';
                    break;

                // Default - Not Found
                default:
                    return false;

            }

            return $this->api_url . $url_args;

        }

        /**
         * Make the HTTP Request
         *
         * @param string $url
         * @param string $method
         * @return array|mixed
         */
        public function make_request( $url, $method = 'GET' ) {

            $default_args = array(
                'method' => $method,
                'timeout' => 5,
                'httpversion' => '1.0',
                'headers' => array(
                    'Authorization' => 'Basic ' . base64_encode( $this->credentials['username'] . ':' . $this->credentials['password'] ),
                ),
                'body' => null,
            );

            $args = wp_parse_args( $this->custom_http_args, $default_args );

            $response = wp_remote_request( $url, $args );

            /*
             * Check for HTTP API Error
             */
            if ( is_wp_error( $response ) ) {

                return $response->errors;

            } else {

                // Decode JSON if needed
                return json_decode( $response['body'] );

            }

        }

    }

}