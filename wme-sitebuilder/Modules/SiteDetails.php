<?php

namespace Tribe\WME\Sitebuilder\Modules;

use Tribe\WME\Sitebuilder\Concerns\HasAssets;

class SiteDetails extends Module {

	use HasAssets;

	/**
	 * @var string
	 */
	protected $page_title = 'Site Details';

	/**
	 * @var string
	 */
	protected $menu_title = 'Site Details';

	/**
	 * @var string
	 */
	protected $capability = 'manage_options';

	/**
	 * @var string
	 */
	protected $menu_slug = 'site-details';

	/**
	 * @var string
	 */
	protected $icon_url = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTMiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxMyAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTcuODkzODYgNi45NzMyM0w3Ljk1MTUxIDYuODU3OUwxMi44MjMyIDBIOS42Mjc1Nkw2LjQzNjA3IDQuNzg2MTFMMy4yNDQ1OSAwSDAuMDUzMTAwNkw0LjkyNDc1IDYuODU3OUw0Ljk4MjQgNi45NzMyM0w2LjQzNjA3IDkuMTI3MzlMOS43MTQwNCAxNEgxMi44NzY3TDcuODkzODYgNi45NzMyM1oiIGZpbGw9IndoaXRlIi8+CjxwYXRoIGQ9Ik0wIDEzLjk5OThIMy4xNjI2Nkw2LjQzNjUxIDkuMTI3MkgzLjQ1NTA0TDAgMTMuOTk5OFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=';

	/**
	 * @var null|int
	 */
	protected $position = 2;

	/**
	 * Setup the Module.
	 */
	public function setup() {
		$this->menu_title = __( 'Site Details', 'wme-sitebuilder' );

		parent::setup();
	}

	/**
	 * Register hook callbacks.
	 */
	public function register_hooks() {
		parent::register_hooks();

		add_action( sprintf( '%s/print_scripts', $this->menu_slug ), [ $this, 'actionPrintScripts' ], 5 );
		add_action( sprintf( '%s/print_scripts', $this->menu_slug ), [ $this, 'actionPrintScripts_15' ], 15 );
	}

	/**
	 * Action: toplevel_page_sitebuilder:print_scripts.
	 *
	 * Add properties for page headline and description.
	 */
	public function actionPrintScripts() {
		$props = [
			'app_name'    => __( 'Site Details', 'wme-sitebuilder' ),
			'logo'        => 'sitebuilder-logo.svg',
			'title'       => __( 'Setup your site', 'wme-sitebuilder' ),
			'intro'       => __( 'Our set up wizard will help you get the most out of your site.', 'wme-sitebuilder' ),
			'site_url'    => site_url(),
			'logout_url'  => wp_logout_url(),
			'assets_url'  => $this->getAssetSource( 'sitebuilder/' ),
			'support_url' => esc_url( 'https://www.nexcess.net/support/' ),
			'page_url'    => add_query_arg( 'page', $this->menu_slug, admin_url( 'admin.php' ) ),
			'cards'       => [],
			'wizards'     => (object) [],
		];

		if ( class_exists( \WP101\API::class ) ) {
			$key = \WP101\API::get_instance()->get_public_api_key();

			if ( ! is_wp_error( $key ) ) {
				$props['wp101_api_key'] = $key;
			}
		}

		printf(
			'<script>window[%s] = %s</script>' . PHP_EOL,
			wp_json_encode( str_replace( '-', '_', (string) $this->menu_slug ) ),
			wp_json_encode( $props )
		);
	}

	/**
	 * Action: toplevel_page_sitebuilder/print_scripts:15.
	 *
	 * Print the JavaScript include and dependencies.
	 */
	public function actionPrintScripts_15() {
		$this->enqueueScript( 'wme-sitebuilder-site-details', 'site-details.js', [ 'wp-element', 'underscore', 'wp-api', 'wp-edit-post', 'password-strength-meter' ] );
	}
}
