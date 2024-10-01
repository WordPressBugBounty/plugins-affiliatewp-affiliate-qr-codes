<?php
/**
 * Affiliate Portal Integration
 *
 * @package     AffiliateWP Affiliate QR Codes
 * @subpackage  Core
 * @copyright   Copyright (c) 2021, Sandhills Development, LLC
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0.0
 */
namespace AffiliateWP_Affiliate_QR_Codes;

use AffiliateWP_Affiliate_Portal\Core\Components\Controls;
use AffiliateWP_Affiliate_Portal\Core\Controls_Registry;

/**
 * Sets up front end Affiliate Portal view changes.
 *
 * @since 1.0.0
 */
class Affiliate_Portal {

	/**
	 * Sets up hook callbacks for integration with the Affiliate Area.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'affwp_portal_controls_registry_init', array( $this, 'add_controls' ), 11 );
	}

	/**
	 * Registers controls to display a QR code in the Affiliate URLs view in the Affiliate Portal.
	 *
	 * @since 1.0.0
	 *
	 * @param Controls_Registry $registry Controls registry.
	 */
	public function add_controls( $registry ) {
		$affiliate_id = affwp_get_affiliate_id();

		$generator = affiliatewp_affiliate_qr_codes()->generator;
		$image_url = $generator->get_code_for_affiliate( $affiliate_id );

		// Set margin=1 for the QR code link.
		$image_url_href = add_query_arg( array( 'margin' => 1 ), $image_url );

		// Set margin=0 for the QR code display.
		$image_url_src = add_query_arg( array( 'margin' => 0 ), $image_url );

		// Always display the description.
		$desc_control = new Controls\Paragraph_Control( array(
			'id'       => 'affwp_aqrc_qr_code_desc',
			'view_id'  => 'urls',
			'section'  => 'referral-url',
			'priority' => 10,
			'atts'     => array(
				'class' => array( 'mb-2', 'text-sm', 'leading-5', 'text-gray-500' ),
			),
			'args'     => array(
				'text' => _x( 'Click the image below to save, print, or share your referral URL as a QR code.', 'affiliate portal', 'affiliatewp-affiliate-qr-codes' ),
			)
		) );

		$registry->add_control( $desc_control );

		if ( class_exists( 'AffiliateWP_Affiliate_Portal\\Core\\Components\\Controls\\Image_Control' ) ) {

			$link_control = new Controls\Link_Control( array(
				'id'       => 'affwp_aqrc_qr_code',
				'view_id'  => 'urls',
				'section'  => 'referral-url',
				'priority' => 10,
				'atts'     => array(
					'href'   => $image_url_href,
					'target' => '_blank',
				),
				'args'     => array(
					'image' => new Controls\Image_Control( array(
						'id'   => 'affwp_aqrc_qr_code_image',
						'atts' => array(
							'src'    => $image_url_src,
							'width'  => 120,
							'height' => 120,
							'alt'    => __( 'Referral URL QR code', 'affiliatewp-affiliate-portal' ),
						),
					) ),
				),
			) );

			$registry->add_control( $link_control );

		} else {

			$image   = $generator->build_image_html( $image_url_src );
			$qr_code = sprintf( '<a href="%1$s" target="_blank">%2$s</a>', esc_url( $image_url_href ), $image );

			// "Image" control.
			$control = new Controls\Text_Control( array(
				'id'       => 'affwp_aqrc_qr_code',
				'view_id'  => 'urls',
				'section'  => 'referral-url',
				'priority' => 11,
				'args'     => array(
					'label' => __( 'Referral URL QR code', 'affiliatewp-affiliate-qr-codes' ),
				),
				'alpine'   => array(
					'x-data' => "{ qr_code: '" . $qr_code . "'}",
					'x-html' => 'qr_code',
				),
			) );

			$registry->add_control( $control );

		}

	}

}
