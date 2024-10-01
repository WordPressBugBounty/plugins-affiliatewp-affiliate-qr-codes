<?php
/**
 * Affiliate Area Integration
 *
 * @package     AffiliateWP Affiliate QR Codes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace AffiliateWP_Affiliate_QR_Codes;

/**
 * Sets up front end Affiliate Area template changes.
 *
 * @since 1.0.0
 */
class Affiliate_Area {

	/**
	 * Sets up hook callbacks for integration with the Affiliate Area.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'affwp_affiliate_dashboard_urls_before_generator', array( $this, 'render_qr_code' ) );
	}

	/**
	 * Outputs a QR code matching the (static) affiliate URL in the Affiliate Area.
	 *
	 * @since 1.0.0
	 *
	 * @param int $affiliate_id Current affiliate ID.
	 */
	public function render_qr_code( $affiliate_id ) {
		$generator = affiliatewp_affiliate_qr_codes()->generator;

		$image_url = $generator->get_code_for_affiliate( $affiliate_id );

		// Set margin=1 for the QR code link.
		$image_url_href = add_query_arg( array( 'margin' => 1 ), $image_url );

		// Set margin=0 for the QR code display.
		$image_url_src = add_query_arg( array( 'margin' => 0 ), $image_url );

		$image   = $generator->build_image_html( $image_url_src );
		$qr_code = sprintf( '<a href="%1$s" target="_new">%2$s</a>', esc_url( $image_url_href ), $image );

		$output  = '<div style="margin-bottom: 10px;">';
		$output .= '<p class="description">' . _x( 'Click the image below to save, print, or share your referral URL as a QR code.', 'affiliate area', 'affiliatewp-affiliate-qr-codes' ) . '</p>';
		$output .= '<p>' . $qr_code . '</p>';
		$output .= '</div>';

		echo $output;
	}

}
