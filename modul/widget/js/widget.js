function showHide(show,available_lang,judul, nama_lang, default_lang, 
	home_modul, home_jenis, isconfigurable) 
{	document.getElementById('headerlang').innerHTML = nama_lang+': '+judul;

	var daftar_lang = eval(available_lang);
	for(i=0; i <daftar_lang.length;i++)
	{	if(daftar_lang[i]!=default_lang)
		{	if(daftar_lang[i]==show)
			{	document.getElementById('juduledit_'+daftar_lang[i]).style.visibility = 'visible';
				document.getElementById('juduledit_'+daftar_lang[i]).style.display = 'table-row';
				if(home_jenis=="custom")
				{	document.getElementById('isiedit_'+daftar_lang[i]).style.visibility = 'visible';
					document.getElementById('isiedit_'+daftar_lang[i]).style.display = 'table-row';
				}
			}
			else
			{	document.getElementById('juduledit_'+daftar_lang[i]).style.visibility = 'hidden';
				document.getElementById('juduledit_'+daftar_lang[i]).style.display = 'none';	
				if(home_jenis=="custom")
				{	document.getElementById('isiedit_'+daftar_lang[i]).style.visibility = 'hidden';
					document.getElementById('isiedit_'+daftar_lang[i]).style.display = 'none';	
				}
			}
		}
	}
}

function copas(title, isi, available_lang, default_lang, nama_available_lang, 
	home_modul, home_jenis, isconfigurable) 
{	nilai_header_lang = document.getElementById('headerlang').innerHTML;
	
	title = unescape(title.replace(/\+/g, " "));
	isi = unescape(isi.replace(/\+/g, " "));
	
	var aPosition = nilai_header_lang.indexOf(":");
	var nama_active_lang = "";
	if(aPosition > 0)
	{	nama_active_lang = nilai_header_lang.substr(0, (aPosition));
	}
		
	var daftar_lang = eval(available_lang);
	var daftar_nama_lang = eval(nama_available_lang);
	for(i=0; i <daftar_lang.length;i++)
	{	if(daftar_lang[i]!=default_lang)
		{	if(daftar_nama_lang[i]==nama_active_lang)	
			{	document.getElementById('tdjuduledit_'+daftar_lang[i]).value = title;
				if(home_jenis=="custom")
				{	tinyMCE.get('tdisiedit_'+daftar_lang[i]).setContent(isi);
				}
			}
		}
	}
	
}

