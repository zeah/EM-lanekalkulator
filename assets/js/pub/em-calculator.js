(function() {

	// finding calcs
	var calc = document.querySelectorAll('.em-calculator');

	for (var i = 0; i < calc.length; i++) {
		// scoping
		(function() { 

			var c = calc[i];

			// data & functions
			var o = {
				// text input elements
				'amountText': c.querySelector('#em-calculator-amount'),
				'periodText': c.querySelector('#em-calculator-period'),
				'interestText': c.querySelector('#em-calculator-interest'),
				'result': c.querySelector('.em-calculator-result'),

				// range input elements
				'amount': c.querySelector('.em-calculator-amount-range'),
				'period': c.querySelector('.em-calculator-period-range'),
				'interest': c.querySelector('.em-calculator-interest-range'),

				// interest buttons
				'buttonUp': c.querySelector('.em-calc-button-right'),
				'buttonDown': c.querySelector('.em-calc-button-left'),

				// styling for js locales
				'percent': {
					style: 'percent', 
					minimumFractionDigits: 2, 
					maximumFractionDigits: 2
				},
			
				// period postfix
				'postfix': c.querySelector('.em-calc-period-postfix') ? ' '+c.querySelector('.em-calc-period-postfix').value : ' year',
				'postfixes': c.querySelector('.em-calc-period-postfixes') ? ' '+c.querySelector('.em-calc-period-postfixes').value : ' years',
				
				// for js locale
				'amountDefault': c.querySelector('.em-calc-amount-default') ? parseFloat(c.querySelector('.em-calc-amount-default').value) : 250000,
				
				// data for counting and initial interest rate
				'interestDefault': c.querySelector('.em-calc-interest-default') ? parseFloat(c.querySelector('.em-calc-interest-default').value) : 12,
				
				'locale': c.querySelector('.em-calc-locale') ? c.querySelector('.em-calc-locale').value : 'en-US|USD',

				payment: function(p, n, i) { return Math.floor(p / ((1 - Math.pow(1 + i, -n)) / i)) },
				calc: function() { o.result.value = o.payment(o.amount.value, o.period.value*12, o.interest.value/100/12).toLocaleString(o.language, o.currency) }, 
			
				setInterest: function() {
					// setting text input
					o.interestText.value = (o.interestDefault / 100).toLocaleString(o.language, o.percent);

					// setting range input
					o.interest.value = o.interestDefault;

					// recalculating result
					o.calc();					
				},

				countUp: function() {
					o.interestDefault += 0.01;
					o.setInterest();
				},

				countDown: function() {
					o.interestDefault -= 0.01;
					o.setInterest();
				},

				button: function(e, callback) {
					if (e) {
						e.addEventListener('mousedown', function() {

							var timer = setInterval(callback, 100);

							var mousereleased = function() {
								clearInterval(timer);
								document.removeEventListener('mouseup', mousereleased);
							}

							document.addEventListener('mouseup', mousereleased);
						});

						e.addEventListener('click', function() { callback(); });
					}
				},

			}

			// processing locales
			o.language = o.locale.split('|')[0] || 'en-US';
			// o.currencySymbol = o.locale.split('|')[1];

			// styling for js locales
			o.currency = {
				style: 'currency',
				currency: o.locale.split('|')[1] || 'USD',
				minimumFractionDigits: 0, 
				maximumFractionDigits: 0
			};
			// end of data 


			// actions
			// init amount & interest values (using js locales)
			o.amountText.value = parseInt(o.amountDefault).toLocaleString(o.language, o.currency);
			o.interestText.value = parseFloat(o.interestDefault/100).toLocaleString(o.language, o.percent);

			// initial update
			o.calc();

			// end of actions


			// events
			// amount range
			var amountEvent = function(e) {
				o.amountText.value = parseInt(e.target.value).toLocaleString(o.language, o.currency);
				o.calc();
			}

			o.amount.addEventListener('input', function(e) { amountEvent(e) });

			// for IE
			o.amount.addEventListener('change', function(e) { amountEvent(e) });

			// period range
			var periodEvent = function(e) {
				var a = e.target.value;

				if (a != 1) a += o.postfixes;
				else a += o.postfix;

				o.periodText.value = a;
				o.calc();
			}

			o.period.addEventListener('input', function(e) { periodEvent(e) });
			
			// for IE
			o.period.addEventListener('change', function(e) { periodEvent(e) });

			// interest range

			var interestEvent = function(e) {
				o.interestDefault = parseFloat(e.target.value);
				o.interestText.value = (o.interestDefault/100).toLocaleString(o.language, o.percent);
				o.calc();
			}

			o.interest.addEventListener('input', function(e) { interestEvent(e) });

			// for IE
			o.interest.addEventListener('change', function(e) { interestEvent(e) });

			// interest text
			o.interestText.addEventListener('input', function(e) {
				o.interestDefault = parseFloat(e.target.value);
				o.interest.value = e.target.value;
				o.calc();
			});

			// button events
			o.button(o.buttonUp, o.countUp);
			o.button(o.buttonDown, o.countDown);


		})(); 	// end of scoping
	} 			// end of loop

})();