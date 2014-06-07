<?php
/**
 * Bitbucket API
 *
 * Handles communication between WordPress and the Bitbucket API
 * Supports v2 of the API - https://confluence.atlassian.com/display/BITBUCKET/Use+the+Bitbucket+REST+APIs
 *
 * @version 1.0
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
         * Page Length for API Pagination
         * Between 10-100
         *
         * @var int
         */
        private $page_length = 25;

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
         * Gets the Page Length
         *
         * @return int
         */
        public function get_page_length() {

            return $this->page_length;

        }

        /**
         * Sets the Page Length
         *
         * @param string $page_length
         */
        public function set_page_length( $page_length ) {

            if ( 10 > $page_length ) {
                $page_length = 10;
            }

            if ( 100 < $page_length ) {
                $page_length = 100;
            }

            $this->page_length = $page_length;

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
        public function __construct( $username, $password ) {

            $this->credentials = array(
                'username' => $username,
                'password' => $password,
            );

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

        /**
         * List the Repositories of given Account.
         *
         * @param $account
         * @param $page
         * @return array|mixed
         */
        public function get_account_repositories( $account, $page = null ) {

            $url = $this->api_url . 'repositories/' . $account . '?pagelen=' . $this->page_length;

            if ( $page ) {
                $url = $url . '&page=' . $page;
            }

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * List the Commits of given Account and Repository.
         *
         * @param string $account
         * @param string $repository
         * @param int $page
         * @param string $include
         * @param string $exclude
         * @return array|mixed
         */
        public function get_repository_commits( $account, $repository, $page = null, $include = null, $exclude = null ) {

            $params = array(
                'pagelen' => $this->page_length,
                'page' => $page,
                'include' => $include,
                'exclude' => $exclude,
            );

            $url = $this->api_url . 'repositories/' . $account . '/' . $repository . '/commits/?' . http_build_query( $params, '', '&amp;' );

            if ( $page ) {
                $url = $url . '&page=' . $page;
            }

            $response = $this->make_request( $url );

            return $response;

        }

        /**
         * Get the Commit of given Revision, Account and Repository.
         *
         * @param $account
         * @param $repository
         * @param $revision
         * @return array|mixed
         */
        public function get_repository_commit( $account, $repository, $revision ) {

            $url = $this->api_url . 'repositories/' . $account . '/' . $repository . '/commit/' . $revision;

            $response = $this->make_request( $url );

            return $response;

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

                $status = absint( wp_remote_retrieve_response_code( $response ) );

                if ( 200 == $status ) {

                    return json_decode( $response['body'] );

                } else {

                    return $response;

                }

            }

        }

    }

}