<?php
/**
 * QR Code Generator
 *
 * @package     AffiliateWP Affiliate QR Codes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace AffiliateWP_Affiliate_QR_Codes;

/**
 * Implements a set of helpers for generating QR codes.
 *
 * @since 1.0.0
 */
class Generator {

	/**
	 * Retrieves the image URL for a QR code based on the given URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url URL to generate the code for.
	 * @return string Image URL.
	 */
	public function get_image_url( $url ) {
		$base_url = 'https://quickchart.io/qr';

		$query_args = array(
			'text'  => $url,
			'light' => '0000',
		);

		return add_query_arg( $query_args, $base_url );
	}

	/**
	 * Retrieves the SVG URL for a QR code based on the given URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $url URL to generate the code for.
	 * @return string SVG path.
	 */
	public function get_svg_url( $url ) {
		$image_url = $this->get_image_url( $url );

		return add_query_arg( array( 'format' => 'svg' ), $image_url );
	}

	/**
	 * Builds the img tag HTML markup from a given URL.
	 *
	 * @since 1.0.0
	 *
	 * @param string $image_url Image URL.
	 * @param int    $size      Optional. Size (in pixels) to assign the image attributes. Default 120.
	 * @return string HTML markup for the img tag.
	 */
	public function build_image_html( $image_url, $size = 120 ) {
		$size = absint( $size );

		return sprintf( '<img src="%1$s" width="%2$d" height="%2$d" />', $image_url, $size );
	}

	/**
	 * Retrieves a QR code URL for a given affiliate.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $affiliate_id Affiliate ID.
	 * @param string $format       Optional. Format to retrieve the QR code in. Accepts 'svg' and 'png'.
	 *                             Default 'png'.
	 * @return string QR code URL.
	 */
	public function get_code_for_affiliate( $affiliate_id, $format = 'png' ) {
		if ( ! in_array( $format, array( 'svg', 'png' ), true ) ) {
			$format = 'png';
		}

		$affiliate_url = affwp_get_affiliate_referral_url( array( 'affiliate_id' => $affiliate_id ) );

		if ( 'png' === $format ) {
			$qr_code_url = $this->get_image_url( $affiliate_url );
		} else {
			$qr_code_url = $this->get_svg_url( $affiliate_url );
		}

		return $qr_code_url;
	}

}
