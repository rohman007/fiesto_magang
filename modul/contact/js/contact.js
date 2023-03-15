function checkall(pr,ischecked) {
	if (pr=='p') {
		for(i=0;i<14;i++) {
			if (ischecked) {
				eval("document.fieldopt.pfield"+i+".checked=true");
			} else {
				if (i!=11 && i!=14) eval("document.fieldopt.pfield"+i+".checked=false");
				document.fieldopt.rcheckall.checked=false;
				if (i!=11 && i!=14) eval("document.fieldopt.rfield"+i+".checked=false");
			}
		}
	}
	if (pr=='r') {
		for(i=0;i<14;i++) {
			if (ischecked) {
				eval("document.fieldopt.rfield"+i+".checked=true");
				document.fieldopt.pcheckall.checked=true;
				eval("document.fieldopt.pfield"+i+".checked=true");
			} else {
				if (i!=11 && i!=14) eval("document.fieldopt.rfield"+i+".checked=false");
			}
		}
	}
}