<?php
/*
Plugin Name: Revslider-search-replace
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: revslider-search-replace
Domain Path: /languages
*/

// wp rsr --id=0-9|all <source> <destination>

class Revslider_Search_Replace {

	public $slider;

	public function __invoke( $args ) {

		if ( ! class_exists( 'RevSliderSlider' ) ) {
			WP_CLI::error( "Revolution slider is not active" );

			return false;
		}

		$default = array(
			0 => '',
			1 => '',
			2 => '',
		);


		if ( ! isset( $args[0] ) ) {
			$args[0] = $default[0];
		}

		if ( ! isset( $args[1] ) ) {
			$args[1] = $default[1];
		}

		if ( ! isset( $args[2] ) ) {
			$args[2] = $default[2];
		}

		$id          = $args[0];
		$source      = $args[1];
		$destination = $args[2];

		$data = array(
			'sliderid' => $id,
			'url_from' => $source,
			'url_to'   => $destination
		);

		$this->slider = new RevSliderSlider();
		$this->replace_revslider_urls( $data );
	}

	public function replace_revslider_urls( $data ) {

		$id = $data["sliderid"];

		if ( $id == "all" ) {
			$arrSliders = $this->slider->getArrSliders();

			foreach ( $arrSliders as $key => $value ) {
				$id               = $value->getID();
				$data["sliderid"] = $id;
				$this->slider->replaceImageUrlsFromData( $data );
				WP_CLI::success( "strings replaced for slider : " . $id );
			}
		} else {
			$this->slider->replaceImageUrlsFromData( $data );
			WP_CLI::success( "strings replaced for slider : " . $id );
		}

	}

}

if ( class_exists( 'WP_CLI' ) ) {
	WP_CLI::add_command( 'rsr', 'Revslider_Search_Replace' );
}