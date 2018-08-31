(function() {

	var belop_text = document.querySelector('#em-kalkulator-belop');
	var ar_text = document.querySelector('#em-kalkulator-ar');
	var rente_text = document.querySelector('#em-kalkulator-rente');

	var belop = document.querySelector('.em-kalkulator-belop-range');
	var ar = document.querySelector('.em-kalkulator-ar-range');
	var rente = document.querySelector('.em-kalkulator-rente-range');
	var resultat = document.querySelector('.em-kalkulator-resultat');

	if (!belop || !ar || !rente || !resultat) return;

	var b = belop.value || 250000;
	var a = ar.value || 5;
	var r = rente.value || 15;

	function monthlyPayment(p, n, i) {
	 	return Math.floor(p / ((1-Math.pow(1+i, -n))/i))
	}

	function writeMP() {
		b = belop.value;
		a = ar.value;
		r = rente.value;

		resultat.value = monthlyPayment(b, a*12, r/100/12)+' kr';
	}


	writeMP();

	belop.addEventListener('input', function(e) {
		belop_text.value = e.target.value;
		writeMP();
	});

	ar.addEventListener('input', function(e) {
		ar_text.value = e.target.value;
		writeMP();
	});

	rente.addEventListener('input', function(e) {
		rente_text.value = e.target.value;
		writeMP();
	});


	rente_text.addEventListener('input', function(e) {
		rente.value = e.target.value;
		writeMP();
	});

})();