<?php
/**
 * Plugin Name: WP-API Post Groups
 * Plugin URI: https://github.com/eduardoboucas/WP_JSON_Post_Groups
 * Description: Allows multiple groups of posts with different filters to be obtained from the WP-API in a single request
 * Version: 0.1.0
 * Author URI: https://eduardoboucas.com
 * License: MIT
 */

class WP_JSON_Post_Groups {
	protected $server;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action('wp_json_server_before_serve', array($this, 'init'));
	}

	/**
	 * Plugin bootstrap
	 *
	 * @param WP_JSON_ResponseHandler $server Server object.
	 */
	public function init($server) {
		$this->server = $server;
		add_filter('json_endpoints', array($this, 'register_routes'));
	}

	/**
	 * Registers routes for the endpoints
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes($routes) {
		$routes['/postgroups'] = array(
			array(array($this, 'get_post_groups'), WP_JSON_Server::READABLE)
		);

		$routes['/pagegroups'] = array(
			array(array($this, 'get_page_groups'), WP_JSON_Server::READABLE)
		);

		return $routes;
	}

	/**
	 * Gets groups of posts
	 *
	 * @global WP $wp Current WordPress environment instance.
	 * @param  array  $type Array of post type slugs
	 * @return stdClass[] Collection of Post entities
	 */
	public function get_post_groups($type = array('post')) {
		global $wp;

		$groups = array();

		foreach ($this->server->params['GET'] as $var => $value) {
			$varParts = explode(':', $var);

			if ((count($varParts) > 1) && ($varParts[1] == 'filter')) {
				$groups[$varParts[0]] = $value;
			}
		}

		$wp_posts = new WP_JSON_Posts($this->server);
		$posts = array();

		foreach ($groups as $name => $filters) {
			$posts[$name] = $wp_posts->get_posts($filters, 'view', $type);
		}

		return $posts;
	}

	/**
	 * Gets groups of pages
	 *
	 * @return stdClass[] Collection of Post entities
	 */
	public function get_page_groups() {
		return $this->get_post_groups(array('page'));
	}
}

$multipost = new WP_JSON_Post_Groups();