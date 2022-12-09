<?php

namespace Tribe\WME\Sitebuilder\Cards;

class SiteVisibility extends Card {

	/**
	 * @var string
	 */
	protected $admin_page_slug = 'site-details';

	/**
	 * @var string
	 */
	protected $card_slug = 'site-visibility';

	/**
	 * Get properties.
	 *
	 * @return array
	 */
	public function props() {
		return [
			'id'        => 'site-visibility',
			'title'     => __( 'Site Visibility', 'wme-sitebuilder' ),
			'intro'     => __( 'Limit who can access your site online.', 'wme-sitebuilder' ),
			'completed' => false,
			'visible'   => false,
		];
	}

	/**
	 * Builds links to WC default product types.
	 *
	 * @return array[] Array with WC Product Examples.
	 */
	protected function get_example_products() {
		return array_filter([
			$this->get_wc_product_type( __( 'Simple', 'wme-sitebuilder' ), 'simple' ),
			$this->get_wc_product_type( __( 'Variable', 'wme-sitebuilder' ), 'variable' ),
			$this->get_wc_product_type( __( 'Grouped', 'wme-sitebuilder' ), 'grouped' ),
			$this->get_wc_product_type( __( 'External', 'wme-sitebuilder' ), 'external' ),
		], 'array_filter');
	}

	/**
	 * Queries WC for a specific product type.
	 *
	 * Queries the latest product stored in the database.
	 *
	 * @param string $title The Link name.
	 * @param string $type  The WC Product type. Defaults are simple, variable, grouped and external.
	 *
	 * @return array The WC Product if found. Empty otherwise.
	 */
	protected function get_wc_product_type( $title, $type ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return [];
		}

		$args = [
			'type'  => $type,
			'limit' => '1',
			'order' => 'DESC',
		];

		$products = wc_get_products( $args );

		if ( 0 === count( $products ) ) {
			return [];
		}

		$product = array_shift( $products );

		$url = add_query_arg([
			'post'   => $product->get_id(),
			'action' => 'edit',
		], admin_url());

		return [
			'title' => $title,
			'url'   => $url,
		];
	}
}
