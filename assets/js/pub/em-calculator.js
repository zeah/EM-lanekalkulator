(function() {

	

	var belop_text = document.querySelector('#em-calculator-amount');
	var ar_text = document.querySelector('#em-calculator-year');
	var rente_text = document.querySelector('#em-calculator-interest');

	var belop = document.querySelector('.em-calculator-amount-range');
	var ar = document.querySelector('.em-calculator-year-range');
	var rente = document.querySelector('.em-calculator-interest-range');
	var resultat = document.querySelector('.em-calculator-result');


	var default_amount = document.querySelector('.em-calculator-default');
	if (default_amount) default_amount = default_amount.value;

	var http_lang = document.querySelector('.em-calculator-lang');
	if (http_lang) http_lang = http_lang.value;

	var locale = 'en-US';
	var currency_symbol = 'USD';

	var temp_lang = http_lang.split('|');

	if (Array.isArray(temp_lang)) {
		locale = temp_lang[0];
		currency_symbol = temp_lang[1];
	}

	var currency = {
		style: 'currency',
		currency: currency_symbol,
		minimumFractionDigits: 0, 
		maximumFractionDigits: 0
	};

	belop_text.value = parseInt(default_amount).toLocaleString(locale, currency);

	if (!belop || !ar || !rente || !resultat) return;

	var b = belop.value || 250.000;
	var a = ar.value || 5;
	var r = rente.value || 15;

	function monthlyPayment(p, n, i) {
	 	return Math.floor(p / ((1-Math.pow(1+i, -n))/i));
	}

	function writeMP() {
		b = belop.value;
		a = ar.value;
		r = rente.value;

		resultat.value = monthlyPayment(b, a*12, r/100/12).toLocaleString(locale, currency);
	}

	writeMP();

	belop.addEventListener('input', function(e) {
		belop_text.value = parseInt(e.target.value).toLocaleString(locale, currency);
		writeMP();
	});

	ar.addEventListener('input', function(e) {
		ar_text.value = e.target.value;
		writeMP();
	});

	rente.addEventListener('input', function(e) {
		rente_text.value = parseFloat(e.target.value).toFixed(2);
		writeMP();
	});

	rente_text.addEventListener('input', function(e) {
		rente.value = e.target.value;
		writeMP();
	});

})();