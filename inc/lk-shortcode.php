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
		wp_enqueue_script( 'em-lanekalkulator-script', LANEKALKULATOR_PLUGIN_URL.'assets/js/pub/em-calculator.js', array(), false, true);
		// wp_die('<xmp>'.print_r($atts, true).'</xmp>');
		

		$max_amount = $atts['amount-max'] ? intval($atts['amount-max']) : '500000';
		$default_amount = $atts['amount-default'] ? intval($atts['amount-default']) : intval($max_amount / 2);
		$min_amount = $atts['amount-min'] ? intval($atts['amount-min']) : '10000';
		$step_amount = $atts['amount-step'] ? intval($atts['amount-step']) : '1000';
		$text_amount = $atts['amount-text'] ? sanitize_text_field($atts['amount-text']) : 'Beløp';
		$postfix_amount = $atts['amount-postfix'] ? sanitize_text_field($atts['amount-postfix']) : 'Kr';
		$prefix_amount = $atts['amount-prefix'] ? sanitize_text_field($atts['amount-prefix']) : false;

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


		// wp_die('<xmp>'.print_r($max_amount, true).'</xmp>');
		
		$float = false;
		if ($atts['float'])
			switch ($atts['float']) {
				case 'left': $float = 'left'; break;
				case 'right': $float = 'right';
			}

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

		$locale = ( isset($_COOKIE['locale']) ) ? $_COOKIE['locale'] : $_SERVER['HTTP_ACCEPT_LANGUAGE'];

		// setlocale(LC_ALL, 'nb_NO');
		setlocale(LC_ALL, $locale);
		$locale = localeconv();
		// wp_die('<xmp>'.print_r($locale, true).'</xmp>');
// 
		

		$html = '<div class="em-calculator"'.($style ? $style_text : '').'>';
		$html .= '<input class="em-calculator-thousands-sep" type="hidden" value="'.$locale['thousands_sep'].'">';
		// $html .= '<input class="em-calculator-decimal-point" type="hidden" value="'.$locale['decimal_point'].'">';
		// $html = '<div class="em-calculator"'.($float ? ' style="float: '.$float.'"' : '').'>';

		$html .= '<div class="em-calculator-title em-calculator-container">Lånekalkulator</div>';

		// beløp

		$html .= sprintf('<div class="em-calculator-amount-container em-calculator-container">
							<div>%s</div>
							<div>%s%s<span class="em-calculator-input" id="em-calculator-amount">%s</span><span>%s%s</span></div>
							<input class="em-calculator-range em-calculator-amount-range" type="range" value="%d" max="%d" min="%d" step="%d">
						  </div>',
						  $text_amount,
						  $locale['p_cs_precedes'] ? $locale['currency_symbol'] : '',
						  ($locale['p_cs_precedes'] && $locale['p_sep_by_space']) ? ' ' : '',
						  number_format($default_amount, 0, $locale['decimal_point'], $locale['thousands_sep']),
						  (!$locale['p_cs_precedes'] && !$locale['p_sep_by_space']) ? '' : ' ',
						  $locale['p_cs_precedes'] ? '' : locale['currency_symbol'],
						  $default_amount,
						  $max_amount,
						  $min_amount,
						  $step_amount
						);

		// $html .= '<div class="em-calculator-amount-container em-calculator-container">
		// 		    <label for="em-calculator-amount">'.$text_amount.'</label>
		// 		    <div>'.$prefix_amount.'<span class="em-calculator-input" id="em-calculator-amount">'.number_format($default_amount, 0, $locale['decimal_point'], $locale['thousands_sep']).'</span> '.$postfix_amount.'</div>
		// 			<input class="em-calculator-range em-calculator-amount-range" type="range" value="'.$default_amount.'" max="'.$max_amount.'" min="'.$min_amount.'" step="'.$step_amount.'">
		// 		  </div>';
		// $html .= '<div class="em-calculator-amount-container em-calculator-container">
		// 		    <label for="em-calculator-amount">'.$text_amount.'</label>
		// 		    <div>'.$prefix_amount.'<input class="em-calculator-input" id="em-calculator-amount" disabled value="'.number_format($default_amount, 0, ',', '.').'"> '.$postfix_amount.'</div>
		// 			<input class="em-calculator-range em-calculator-amount-range" type="range" value="'.$default_amount.'" max="'.$max_amount.'" min="'.$min_amount.'" step="'.$step_amount.'">
		// 		  </div>';

		// år
		$html .= '<div class="em-calculator-year-container em-calculator-container">
					<label for="em-calculator-year">'.$text_year.'</label>
					<div><input'.($font_color ? ' style="color: '.$font_color.';"' : '').' class="em-calculator-input" id="em-calculator-year" disabled value="'.$default_year.'"> '.$postfix_year.'</div>
					<input class="em-calculator-range em-calculator-year-range" type="range" value="'.$default_year.'" max="'.$max_year.'" min="'.$min_year.'" step="'.$step_year.'">
				  </div>';

 		// rente
		$html .= '<div class="em-calculator-interest-container em-calculator-container">
					<label for="em-calculator-interest">'.$text_interest.'</label>
					<div><input'.($font_color ? ' style="color: '.$font_color.';"' : '').' class="em-calculator-input" id="em-calculator-interest" type="number" step="0.01" value="'.$default_interest.'">%</div>
					<input class="em-calculator-range em-calculator-interest-range" type="range" value="'.$default_interest.'" max="'.$max_interest.'" min="'.$min_interest.'" step="'.$step_interest.'">
				  </div>';

		// result
		// $html .= '<div class="em-calculator-result-container em-calculator-container">
		// 			<div>'.$text_result.'</div>
		// 			<div class="em-calculator-result-wrapper">'.$prefix_amount.'<input'.($font_color ? ' style="color: '.$font_color.';"' : '').' class="em-calculator-result" disabled><span>'.$postfix_amount.'</span></div>
		// 		  </div>';
		// 		  
		$html .= sprintf('<div class="em-calculator-result-container em-calculator-container">
						 	<div>%s</div>
						 	<div class="em-calculator-result-wrapper">
						 		<span>%s%s</span>
						 		<input class="em-calculator-result" disabled>
						 		<span>%s%s</span>
						 	</div>',
						 	$text_result,
							  $locale['p_cs_precedes'] ? $locale['currency_symbol'] : '',
							  ($locale['p_cs_precedes'] && $locale['p_sep_by_space']) ? ' ' : '',
							  (!$locale['p_cs_precedes'] && !$locale['p_sep_by_space']) ? ' ' : '',
							  $locale['p_cs_precedes'] ? '' : locale['currency_symbol']
						 );

		$html .= '</div>';

		setlocale(LC_ALL, null);

		return $html;

	}

	private function add_currency($text, $locale) {
		if (!is_array($locale)) return $text;

		// prefix
		
		// text 
		
		// postfix

	} 

	private function add_css() {
        wp_enqueue_style('em-em-calculator-style', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator.css', array(), '1.0.0', '(min-width: 801px)');
        wp_enqueue_style('em-em-calculator-mobile', LANEKALKULATOR_PLUGIN_URL.'assets/css/pub/em-calculator-mobile.css', array(), '1.0.0', '(max-width: 800px)');
	}

}