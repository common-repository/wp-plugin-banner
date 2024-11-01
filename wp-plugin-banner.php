<?php
/*
 Plugin Name: WP Plugin Banner
 Plugin URI: https://chrisk.io
 Description: Adds a shortcode to display a banner from a plugin in the WordPress repository
 Author: cklosows
 Version: 1.0.2
 Author URI: https://chrisk.io
 Text Domain: wp-plugin-banner
 Domain Path: languages
 */

if ( ! class_exists( 'WP_Plugin_Banner' ) ) {
class WP_Plugin_Banner {
	private static $instance;

	private function __construct() {
		$this->init();
	}

	static public function instance() {

		if ( !self::$instance ) {
			self::$instance = new WP_Plugin_Banner();
		}

		return self::$instance;

	}

	private function init() {
		add_shortcode( 'plugin_banner', array( $this, 'display_banner' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_styles' ) );
	}

	public function display_banner( $atts ) {
		$atts = shortcode_atts(
			array(
				'slug'          => '',
				'link'          => false,
				'title_wrapper' => 'h2',
			),
			$atts,
			'wp_plugin_banners_display_atts'
		);

		$allowed_wrappers = array( 'h2', 'h3', 'h4', 'h5', 'p', 'strong', 'span', 'em' );
		if ( ! in_array( $atts['title_wrapper'], $allowed_wrappers ) ) {
			$atts['title_wrapper'] = 'h2';
		}

		if ( empty( $atts['slug'] ) ) {
			return;
		}

		$image_url = 'https://plugins.svn.wordpress.org/' . $atts['slug'] . '/assets/banner-772x250.png';
		$link_url  = $atts['link'] ? 'https://wordpress.org/plugins/' . $atts['slug'] : '';

		$image_exists = get_transient( 'wppb_has_img_' . $atts['slug'] );
		if ( false === $image_exists ) {
			$image_test   = wp_remote_head( $image_url );
			$image_exists = ! is_wp_error( $image_test ) && 200 == $image_test['response']['code'] ? true : '';
			set_transient( 'wppb_has_img_' . $atts['slug'], $image_exists, WEEK_IN_SECONDS );
		}

		$plugin_data = get_transient( 'wppb_data_' . $atts['slug'] );
		if ( false === $plugin_data ) {
			$request     = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.0/' . $atts['slug'] . '.json' );
			$plugin_data = ! is_wp_error( $request ) && 200 === $request['response']['code'] ? json_decode( wp_remote_retrieve_body( $request ), true ) : array();
			set_transient( 'wppb_data_' . $atts['slug'], $plugin_data, WEEK_IN_SECONDS );
		}
		ob_start();
		?>
		<<?php echo $atts['title_wrapper']; ?> itemprop="name"><?php echo $plugin_data['name']; ?></<?php echo $atts['title_wrapper']; ?>>

		<?php

		if ( empty( $image_exists ) ) {
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		};

		if ( ! empty( $link_url ) ) {
			?><a class="wp-plugin-banner-link <?php echo $atts['slug']; ?>" target="_blank" rel="noopener" href="<?php echo $link_url; ?>"><?php
		}
		?>
		<div class="plugin-title <?php echo $atts['slug']; ?>" style="background-image: url(<?php echo $image_url; ?>)">
			<div class="vignette"></div>
		</div>
		<?php
		if ( ! empty( $link_url ) ) {
			?></a><?php
		}

		$output = ob_get_contents();
		ob_end_clean();

		return $output;
	}

	public function load_styles() {
		$plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$assets_url = $plugin_url . 'assets/';
		wp_register_style( 'wp-plugin-banner', $assets_url . 'style.css', array(), false, 'all' );
		wp_enqueue_style( 'wp-plugin-banner' );
	}
}
}

function load_wp_plugin_banner() {
	return WP_Plugin_Banner::instance();
}
add_action( 'plugins_loaded', 'load_wp_plugin_banner', PHP_INT_MAX );
