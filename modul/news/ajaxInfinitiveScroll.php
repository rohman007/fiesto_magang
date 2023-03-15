<script type="text/javascript">
	var busy = false;
	var limit = <?php echo $maxlistnews; ?>;
	var offset = 0;

	function displayRecords(lim, off) {
		$.ajax({
			type: "GET",
			async: false,
			url: "<?php echo "$cfg_app_url/modul/news/ajaxGetRecords.php"?>",
			data: "limit=" + lim + "&offset=" + off,
			cache: false,
			beforeSend: function() {
				$("#loader_message").html("").hide();
				$('#loader_image').show();
			},
			success: function(html) {
				$("#results").append(html);
				$('#loader_image').hide();
				if (html == "") {
					$("#loader_message").html('<span >No more records.</span>').show()
				} else {
					$("#loader_message").html('<img src="<?php echo $cfg_app_url; ?>/images/loader.gif" alt="" width="24" height="24"><span>Loading please wait...</span>').show();
				}
				window.busy = false;
			}
		});
	}

	$(document).ready(function() {
		// start to load the first set of data
		if (busy == false) {
			busy = true;
			// start to load the first set of data
			displayRecords(limit, offset);
		}


		$(window).scroll(function() {
			// make sure u give the container id of the data to be loaded in.
			if ($(window).scrollTop() + $(window).height() > $("#results").height() && !busy) {
			busy = true;
			offset = limit + offset;

			// this is optional just to delay the loading of data
			setTimeout(function() { displayRecords(limit, offset); }, 1000);

			// you can remove the above code and can use directly this function
			// displayRecords(limit, offset);

			}
		});

	});

</script>