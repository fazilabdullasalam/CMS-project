PK     p�X����
  
  U 	 wp-content/plugins/woocommerce/src/Internal/Admin/Onboarding/OnboardingIndustries.phpUT �;#fPK     p�X���4  4  R 	 wp-content/plugins/woocommerce/src/Internal/Admin/Onboarding/OnboardingJetpack.phpUT �;#f<?php
/**
 * WooCommerce Onboarding Jetpack
 */

namespace Automattic\WooCommerce\Internal\Admin\Onboarding;

/**
 * Contains logic around Jetpack setup during onboarding.
 */
class OnboardingJetpack {
	/**
	 * Class instance.
	 *
	 * @var OnboardingJetpack instance
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 */
	final public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Init.
	 */
	public function init() {
		add_action( 'woocommerce_admin_plugins_pre_activate', array( $this, 'activate_and_install_jetpack_ahead_of_wcpay' ) );
		add_action( 'woocommerce_admin_plugins_pre_install', array( $this, 'activate_and_install_jetpack_ahead_of_wcpay' ) );

		// Always hook into Jetpack connection even if outside of admin.
		add_action( 'jetpack_site_registered', array( $this, 'set_woocommerce_setup_jetpack_opted_in' ) );
	}

	/**
	 * Sets the woocommerce_setup_jetpack_opted_in to true when Jetpack connects to WPCOM.
	 */
	public function set_woocommerce_setup_jetpack_opted_in() {
		update_option( 'woocommerce_setup_jetpack_opted_in', true );
	}

	/**
	 * Ensure that Jetpack gets installed and activated ahead of WooCommerce Payments
	 * if both are being installed/activated at the same time.
	 *
	 * See: https://github.com/Automattic/woocommerce-payments/issues/1663
	 * See: https://github.com/Automattic/jetpack/issues/19624
	 *
	 * @param array $plugins A list of plugins to install or activate.
	 *
	 * @return array
	 */
	public function activate_and_install_jetpack_ahead_of_wcpay( $plugins ) {
		if ( in_array( 'jetpack', $plugins, true ) && in_array( 'woocommerce-payments', $plugins, true ) ) {
			array_unshift( $plugins, 'jetpack' );
			$plugins = array_unique( $plugins );
		}
		return $plugins;
	}

}
PK     p�Xi@&N�  �  T 	 wp-content/plugins/woocommerce/src/Internal/Admin/Onboarding/OnboardingMailchimp.phpUT �;#f<?php
/**
 * WooCommerce Onboarding Mailchimp
 */

namespace Automattic\WooCommerce\Internal\Admin\Onboarding;

use Automattic\WooCommerce\Internal\Admin\Schedulers\MailchimpScheduler;

/**
 * Logic around updating Mailchimp during onboarding.
 */
class OnboardingMailchimp {
	/**
	 * Class instance.
	 *
	 * @var OnboardingMailchimp instance
	 */
	private static $instance = null;

	/**
	 * Get class instance.
	 */
	final public static function instance() {
		if ( ! static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Init.
	 */
	public function init() {
		add_action( 'woocommerce_onboarding_profile_data_updated', array( $this, 'on_profile_data_updated' ), 10, 2 );
	}

	/**
	 * Reset MailchimpScheduler if profile data is being updated with a new email.
	 *
	 * @param array $existing_data Existing option data.
	 * @param array $updating_data Updating option data.
	 */
	public function on_profile_data_updated( $existing_data, $updating_data ) {
		if (
			isset( $existing_data['store_email'] ) &&
			isset( $updating_data['store_email'] ) &&
			$existing_data['store_email'] !== $updating_data['store_email']
		) {
			MailchimpScheduler::reset();
		}
	}
}
PK     p�X��\  \  S 	 wp-content/plugins/woocommerce/src/Internal/Admin/Onboarding/OnboardingProducts.phpUT �;#f<?php
/**
 * WooCommerce Onboarding Products
 */

namespace Automattic\WooCommerce\Internal\Admin\Onboarding;

use Automattic\WooCommerce\Admin\Features\Features;
use Automattic\WooCommerce\Internal\Admin\Onboarding\OnboardingProfile;
use Automattic\WooCommerce\Admin\Loader;
use Automattic\WooCommerce\Admin\PluginsHelper;

/**
 * Class for handling product types and data around product types.
 */
class OnboardingProducts {

	/**
	 * Name of product data transient.
	 *
	 * @var string
	 */
	const PRODUCT_DATA_TRANSIENT = 'wc_onboarding_product_data';

	/**
	 * Get a list of allowed product types for the onboarding wizard.
	 *
	 * @return array
	 */
	public static function get_allowed_product_types() {
		$products         = array(
			'physical'        => array(
				'label'   => __( 'Physical products', 'woocommerce' ),
				'default' => true,
			),
			'downloads'       => array(
				'label' => __( 'Downloads', 'woocommerce' ),
			),
			'subscriptions'   => array(
				'label' => __( 'Subscriptions', 'woocommerce' ),
			),
			'memberships'     => array(
				'label'   => __( 'Memberships', 'woocommerce' ),
				'product' => 958589,
			),
			'bookings'        => array(
				'label'   => __( 'Bookings', 'woocommerce' ),
				'product' => 390890,
			),
			'product-bundles' => array(
				'label'   => __( 'Bundles', 'woocommerce' ),
				'product' => 18716,
			),
			'prod