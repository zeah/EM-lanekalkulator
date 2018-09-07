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


		$options = get_option('em_calc');
		if (!is_array($options)) $options = [];

		// wp_die('<xmp>'.print_r($options, true).'</xmp>');


		$this->add_css();
		wp_enqueue_script( 'em-lanekalkulator-script', LANEKALKULATOR_PLUGIN_URL.'assets/js/pub/em-calculator.js', array(), false, true);


		// locale
		$locale = $atts['locale'] ? sanitize_text_field($atts['locale'])
				  : ($options['locale'] ? sanitize_text_field($options['locale'])
				     : 'en-US|USD');

		// calc title
		$title = $atts['title'] ? sanitize_text_field($atts['title']) 
				 : ($options['title'] ? sanitize_text_field($options['title']) 
				 	: 'Loan Cost Calculator');

	    // amount
		$max_amount = $atts['amount-max'] ? intval($atts['amount-max']) //  value from shortcode
					  : ($options['amount_max'] ? intval($options['amount_max']) // value from customizer
					  	 : '500000'); // default value

		$default_amount = $atts['amount-default'] ? intval($atts['amount-default']) 
					  : ($options['amount_default'] ? intval($options['amount_default']) 
					  	 : '250000');

		$min_amount = $atts['amount-min'] ? intval($atts['amount-min']) 
					  : ($options['amount_min'] ? intval($options['amount_min']) 
					  	 : '10000');

		$step_amount = $atts['amount-step'] ? intval($atts['amount-step']) 
					  : ($options['amount_step'] ? intval($options['amount_step']) 
					  	 : '1000');

		$text_amount = $atts['amount-text'] ? sanitize_text_field($atts['amount-text']) 
					   : ($options['amount'] ? sanitize_text_field($options['amount'])
					   	  : 'Amount');



		// time period
		// $max_period = $atts['period-max'] ? intval($atts['period-max']) : '15';
		$max_period = $atts['period-max'] ? intval($atts['period-max']) //  value from shortcode
					  : ($options['period_max'] ? intval($options['period_max']) // value from customizer
					  	 : 15); // default value

		// $default_period = $atts['period-default'] ? intval($atts['period-default']) : intval($max_period / 2);
		$default_period = $atts['period-default'] ? intval($atts['period-default']) //  value from shortcode
					      : ($options['period_default'] ? intval($options['period_default']) // value from customizer
					  	     : 5); // default value

		// $min_period = $atts['period-min'] ? intval($atts['period-min']) : '1';
		$min_period = $atts['period-min'] ? intval($atts['period-min']) //  value from shortcode
					  : ($options['period_min'] ? intval($options['period_min']) // value from customizer
					  	 : 1); // default value

		// $step_period = $atts['period-step'] ? intval($atts['period-step']) : '1';
		$step_period = $atts['period-step'] ? intval($atts['period-step']) //  value from shortcode
					   : ($options['period_step'] ? intval($options['period_step']) // value from customizer
					  	  : 1); // default value

		$text_period = $atts['period-text'] ? sanitize_text_field($atts['period-text']) //  value from shortcode
					   : ($options['period'] ? sanitize_text_field($options['period']) // value from customizer
					  	  : 'Time Period'); // default value

		$postfix_period = $atts['period-postfix'] ? sanitize_text_field($atts['period-postfix']) //  value from shortcode
					      : ($options['period_postfix'] ? sanitize_text_field($options['period_postfix']) // value from customizer
					  	     : 'year'); // default value

		$postfixes_period = $atts['period-postfixes'] ? sanitize_text_field($atts['period-postfixes']) //  value from shortcode
					       : ($options['period_postfixes'] ? sanitize_text_field($options['period_postfixes']) // value from customizer
					  	      : 'years'); // default value



		// interest
		$default_interest = $atts['interest-default'] ? floatval($atts['interest-default']) 
						: ($options['interest_default'] ? floatval($options['interest_default']) 
						   : 15);

		$max_interest = $atts['interest-max'] ? floatval($atts['interest-max']) 
						: ($options['interest_max'] ? floatval($options['interest_max']) 
						   : 45);

		// $default_interest = $atts['interest-default'] ? intval($atts['interest-default']) : number_format(floatval($max_interest / 2), 2);
		// $min_interest = $atts['interest-min'] ? floatval($atts['interest-min']) : '2';
		$min_interest = $atts['interest-min'] ? floatval($atts['interest-min']) 
						: ($options['interest_min'] ? floatval($options['interest_min']) 
						   : 2);

		// $step_interest = $atts['interest-step'] ? floatval($atts['interest-step']) : '0.5';
		$step_interest = $atts['interest-step'] ? floatval($atts['interest-step']) 
						: ($options['interest_step'] ? floatval($options['interest_step']) 
						   : 0.5);

		// $text_interest = $atts['interest-text'] ? sanitize_text_field($atts['interest-text']) : 'Rente';
		$text_interest = $atts['interest-text'] ? sanitize_text_field($atts['interest-text']) 
						: ($options['interest'] ? sanitize_text_field($options['interest']) 
						   : 'Interest');


		// result
		// $text_result = $atts['result-text'] ? sanitize_text_field($atts['result-text']) : 'Kostnad per måned';
		$text_result = $atts['result-text'] ? floatval($atts['result-text']) 
						: ($options['result_text'] ? floatval($options['result_text']) 
						   : 'Monthly Costs');


		// colors
		$background_color = $atts['background-color'] ? esc_attr($atts['background-color']) 
							: ($options['color_background'] ? sanitize_hex_color($options['color_background']) 
								: false);



		$font_color = $atts['font-color'] ? esc_attr($atts['font-color']) 
					  : ($options['color_font'] ? sanitize_hex_color($options['color_font']) 
					  	 : false);
		
		$border = $atts['border'] ? esc_attr($atts['border']) : false;


		$right_arrow = '<svg class="em-calc-rightarrow-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve"><path class="em-calc-rightarrow-path"'.($font_color ? ' style="fill: '.$font_color.';"' : '').' d="M8.59,16.59L13.17,12L8.59,7.41L10,6l6,6l-6,6L8.59,16.59z"/><path fill="none" d="M0,0h24v24H0V0z"/></svg>';
		$left_arrow = '<svg class="em-calc-leftarrow-svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="24px" viewBox="0 0 24 24" enable-background="new 0 0 24 24" xml:space="preserve"><path class="em-calc-leftarrow-path"'.($font_color ? ' style="fill: '.$font_color.';"' : '').' d="M15.41,16.59L10.83,12l4.58-4.59L14,6l-6,6l6,6L15.41,16.59z"/><path fill="none" d="M0,0h24v24H0V0z"/></svg>';

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
		$html .= sprintf('<input class="em-calculator-amount-default" type="hidden" value="%s">', $default_amount);
		$html .= sprintf('<input class="em-calculator-interest-default" type="hidden" value="%s">', $default_interest);
		// $html .= '<input class="em-calculator-default" type="hidden" value="'.$default_amount.'">';
		$html .= sprintf('<input class="em-calculator-postfix" type="hidden" value="%s">', $postfix_period);
		$html .= sprintf('<input class="em-calculator-postfixes" type="hidden" value="%s">', $postfixes_period);

		$html .= sprintf('<input class="em-calculator-lang" type="hidden" value="%s">', $locale);

		// title 
		$html .= sprintf('<div class="em-calculator-title em-calculator-container">%s</div>', $title);

		// amount
		$html .= sprintf('<div class="em-calculator-amount-container em-calculator-container">
							<label class="em-calculator-title-amount">%s</label>
							<div><input%s class="em-calculator-input" id="em-calculator-amount" disabled></div>
							<input class="em-calculator-range em-calculator-amount-range" type="range" value="%d" max="%d" min="%d" step="%d">
						  </div>',
						  $text_amount,
						  $font_color ? ' style="color: '.$font_color.';"' : '',
						  $default_amount,
						  $max_amount,
						  $min_amount,
						  $step_amount
						);


		// period
		$html .= sprintf('<div class="em-calculator-period-container em-calculator-container">
							<label class="em-calculator-title-period" for="em-calculator-period">%s</label>
							<div><input%s class="em-calculator-input" id="em-calculator-period" value="%s%s" disabled></div>
							<input class="em-calculator-range em-calculator-period-range" type="range" value="%s" max="%s" min="%s" step="%s">
						  </div>',
						  $text_period,
						  $font_color ? ' style="color: '.$font_color.';"' : '',
						  $default_period,
						  ($default_period === 1) ? ' '.$postfix_period : ' '.$postfixes_period,
						  $default_period,
						  $max_period,
						  $min_period,
						  $step_period
						);

 		// interest
		$html .= sprintf('<div class="em-calculator-interest-container em-calculator-container">
							<label class="em-calculator-title-interest" for="em-calculator-interest">%s</label>
							<div><input%s class="em-calculator-input" id="em-calculator-interest" value=""></div>
							<input class="em-calculator-range em-calculator-interest-range" type="range" value="%s" max="%s" min="%s" step="%s">
							<div><button type="button" class="em-calc-button-left">%s</button><button type="button" class="em-calc-button-right">%s</button></div>
						</div>',
						$text_interest,
						$font_color ? ' style="color: '.$font_color.';"' : '',
						// $default_interest,
						$default_interest,
						$max_interest,
						$min_interest,
						$step_interest,
						$left_arrow,
						$right_arrow
					);

		// result
		$html .= sprintf('<div class="em-calculator-result-container em-calculator-container">
					 	<div class="em-calculator-title-result">%s</div>
					 	<div class="em-calculator-result-wrapper">
					 		<input%s class="em-calculator-result" disabled>
					 	</div>',
					 	$text_result,
						$font_color ? ' style="color: '.$font_color.';"' : ''
					 );

		$html .= '</div>';

		return $html;

	}


	private function add_css() {
        wp_enqueue_style('em-calculator-style', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator.css', array(), '1.0.0', '(min-width: 801px)');
        wp_enqueue_style('em-calculator-mobile', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator-mobile.css', array(), '1.0.0', '(max-width: 800px)');
	}

	public function footer() {


		// add js script that adds inline css in header


	}

}