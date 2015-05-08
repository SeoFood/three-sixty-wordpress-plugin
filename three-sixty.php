<?php
/**
 * @package Three_Sixty
 * @version 1.0
 */

/*
Plugin Name: Three Sixty Image Slider Wordpress Plugin
Plugin URI: http://www.gravio.de/magazin/
Description: This Plugin uses the Threesixty-slider jQuery Slider plugin in a nice Wordpress Plugin.
Author: Marco Hillger
Version: 1.0
Author URI: http://seofood.de/
*/

$three_sixty = new Three_Sixty();
class Three_Sixty
{
	/**
	 * If you should add the script or not
	 *
	 * @var bool
	 */
	private $addScript = false;

	public function __construct()
	{
		add_shortcode('360', array($this, 'shortcode_content'));

		// wp_enqueue_scripts
		// If you use the below the CSS and JS file will be added on everypage
		// add_action( 'wp_enqueue_scripts', array($this, 'add_shortcode_scripts'));

		// Add styles and scripts to the page
		add_action('wp_footer', array($this, 'add_shortcode_scripts'));
	}

	public function shortcode_content( $attr, $content )
	{
		$this->addScript = true;
		extract(shortcode_atts(array(
			'width' => 400,
			'height' => 200,
		), $attr));

		?>
		<h1>TESSSST</h1>
		<?php
	}

	public function add_shortcode_scripts()
	{
		if(!$this->addScript)
		{
			return false;
		}

		wp_enqueue_script('shortcode-js', get_stylesheet_directory_uri() . '/js/shortcode.js', false);
	}
}