$(() => { ((api) => {


	// locale 


	// title 
	api('em_calc[title]', (v) => v.bind((nv) => $('.em-calculator-title').text(nv)));

	// amount
	api('em_calc[amount]', (v) => v.bind((nv) => $('.em-calculator-title-amount').text(nv)));
	
	api('em_calc[amount_default]', (v) => v.bind((nv) => {
		let val = $('#em-calculator-amount').val();
		val = val.replace(/\d.*(\d|$)/, nv);
		$('#em-calculator-amount').val(val);
		$('.em-calculator-amount-range').val(nv);
	}));

	api('em_calc[amount_max]', (v) => v.bind((nv) => $('.em-calculator-amount-range').attr('max', nv)))
	api('em_calc[amount_min]', (v) => v.bind((nv) => $('.em-calculator-amount-range').attr('min', nv)))
	api('em_calc[amount_step]', (v) => v.bind((nv) => $('.em-calculator-amount-range').attr('step', nv)))

	// period
	api('em_calc[period]', (v) => v.bind((nv) => $('.em-calculator-title-period').text(nv)));

	api('em_calc[period_postfix]', (v) => v.bind((nv) => {
		$('.em-calculator-period-range').val(1);
		$('#em-calculator-period').val(1+' '+nv);
	}));

	api('em_calc[period_postfixes]', (v) => v.bind((nv) => {
		$('.em-calculator-period-range').val(2);
		$('#em-calculator-period').val(2+' '+nv);
	}));

	api('em_calc[period_default]', (v) => v.bind((nv) => {
		let val = $('#em-calculator-period').val();

		let s = api.instance('em_calc[period_postfix]').get();
		let m = api.instance('em_calc[period_postfixes]').get();

		if (nv != 1) s = m;

		$('#em-calculator-period').val(nv+' '+s);
		$('.em-calculator-period-range').val(nv);
	}));

	api('em_calc[period_max]', (v) => v.bind((nv) => $('.em-calculator-period-range').attr('max', nv)))
	api('em_calc[period_min]', (v) => v.bind((nv) => $('.em-calculator-period-range').attr('min', nv)))
	api('em_calc[period_step]', (v) => v.bind((nv) => $('.em-calculator-period-range').attr('step', nv)))

	// interest
	api('em_calc[interest]', (v) => v.bind((nv) => $('.em-calculator-title-interest').text(nv)));

	api('em_calc[interest_default]', (v) => v.bind((nv) => {
		$('#em-calculator-interest').val(nv);
		$('.em-calculator-interest-range').val(nv);
	}));

	api('em_calc[interest_max]', (v) => v.bind((nv) => $('.em-calculator-interest-range').attr('max', nv)))
	api('em_calc[interest_min]', (v) => v.bind((nv) => $('.em-calculator-interest-range').attr('min', nv)))
	api('em_calc[interest_step]', (v) => v.bind((nv) => $('.em-calculator-interest-range').attr('step', nv)))

	// result
	api('em_calc[result]', (v) => v.bind((nv) => $('.em-calculator-title-result').text(nv)));

	// colors
	api('em_calc[color_background]', (v) => v.bind((nv) => $('.em-calculator').css('background-color', nv)));

	api('em_calc[color_font]', (v) => v.bind((nv) => {
		$('.em-calculator, .em-calculator input').css('color', nv);
	}));
	
})(wp.customize); });