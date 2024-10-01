<?php
/**
 * Core: Plugin Bootstrap
 *
 * @package     AffiliateWP Affiliate QR Codes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */

use AffiliateWP_Affiliate_QR_Codes as Base;

if ( ! class_exists( 'AffiliateWP_Affiliate_QR_Codes' ) ) {

	/**
	 * Setup class.
	 *
	 * @since 1.0.0
	 * @final
	 */
	final class AffiliateWP_Affiliate_QR_Codes {

		/**
		 * Holds the instance.
		 *
		 * Ensures that only one instance of the plugin bootstrap exists in memory at any
		 * one time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @since 1.0.0
		 * @var   \AffiliateWP_Affiliate_QR_Codes
		 * @static
		 */
		private static $instance;

		/**
		 * Plugin loader file.
		 *
		 * @since 1.0.0
		 * @var   string
		 */
		private $file = '';

		/**
		 * The version number.
		 *
		 * @since 1.0.0
		 * @var    string
		 */
		private $version = '1.0.3';

		/**
		 * Admin settings component.
		 *
		 * @since 1.0.0
		 * @var   Base\Admin\Settings
		 */
		public $settings;

		/**
		 * Generator component.
		 *
		 * @since 1.0.0
		 * @var   Base\Generator
		 */
		public $generator;

		/**
		 * Affiliate Area integration.
		 *
		 * @since 1.0.0
		 * @var   Base\Affiliate_Area
		 */
		public $affiliate_area;

		/**
		 * Affiliate Portal integration.
		 *
		 * @since 1.0.0
		 * @var   Base\Affiliate_Portal
		 */
		public $affiliate_portal;

		/**
		 * Generates the main bootstrap instance.
		 *
		 * Insures that only one instance of bootstrap exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 *
		 * @param string $file Path to the main plugin file.
		 * @return \AffiliateWP_Affiliate_QR_Codes The one true bootstrap instance.
		 */
		public static function instance( $file = '' ) {
			// Return if already instantiated.
			if ( self::is_instantiated() ) {
				return self::$instance;
			}

			// Setup the singleton.
			self::setup_instance( $file );

			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
			self::$instance->setup_objects();

			return self::$instance;
		}

		/**
		 * Setup the singleton instance
		 *
		 * @since 1.0.0
		 *
		 * @param string $file File path to the main plugin file.
		 */
		private static function setup_instance( $file ) {
			self::$instance       = new AffiliateWP_Affiliate_QR_Codes;
			self::$instance->file = $file;
		}

		/**
		 * Return whether the main loading class has been instantiated or not.
		 *
		 * @since 1.0.0
		 *
		 * @return bool True if instantiated. False if not.
		 */
		private static function is_instantiated() {

			// Return true if instance is correct class
			if ( ! empty( self::$instance ) && ( self::$instance instanceof AffiliateWP_Affiliate_QR_Codes ) ) {
				return true;
			}

			// Return false if not instantiated correctly.
			return false;
		}

		/**
		 * Throws an error on object clone.
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		protected function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh? This object cannot be cloned.', 'affiliatewp-affiliate-qr-codes' ), '1.0.0' );
		}

		/**
		 * Disables unserialization of the class.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		protected function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh? This class cannot be unserialized.', 'affiliatewp-affiliate-qr-codes' ), '1.0.0' );
		}

		/**
		 * Sets up the class.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Resets the instance of the class.
		 *
		 * @since 1.0.0
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Sets up plugin constants.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version.
			if ( ! defined( 'AFFWP_AQRC_VERSION' ) ) {
				define( 'AFFWP_AQRC_VERSION', $this->version );
			}

			// Plugin Folder Path.
			if ( ! defined( 'AFFWP_AQRC_PLUGIN_DIR' ) ) {
				define( 'AFFWP_AQRC_PLUGIN_DIR', plugin_dir_path( $this->file ) );
			}

			// Plugin Folder URL.
			if ( ! defined( 'AFFWP_AQRC_PLUGIN_URL' ) ) {
				define( 'AFFWP_AQRC_PLUGIN_URL', plugin_dir_url( $this->file ) );
			}

			// Plugin Root File.
			if ( ! defined( 'AFFWP_AQRC_PLUGIN_FILE' ) ) {
				define( 'AFFWP_AQRC_PLUGIN_FILE', $this->file );
			}
		}

		/**
		 * Includes necessary files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function includes() {
			// Bring in the autoloader.
			require_once AFFWP_AQRC_PLUGIN_DIR . 'includes/lib/affwp/affiliatewp-autoloader.php';
		}

		/**
		 * Sets up the default hooks and actions.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		private function hooks() {
			// Plugin meta.
			add_filter( 'plugin_row_meta', array( $this, 'plugin_meta' ), null, 2 );

		}

		/**
		 * Sets up objects and components.
		 *
		 * @since 1.0.0
		 */
		private function setup_objects() {
			if ( is_admin() ) {
				self::$instance->settings = new Base\Admin\Settings;
			}

			$enabled_for_affiliate = affiliate_wp()->settings->get( 'qr_codes_enabled', false );

			if ( false !== $enabled_for_affiliate ) {
				self::$instance->generator      = new Base\Generator;
				self::$instance->affiliate_area = new Base\Affiliate_Area;

				if ( class_exists( 'AffiliateWP_Affiliate_Portal' ) ) {
					self::$instance->affiliate_portal = new Base\Affiliate_Portal;
				}
			}
		}

		/**
		 * Modifies the plugin list table meta links.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $links The current links array.
		 * @param string $file  A specific plugin table entry.
		 * @return array The modified links array.
		 */
		public function plugin_meta( $links, $file ) {

			if ( $file === plugin_basename( $this->file ) ) {

				$label = __( 'More add-ons', 'affiliatewp-affiliate-qr-codes' );
				$atts  = array( 'title' => __( 'Get more add-ons for AffiliateWP', 'affiliatewp-affiliate-qr-codes' ) );

				$plugins_link = affwp_admin_link( 'add-ons', $label, array(), $atts );

				$links = array_merge( $links, array( $plugins_link ) );
			}

			return $links;

		}
	}
}

/**
 * The main function responsible for returning the one true bootstrap instance
 * to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $affiliatewp_affiliate_qr_codes = affiliatewp_affiliate_qr_codes(); ?>
 *
 * @since 1.0.0
 *
 * @return \AffiliateWP_Affiliate_QR_Codes The one true bootstrap instance.
 */
function affiliatewp_affiliate_qr_codes() {
	return AffiliateWP_Affiliate_QR_Codes::instance();
}
