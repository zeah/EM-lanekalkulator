(function() {

	

	var belop_text = document.querySelector('#em-calculator-amount');
	var ar_text = document.querySelector('#em-calculator-year');
	var rente_text = document.querySelector('#em-calculator-interest');

	var belop = document.querySelector('.em-calculator-amount-range');
	var ar = document.querySelector('.em-calculator-year-range');
	var rente = document.querySelector('.em-calculator-interest-range');
	var resultat = document.querySelector('.em-calculator-result');

	var thousand_sep = document.querySelector('.em-calculator-thousands-sep');
	if (thousand_sep) thousand_sep = thousand_sep.value;

	if (!belop || !ar || !rente || !resultat) return;

	var b = belop.value || 250.000;
	var a = ar.value || 5;
	var r = rente.value || 15;

	function monthlyPayment(p, n, i) {
	 	return numberWithCommas(Math.floor(p / ((1-Math.pow(1+i, -n))/i)));
	}

	function writeMP() {
		b = belop.value;
		a = ar.value;
		r = rente.value;

		resultat.value = monthlyPayment(b, a*12, r/100/12);
	}

	function numberWithCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousand_sep);
	}

	writeMP();

	belop.addEventListener('input', function(e) {
		// belop_text.innerHTML = parseInt(e.target.value).toLocaleString();
		// belop_text.innerHTML = parseInt(e.target.value).toLocaleString();
		belop_text.innerHTML = numberWithCommas(e.target.value);
		// belop_text.value = numberWithCommas(e.target.value);
		writeMP();
	});

	ar.addEventListener('input', function(e) {
		// ar_text.innerHTML = e.target.value;
		ar_text.value = e.target.value;
		writeMP();
	});

	rente.addEventListener('input', function(e) {
		// rente_text.innerHTML = e.target.value;
		rente_text.value = parseFloat(e.target.value).toFixed(2);
		writeMP();
	});


	rente_text.addEventListener('input', function(e) {
		rente.value = e.target.value;
		writeMP();
	});

})();