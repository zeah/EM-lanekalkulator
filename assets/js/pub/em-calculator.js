(function() {

	

	var belop_text = document.querySelector('#em-calculator-amount');
	var ar_text = document.querySelector('#em-calculator-period');
	var interest_text = document.querySelector('#em-calculator-interest');

	var belop = document.querySelector('.em-calculator-amount-range');
	var ar = document.querySelector('.em-calculator-period-range');
	var interest = document.querySelector('.em-calculator-interest-range');
	var resultat = document.querySelector('.em-calculator-result');

	var postfix = document.querySelector('.em-calculator-postfix');
	if (postfix) postfix = ' '+postfix.value;

	var postfixes = document.querySelector('.em-calculator-postfixes');
	if (postfixes) postfixes = ' '+postfixes.value;

	var default_amount = document.querySelector('.em-calculator-amount-default');
	if (default_amount) default_amount = default_amount.value;

	var default_interest = document.querySelector('.em-calculator-interest-default');
	if (default_interest) default_interest = parseFloat(default_interest.value);

	var http_lang = document.querySelector('.em-calculator-lang');
	if (http_lang) http_lang = http_lang.value;

	var up_button = document.querySelector('.em-calc-button-right');
	var down_button = document.querySelector('.em-calc-button-left');

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
	interest_text.value = parseFloat(default_interest/100).toLocaleString(locale, { style: 'percent', minimumFractionDigits: 2, maximumFractionDigits: 2});

	if (!belop || !ar || !interest || !resultat) return;

	var b = belop.value || 250.000;
	var a = ar.value || 5;
	var r = interest.value || 15;

	function monthlyPayment(p, n, i) {
	 	return Math.floor(p / ((1-Math.pow(1+i, -n))/i));
	}

	function writeMP() {
		b = belop.value;
		a = ar.value;
		// r = interest.value;
		r = default_interest;

		resultat.value = monthlyPayment(b, a*12, r/100/12).toLocaleString(locale, currency);
	}

	writeMP();

	belop.addEventListener('input', function(e) {
		belop_text.value = parseInt(e.target.value).toLocaleString(locale, currency);
		writeMP();
	});

	ar.addEventListener('input', function(e) {
		var a = e.target.value;

		if (a != 1) a += postfixes;
		else a += postfix;

		ar_text.value = a;
		writeMP();
	});

	interest.addEventListener('input', function(e) {
		default_interest = parseFloat(e.target.value);
		interest_text.value = default_interest.toLocaleString(locale, { minimumFractionDigits: 2, maximumFractionDigits: 2})+'%';
		writeMP();
	});

	interest_text.addEventListener('input', function(e) {
		default_interest = parseFloat(e.target.value);
		interest.value = e.target.value;
		writeMP();
	});

	function countup() {
		default_interest = default_interest + 0.01;
		interest_text.value = parseFloat(default_interest/100).toLocaleString(locale, { style: 'percent', minimumFractionDigits: 2, maximumFractionDigits: 2});
		interest.value = default_interest;
	} 

	function countdown() {
		default_interest = default_interest - 0.01;
		interest_text.value = parseFloat(default_interest/100).toLocaleString(locale, { style: 'percent', minimumFractionDigits: 2, maximumFractionDigits: 2});
		interest.value = default_interest;
	}

	if (up_button) {
		up_button.addEventListener('mousedown', function() {

			var timer = setInterval(countup, 100);

			var mousereleased = function() {
				clearInterval(timer);
				document.removeEventListener('mouseup', mousereleased);
			}

			document.addEventListener('mouseup', mousereleased);
		});

		up_button.addEventListener('click', function() { countup(); });
	}

	if (down_button) {
		down_button.addEventListener('mousedown', function() {

			var timer = setInterval(countdown, 100);

			var mousereleased = function() {
				clearInterval(timer);
				document.removeEventListener('mouseup', mousereleased);
			}

			document.addEventListener('mouseup', mousereleased);
		});

		down_button.addEventListener('click', function() { countdown(); });
	}



})();