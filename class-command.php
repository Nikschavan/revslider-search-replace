<?php

class Revslider_Search_Replace extends WP_CLI_Command {
	/**
	 * WP CLI Command to search replace the website URLs in the Revolution sliders
	 *
	 * ## OPTIONS
	 *
	 * <id>
	 *      ID of the slider, also takes "all" as option where it will search accross all the sliders
	 *
	 * <source-url>
	 *      Source URL
	 *
	 * <destination-url>
	 *      destination URL
	 *
	 * [--network]
	 *      Search Replace the strings in Revolution sliders throughout all the sites in multisite network
	 *
	 * ## EXAMPLES
	 *
	 *  1. wp rsr 2 <source-url> <destination-url>
	 *      - This will search replace the strings in the slider with is "2"
	 *  2. wp rsr all <source-url> <destination-url>
	 *      - This will search replace the strings on all the sliders on the site
	 *  3. wp rsr all <source-url> <destination-url> --network
	 *		- This command will will search replace the strings on all sliders accross all the sites in multisite network.
	 *
	*/

	public $slider;

	public function __invoke( $args, $assoc_args ) {

		$network = false;

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

		if ( isset( $assoc_args['network'] ) && $assoc_args['network'] == true && is_multisite() ) {
			$network = true;
		}

		if ( $id == "" ) {
			WP_CLI::error( "Plese enter ID of the slider which you want to search-replace into or 'all' to select all the sliders" );

			return false;
		}

		if ( $source == "" ) {
			WP_CLI::error( "Please enter source URL" );

			return false;
		}

		if ( $destination == "" ) {
			WP_CLI::error( "Please enter destination URL" );

			return false;
		}

		$data = array(
			'url_from' => $source,
			'url_to'   => $destination
		);

		$this->slider = new RevSliderSlider();

		if ( $network == true ) {
			$blogs = wp_get_sites();
			foreach ( $blogs as $keys => $blog ) {
				$blog_id = $blogs[ $keys ]['blog_id'];
				switch_to_blog( $blog_id );
				WP_CLI::success( "Switched to blog " . get_option( 'home' ) );
				$this->set_id_and_replace( $id, $data );
				restore_current_blog();
			}
		} else {
			$this->set_id_and_replace( $id, $data );
		}

	}

	public function set_id_and_replace( $id, $data ) {

		if ( $id == 'all' ) {
			$arrSliders = $this->slider->getArrSliders();
			foreach ( $arrSliders as $key => $value ) {
				$data["sliderid"] = $value->getID();
				$this->replace_revslider_urls( $data );
			}
		} else {
			$data["sliderid"] = $id;
			$this->replace_revslider_urls( $data );
		}

	}

	public function replace_revslider_urls( $data ) {

		$this->slider->replaceImageUrlsFromData( $data );
		WP_CLI::success( "Search Replace complete for slider id : " . $data['sliderid'] );

	}

}

if ( class_exists( 'WP_CLI' ) ) {
	WP_CLI::add_command( 'rsr', 'Revslider_Search_Replace' );
}