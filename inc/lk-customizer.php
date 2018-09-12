<?php 

defined( 'ABSPATH' ) or die( 'Blank Space' );

final class LK_customizer {
	/* singleton */
	private static $instance = null;

	public static function get_instance() {
		if (self::$instance === null) self::$instance = new self();

		return self::$instance;
	}

	private function __construct() {
		$this->hooks();
	}

	private function hooks() {
		add_action('customize_register', array($this, 'customizer'));
		add_action('customize_preview_init', array($this, 'customizer_sands'), 9999);
		add_action('admin_enqueue_scripts', array($this, 'enqueue_sands'));

	}

	public function customizer_sands() {
		wp_enqueue_script('em_calc_customizer', LANEKALKULATOR_PLUGIN_URL.'/assets/js/admin/em-calc-customizer.js', array('jquery','customize-preview'), '0.0.1', true);
	}

	public function enqueue_sands() {
        wp_enqueue_style('em-calc-style', LANEKALKULATOR_PLUGIN_URL.'assets/css/admin/em-calculator.css');
	}

	public function customizer($cust) {

		// LOCALE DROP DOWN
		$locale = [
			'en-US|USD' => __('USA'),
			'en-GB|GBP' => __('UK'),
			'zh-CN|CNY' => __('China'),
			'ja-JP|JPY' => __('Japan'),
			'nb-NO|NOK' => __('Norway'),
			'sv-SE|SEK' => __('Sweden'),
			'da-DK|DKK' => __('Denmark'),
			'fr-FR|EUR' => __('France'),
			'de-DE|EUR' => __('Germany'),
			'es-AR|ARS' => __('Argentina'),
			'de-AT|EUR' => __('Austria'),
			'en-AU|AUD' => __('Australia'),
			'en-CA|CAD' => __('Canada'),
			'es-CL|CLP' => __('Chile'),
			'es-CO|COP' => __('Colombia'),
			'es-CR|CRC' => __('Costa Rica'),
			'cr-HR|HRK' => __('Croatia'),
			'ar-EG|EGP' => __('Egypt'),
			'fi-FI|EUR' => __('Finland'),
			'el-GR|EUR' => __('Greece'),
			'zh-HK|HKD' => __('Hong Kong'),
			'hu-HU|HUF' => __('Hungary'),
			'id-ID|IDR' => __('Indonesia'),
			'lv-LV|EUR' => __('Latvia'),
			'is-IS|ISK' => __('Iceland'),
			'lt-LT|EUR' => __('Lithuanian'),
			'sa-IN|INR' => __('India'),
			'en-IE|EUR' => __('Ireland'),
			'he-IL|ILS' => __('Israel'),
			'it-IT|EUR' => __('Italy'),
			'de-LU|EUR' => __('Luxembourg'),
			'mt-MT|EUR' => __('Malta'),
			'fr-MC|EUR' => __('Monaco'),
			'es-MX|MXV' => __('Mexico'),
			'ne-NE|EUR' => __('Netherlands'),
			'es-NZ|NZD' => __('New Zealand'),
			'en-PH|PHP' => __('Philippine'),
			'pl-PL|PLN' => __('Poland'),
			'pt-PT|EUR' => __('Portugal'),
			'ro-RO|RON' => __('Romania'),
			'ru-RU|RUB' => __('Russia'),
			'ar-SA|SAR' => __('Saudi Arabia'),
			'sr-SP|RSD' => __('Serbia'),
			'sk-SK|EUR' => __('Slovakia'),
			'sl-SI|EUR' => __('Slovenia'),
			'es-ES|EUR' => __('Spain'),
			'ko-KO|KRW' => __('South Korea'),
			'fr-CH|CHF' => __('Switzerland'),
			'zh-SG|SGD' => __('Singapore'),
			'th-TH|THB' => __('Thailand'),
			'uk-UA|UAH' => __('Ukraine'),
			'es-UY|UYU' => __('Uruguay'),
			'vi-VI|VND' => __('Vietnam'),
		];


		/* adding section */
		$cust->add_section('em_calc_section', [
			'title' => 'Calculator Plugin',
			'priority' => 500
		]);


		/* language */
		$cust->add_setting('em_calc[locale]', [
			'type' => 'option',
			'capability' => 'manage_options',
			'default' => 'en-US|USD',
			'sanitize_callback' => 'sanitize_text_field'
		]);
		$cust->add_control('em_calc[locale]', [
			'type' => 'select',
			'label' => _x('Locale', 'Language locality'),
			'description' => __('Set Currecy denomination and regional number formatting.'),
			'choices' => $locale,
			'section' => 'em_calc_section'
		]);


		// setting args
		$args = [
			'type' => 'option',
			'capability' => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field',
			'transport' => 'postMessage'
		];

		$args_nr = [
			'type' => 'option',
			'capability' => 'manage_options',
			'sanitize_callback' => 'absint',
			'transport' => 'postMessage'
		];


		// title
		$cust->add_setting('em_calc[title]', $args);
		$cust->get_setting('em_calc[title]')->default = _x('Loan Cost Calculator', 'default title for loan calculator');
		$cust->add_control('em_calc[title]', [
			'type' => 'text',
			'label' => __('Title'),
			'section' => 'em_calc_section'
		]);


		// amount title
		$cust->add_setting('em_calc[amount]', $args);
		$cust->get_setting('em_calc[amount]')->default = _x('Amount', 'title of amount of money field');
		$cust->add_control('em_calc[amount]', [
			'type' => 'text',
			'label' => __('Amount Text'),
			'section' => 'em_calc_section'
		]);

		// amount initial value
		$cust->add_setting('em_calc[amount_default]', $args_nr);
		$cust->get_setting('em_calc[amount_default]')->default = 250000;		
		$cust->add_control('em_calc[amount_default]', [
			'type' => 'number',
			'label' => __('default value'),
			'section' => 'em_calc_section'

		]);

		// amount max value
		$cust->add_setting('em_calc[amount_max]', $args_nr);
		$cust->get_setting('em_calc[amount_max]')->default = 500000;		
		$cust->add_control('em_calc[amount_max]', [
			'type' => 'number',
			'label' => __('max value'),
			'section' => 'em_calc_section'

		]);

		// amount min value
		$cust->add_setting('em_calc[amount_min]', $args_nr);
		$cust->get_setting('em_calc[amount_min]')->default = 10000;		
		$cust->add_control('em_calc[amount_min]', [
			'type' => 'number',
			'label' => __('min value'),
			'section' => 'em_calc_section'

		]);

		// amount input step
		$cust->add_setting('em_calc[amount_step]', $args_nr);
		$cust->get_setting('em_calc[amount_step]')->default = 1000;		
		$cust->add_control('em_calc[amount_step]', [
			'type' => 'number',
			'label' => __('step value'),
			'description' => __('Don\'t set the value too low.'),
			'section' => 'em_calc_section'

		]);



		// period title
		$cust->add_setting('em_calc[period]', $args);
		$cust->get_setting('em_calc[period]')->default = _x('Time Period', 'default title for time period field');
		$cust->add_control('em_calc[period]', [
			'type' => 'text',
			'label' => __('Time Period Text'),
			'section' => 'em_calc_section'
		]);

		// period plural period postfix
		$cust->add_setting('em_calc[period_postfix]', $args);
		$cust->get_setting('em_calc[period_postfix]')->default = _x('year', 'default postfix for time period field');
		$cust->add_control('em_calc[period_postfix]', [
			'type' => 'text',
			'label' => __('Time Period Postfix when singular'),
			'description' => __('The input represents time in years'),
			'section' => 'em_calc_section'
		]);

		// period singular period postfix
		$cust->add_setting('em_calc[period_postfixes]', $args);
		$cust->get_setting('em_calc[period_postfixes]')->default = _x('years', 'default postfix for time period field when plural postfixes');
		$cust->add_control('em_calc[period_postfixes]', [
			'type' => 'text',
			'label' => __('Time Period Postfix when plural'),
			'section' => 'em_calc_section'
		]);


		// period initial value
		$cust->add_setting('em_calc[period_default]', $args_nr);
		$cust->get_setting('em_calc[period_default]')->default = 5;		
		$cust->add_control('em_calc[period_default]', [
			'type' => 'number',
			'label' => __('default value'),
			'section' => 'em_calc_section'

		]);

		// period max value
		$cust->add_setting('em_calc[period_max]', $args_nr);
		$cust->get_setting('em_calc[period_max]')->default = 15;		
		$cust->add_control('em_calc[period_max]', [
			'type' => 'number',
			'label' => __('max value'),
			'section' => 'em_calc_section'

		]);

		// period min value
		$cust->add_setting('em_calc[period_min]', $args_nr);
		$cust->get_setting('em_calc[period_min]')->default = 1;		
		$cust->add_control('em_calc[period_min]', [
			'type' => 'number',
			'label' => __('min value'),
			'section' => 'em_calc_section'

		]);

		// period input step
		$cust->add_setting('em_calc[period_step]', $args_nr);
		$cust->get_setting('em_calc[period_step]')->default = 1;		
		$cust->add_control('em_calc[period_step]', [
			'type' => 'number',
			'label' => __('step value'),
			'section' => 'em_calc_section'

		]);



		// Interest title
		$cust->add_setting('em_calc[interest]', $args);
		$cust->get_setting('em_calc[interest]')->default = _x('Interest', 'default title for (effective) interest field');
		$cust->add_control('em_calc[interest]', [
			'type' => 'text',
			'label' => __('Interest Text'),
			'description' => __('Interest is the yearly effective interest'),
			'section' => 'em_calc_section'
		]);

		// interest initial value
		$cust->add_setting('em_calc[interest_default]', $args_nr);
		$cust->get_setting('em_calc[interest_default]')->default = 15;		
		$cust->add_control('em_calc[interest_default]', [
			'type' => 'number',
			'label' => __('default value'),
			'section' => 'em_calc_section'

		]);

		// interest max value
		$cust->add_setting('em_calc[interest_max]', $args_nr);
		$cust->get_setting('em_calc[interest_max]')->default = 45;		
		$cust->add_control('em_calc[interest_max]', [
			'type' => 'number',
			'label' => __('max value'),
			'section' => 'em_calc_section'

		]);

		// interest min value
		$cust->add_setting('em_calc[interest_min]', $args_nr);
		$cust->get_setting('em_calc[interest_min]')->default = 2;		
		$cust->add_control('em_calc[interest_min]', [
			'type' => 'number',
			'label' => __('min value'),
			'section' => 'em_calc_section'

		]);

		// interest input step
		$cust->add_setting('em_calc[interest_step]', $args_nr);
		$cust->get_setting('em_calc[interest_step]')->default = 0.5;		
		$cust->add_control('em_calc[interest_step]', [
			'type' => 'number',
			'label' => __('step value'),
			'section' => 'em_calc_section'

		]);


		// result title
		$cust->add_setting('em_calc[result]', $args);
		$cust->get_setting('em_calc[result]')->default = _x('Monthly Costs', 'default title for result field');
		$cust->add_control('em_calc[result]', [
			'type' => 'text',
			'label' => __('Result Text'),
			'section' => 'em_calc_section'
		]);



		// COLORS

		// background-color 
		$cust->add_setting('em_calc[color_background]', [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability' => 'manage_options'
		]);

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'em_calc[color_background]', [
				'label' => __('Background-color'),
				'section' => 'em_calc_section',

			])
		);

		// font color
		$cust->add_setting('em_calc[color_font]', [
			'type' => 'option',
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',
			'capability' => 'manage_options'
		]);

		$cust->add_control(
			new WP_Customize_Color_Control($cust, 'em_calc[color_font]', [
				'label' => __('Font color'),
				'section' => 'em_calc_section',

			])
		);
	}

}