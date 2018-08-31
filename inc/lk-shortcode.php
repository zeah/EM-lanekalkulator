<?php 
defined( 'ABSPATH' ) or die( 'Blank Space' ); 

final class LK_shortcode {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->wp_hooks();
	}

	private function wp_hooks() {
		if (!shortcode_exists('kalkulator')) add_shortcode('kalkulator', array($this, 'do_shortcode'));
		else add_shortcode('emkalkulator', array($this, 'do_shortcode'));
	}

	public function do_shortcode($atts, $content = null) {

		if (!is_array($atts)) $atts = [];

		$this->add_css();
		wp_enqueue_script( 'em-lanekalkulator-script', LANEKALKULATOR_PLUGIN_URL.'assets/js/pub/em-lanekalkulator.js', array(), false, true);

		$float = false;
		if ($atts['float'])
			switch ($atts['float']) {
				case 'left': $float = 'left'; break;
				case 'right': $float = 'right';
			}

		$html = '<div class="em-kalkulator"'.($float ? ' style="float: '.$float.'"' : '').'>';

		$html .= '<div class="em-kalkulator-title em-kalkulator-container">Lånekalkulator</div>';

		// beløp
		$html .= '<div class="em-kalkulator-belop-container em-kalkulator-container">
				    <label for="em-kalkulator-belop">Beløp</label>
				    <input class="em-kalkulator-input" id="em-kalkulator-belop" disabled value="250000"> Kroner<br>
					<input class="em-kalkulator-range em-kalkulator-belop-range" type="range" value="250000" max="500000" min="10000" step="1000">
				  </div>';

		// år
		$html .= '<div class="em-kalkulator-ar-container em-kalkulator-container">
					<label for="em-kalkulator-ar">Nedbetalingstid</label>
					<input class="em-kalkulator-input" id="em-kalkulator-ar" disabled value="5"> År<br>
					<input class="em-kalkulator-range em-kalkulator-ar-range" type="range" value="5" max="15" min="1" step="1">
				  </div>';
		
 		// rente
		$html .= '<div class="em-kalkulator-rente-container em-kalkulator-container">
					<label for="em-kalkulator-rente">Rente</label>
					<input class="em-kalkulator-input" id="em-kalkulator-rente" type="number" step="0.01" value="15">%<br>
					<input class="em-kalkulator-range em-kalkulator-rente-range" type="range" value="15" max="45" min="2" step="0.5">
				  </div>';

		// result
		$html .= '<div class="em-kalkulator-resultat-container em-kalkulator-container">
					Måndelig betaling
					<br><input class="em-kalkulator-resultat" disabled>
				  </div>';

		$html .= '</div>';


		return $html;

	}

	private function add_css() {
        wp_enqueue_style('em-em-kalkulator-style', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-lanekalkulator.css', array(), '1.0.0', '(min-width: 801px)');
        wp_enqueue_style('em-em-kalkulator-mobile', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-lanekalkulator-mobile.css', array(), '1.0.0', '(max-width: 800px)');
	}

}