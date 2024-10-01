<?php
/**
 * Admin Settings
 *
 * @package     AffiliateWP Affiliate QR Codes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace AffiliateWP_Affiliate_QR_Codes\Admin;

/**
 * Implements admin functionality for the plugin.
 *
 * @since 1.0.0
 */
class Settings {

	/**
	 * Sets up admin hook callbacks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Settings tab.
		add_filter( 'affwp_settings_tabs', array( $this, 'setting_tab' ) );

		// Settings.
		add_filter( 'affwp_settings', array( $this, 'register_settings' ) );
	}

	/**
	 * Registers the new settings tab.
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs Settings tabs.
	 * @return array Array of settings tabs.
	 */
	public function setting_tab( $tabs ) {
		$tabs['affiliate_qr_codes'] = __( 'QR Codes', 'affiliatewp-affiliate-portal' );

		return $tabs;
	}

	/**
	 * Registers the settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Settings.
	 * @return array Array of settings.
	 */
	public function register_settings( $settings ) {

		$settings['affiliate_qr_codes'] = array(

			'qr_codes_enabled' => array(
				'name' => __( 'Enable affiliate QR codes', 'affiliatewp-affiliate-portal' ),
				'desc' => __( 'Check this box to globally enable affiliate QR codes.', 'affiliatewp-affiliate-portal' ),
				'type' => 'checkbox',
			),
		);

		return $settings;
	}

}
