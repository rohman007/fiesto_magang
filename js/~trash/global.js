var bustcachevar=1 //bust potential caching of external pages after initial request? (1=yes, 0=no)
var loadedobjects=""
var rootdomain="http://"+window.location.hostname
var bustcacheparameter=""
oneMinute = 60 * 1000; 
oneHour = oneMinute * 60; 
oneDay = oneHour * 24; 

function ajaxpage(url, containerid)
{	var page_request = false
	//alert(url);
	if (window.XMLHttpRequest) // if Mozilla, Safari etc
	{	page_request = new XMLHttpRequest()	}
	else if (window.ActiveXObject)
	{ 	// if IE
		try 
		{	page_request = new ActiveXObject("Msxml2.XMLHTTP")	} 
		catch (e)
		{	try
			{	page_request = new ActiveXObject("Microsoft.XMLHTTP")	}
			catch (e)
			{	}
		}
	}
	else
	{	return false
	}
	
	page_request.onreadystatechange=function(){	loadpage(page_request, containerid, url)	}
	if (bustcachevar) //if bust caching of external page
	{	bustcacheparameter=(url.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()	
	}
	//document.getElementById(containerid).innerHTML= "";
	page_request.open('GET', url+bustcacheparameter, true)
	page_request.send(null)
}

function loadpage(page_request, containerid, url)
{	var isi=page_request.responseText
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1))
	{	var isi=page_request.responseText	
		document.getElementById(containerid).innerHTML= isi	
	}
}

function loadobjs()
{	if (!document.getElementById)
		return
	for (i=0; i<arguments.length; i++)
	{	var file=arguments[i]
		var fileref=""
		if (loadedobjects.indexOf(file)==-1)
		{	//Check to see if this object has not already been added to page before proceeding
			if (file.indexOf(".js")!=-1)
			{	//If object is a js file
				fileref=document.createElement('script')
				fileref.setAttribute("type","text/javascript");
				fileref.setAttribute("src", file);
			}
			else if (file.indexOf(".css")!=-1)
			{	//If object is a css file
				fileref=document.createElement("link")
				fileref.setAttribute("rel", "stylesheet");
				fileref.setAttribute("type", "text/css");
				fileref.setAttribute("href", file);
			}
		}
		if (fileref!="")
		{	document.getElementsByTagName("head").item(0).appendChild(fileref)
			loadedobjects+=file+" " //Remember this object as being already added to page
		}
	}
}

function submitSearchForm(url)
{	var keyword = document.getElementById('keyword').value;
	url = url + "/" + keyword;
	window.location = url;	
}

function valform(formname) {
	mailfield = '';
	switch (formname) {
		case 'loginform':
			reqfieldvar  = ['username','password'];
			reqfieldname = ['Username','Password'];
			break;
		case 'register':
			reqfieldvar  = ['txtUser','txtPass1','txtPass2','txtNama','txtAlamat1','txtKota','txtTelepon','txtEmail'];
			reqfieldname = ['Username','Password','Ketik Ulang Password','Nama','Alamat','Kota','Telepon','Email'];
			mailfield = 'txtEmail';
			break;
		case 'editaccount':
			reqfieldvar  = ['txtNama','txtAlamat1','txtKota','txtTelepon','txtEmail'];
			reqfieldname = ['Nama','Alamat','Kota','Telepon','Email'];
			mailfield = 'txtEmail';
			break;
		case 'editpass':
			reqfieldvar  = ['old_pass','new_pass1','new_pass2'];
			reqfieldname = ['Password lama','Password baru #1','Password baru #2'];
			break;
		case 'hasdomain':
			reqfieldvar  = ['olddname'];
			reqfieldname = ['Nama domain'];
			break;
		case 'regdomain':
		case 'recheckdomain':
			reqfieldvar  = ['newdname'];
			reqfieldname = ['Nama domain'];
			break;
		case 'searchform':
			reqfieldvar  = ['keyword'];
			reqfieldname = ['Kata kunci pencarian'];
			break;
		case 'forgotpass':
			reqfieldvar  = ['txtEmail'];
			reqfieldname = ['Email'];
			mailfield = 'txtEmail';
			break;
		case 'fieldopt':
			reqfieldvar  = ['umbalemail'];
			reqfieldname = ['Email penerima'];
			mailfield = 'umbalemail';
			break;
	}
	for (i=0;i<reqfieldvar.length;i++) {
		if (trim(eval('document.getElementById(formname).'+reqfieldvar[i]+'.value')) == '') {
			window.alert(reqfieldname[i]+" harus diisi.");
			eval('document.getElementById(formname).'+reqfieldvar[i]+'.focus()');
			return false;
		}
	}
	if (mailfield != '') {
		if (eval('document.getElementById(formname).'+mailfield+'.value.match((/(@.*@)|(\\.\\.)|(@\\.)|(\\.@)|(^\\.)/)) || !(document.getElementById(formname).'+mailfield+'.value.match(/^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,4}|[0-9]{1,4})(\\]?)$/))')) {
			window.alert("Format alamat email anda salah.");
			eval('document.getElementById(formname).'+mailfield+'.focus()');
			return false;
		}
	}
}

function showtab(tabnum,isclose) {
	if (isclose==null) isclose=false; 
	if (isclose) closeforms();
	for(i=0;i<2;i++) {
		if (i==tabnum) {
			eval("document.getElementById('tab"+i+"').style.display='block'");
		} else {
			eval("document.getElementById('tab"+i+"').style.display='none'");
		}
	}
}


function setCheckedValue(radioObj, newValue) {
	if(!radioObj)
		return;
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		radioObj.checked = (radioObj.value == newValue.toString());
		return;
	}
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == newValue.toString()) {
			radioObj[i].checked = true;
		}
	}
}
