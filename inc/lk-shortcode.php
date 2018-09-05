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

		// wp_die('<xmp>'.print_r(get_option('em_calc_locale'), true).'</xmp>');

		$this->add_css();
		wp_enqueue_script( 'em-lanekalkulator-script', LANEKALKULATOR_PLUGIN_URL.'assets/js/pub/em-calculator.js', array(), false, true);

		$max_amount = $atts['amount-max'] ? intval($atts['amount-max']) : '500000';
		$default_amount = $atts['amount-default'] ? intval($atts['amount-default']) : intval($max_amount / 2);
		$min_amount = $atts['amount-min'] ? intval($atts['amount-min']) : '10000';
		$step_amount = $atts['amount-step'] ? intval($atts['amount-step']) : '1000';
		$text_amount = $atts['amount-text'] ? sanitize_text_field($atts['amount-text']) : 'Beløp';
		// $postfix_amount = $atts['amount-postfix'] ? sanitize_text_field($atts['amount-postfix']) : 'Kr';
		// $prefix_amount = $atts['amount-prefix'] ? sanitize_text_field($atts['amount-prefix']) : false;

		if ($prefix_amount) $postfix_amount = '';

		$max_year = $atts['year-max'] ? intval($atts['year-max']) : '15';
		$default_year = $atts['year-default'] ? intval($atts['year-default']) : intval($max_year / 2);
		$min_year = $atts['year-min'] ? intval($atts['year-min']) : '1';
		$step_year = $atts['year-step'] ? intval($atts['year-step']) : '1';
		$text_year = $atts['year-text'] ? sanitize_text_field($atts['year-text']) : 'Nedbetaling';
		$postfix_year = $atts['year-postfix'] ? sanitize_text_field($atts['year-postfix']) : 'År';

		$max_interest = $atts['interest-max'] ? floatval($atts['interest-max']) : '45';
		$default_interest = $atts['interest-default'] ? intval($atts['interest-default']) : number_format(floatval($max_interest / 2), 2);
		$min_interest = $atts['interest-min'] ? floatval($atts['interest-min']) : '2';
		$step_interest = $atts['interest-step'] ? floatval($atts['interest-step']) : '0.5';
		$text_interest = $atts['interest-text'] ? sanitize_text_field($atts['interest-text']) : 'Rente';

		$text_result = $atts['result-text'] ? sanitize_text_field($atts['result-text']) : 'Kostnad per måned';

		$background_color = $atts['background-color'] ? esc_attr($atts['background-color']) : false;

		$font_color = $atts['font-color'] ? esc_attr($atts['font-color']) : false;
		
		$border = $atts['border'] ? esc_attr($atts['border']) : false;


		$float = false;
		if ($atts['float'])
			switch ($atts['float']) {
				case 'left': $float = 'left'; break;
				case 'right': $float = 'right';
			}


		// container style
		$style = false;
		$style_text = ' style="';

		if ($float) {
			$style = true;
			$style_text .= 'float: '.$float.';';
		}

		if ($background_color) {
			$style = true;
			$style_text .= 'background-color: '.$background_color.';';
		}

		if ($font_color) {
			$style = true;
			$style_text .= 'color: '.$font_color.';';
		}

		if ($border) {
			$style = true;
			$style_text .= 'border: '.$border.';';
		}

		$style_text .= '"';


		// container 
		$html = '<div class="em-calculator"'.($style ? $style_text : '').'>';

		// info for js
		$html .= '<input class="em-calculator-default" type="hidden" value="'.$default_amount.'">';
		$html .= '<input class="em-calculator-lang" type="hidden" value="nb-NO|NOK">';

		// title 
		$html .= '<div class="em-calculator-title em-calculator-container">Lånekalkulator</div>';

		// amount
		$html .= sprintf('<div class="em-calculator-amount-container em-calculator-container">
							<div>%s</div>
							<div><input class="em-calculator-input" id="em-calculator-amount" disabled></div>
							<input class="em-calculator-range em-calculator-amount-range" type="range" value="%d" max="%d" min="%d" step="%d">
						  </div>',
						  $text_amount,
						  $default_amount,
						  $max_amount,
						  $min_amount,
						  $step_amount
						);


		// year
		$html .= sprintf('<div class="em-calculator-year-container em-calculator-container">
							<label for="em-calculator-year">%s</label>
							<div><input%s class="em-calculator-input" id="em-calculator-year" value="%s" disabled><span> %s</span></div>
							<input class="em-calculator-range em-calculator-year-range" type="range" value="%s" max="%s" min="%s" step="%s">
						  </div>',
						  $text_year,
						  $font_color ? ' style="color: '.$font_color.';"' : '',
						  $default_year,
						  $postfix_year,
						  $default_year,
						  $max_year,
						  $min_year,
						  $step_year
						);

 		// interest
		$html .= sprintf('<div class="em-calculator-interest-container em-calculator-container">
							<label for="em-calculator-interest">%s</label>
							<div><input%s class="em-calculator-input" id="em-calculator-interest" type="number" step="0.01" value="%s">%%</div>
							<input class="em-calculator-range em-calculator-interest-range" type="range" value="%s" max="%s" min="%s" step="%s">
						</div>',
						$text_interest,
						$font_color ? ' style="color: '.$font_color.';"' : '',
						$default_interest,
						$default_interest,
						$max_interest,
						$min_interest,
						$step_interest
					);

		// result
		$html .= sprintf('<div class="em-calculator-result-container em-calculator-container">
					 	<div>%s</div>
					 	<div class="em-calculator-result-wrapper">
					 		<input class="em-calculator-result" disabled>
					 	</div>',
					 	$text_result
					 );

		$html .= '</div>';

		return $html;

	}


	private function add_css() {
        wp_enqueue_style('em-em-calculator-style', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator.css', array(), '1.0.0', '(min-width: 801px)');
        wp_enqueue_style('em-em-calculator-mobile', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator-mobile.css', array(), '1.0.0', '(max-width: 800px)');
	}

	public function footer() {


		// add js script that adds inline css in header


	}

}