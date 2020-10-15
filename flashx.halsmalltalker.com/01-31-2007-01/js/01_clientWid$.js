function ezClientWidth() {
	var _clientWid$ = -1;
	var ta = typeof window.innerWidth;
	if (ta.toUpperCase() == const_number_symbol.toUpperCase()) {
		_clientWid$ = window.innerWidth;
	} else if (document.documentElement && document.documentElement.clientWidth) {
		_clientWid$ = document.documentElement.clientWidth;
	} else if (document.body && document.body.clientWidth) {
		_clientWid$ = document.body.clientWidth;
	}
	return _clientWid$;
}

