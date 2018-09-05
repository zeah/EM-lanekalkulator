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
	}

	public function customizer($cust) {

		$locale = [
			'en-US|USD' => 'USA',
			'en-GB|GBP' => 'UK',
			'nb-NO|NOK' => 'Norway',
		];

		$cust->add_section('em_calc_section', [
			'title' => 'Calculator Plugin',
			'priority' => 500
		]);

		$cust->add_setting('em_calc_locale', [
			'type' => 'option',
			'capability' => 'manage_options',
			'default' => 'en-US|USD',
			'sanitize_callback' => 'sanitize_text_field'
		]);

		$cust->add_control('em_calc_locale', [
			'type' => 'select',
			'label' => 'Locale',
			'choices' => $locale,
			'section' => 'em_calc_section'
		]);


		$args = [
			'type' => 'option',
			'capability' => 'manage_options',
			'sanitize_callback' => 'sanitize_text_field'
		];

	}

}