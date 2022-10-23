// Delete User 
	$('#deleteuser a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this user ?",
			//content : "Click yes to delete",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deleteuser, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete User ACTION
	 */

	function deleteuser() {
		window.location = $.delURL;
	}

// Delete All Apps 
	$('#deleteallapps a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this app ?",
			//content : "Click yes to delete",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deleteallapps, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete All apps ACTION
	 */

	function deleteallapps() {
		window.location = $.delURL;
	}

	// Delete Shared Apps 
	$('#deletesharedapps a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this app ?",
			content : "Note : This app will also be removed from community apps.",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deletesharedapps, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete Shared apps ACTION
	 */

	function deletesharedapps() {
		window.location = $.delURL;
	}

	// Delete My Apps 
	$('#deletemyapps a').click(function(e) {
		//get the link
		var $this = $(this);
		$.delURL = $this.attr('href');

		// ask verification
		$.SmartMessageBox({
			title : "<i class='fa fa-minus-square txt-color-orangeDark'></i> Do you want to delete this app ?",
			buttons : '[No][Yes]'

		}, function(ButtonPressed) {
			if (ButtonPressed == "Yes") {
				setTimeout(deletemyapps, 1000)
			}

		});
		e.preventDefault();
	});

	/*
	 * Delete My apps ACTION
	 */

	function deletemyapps() {
		window.location = $.delURL;
	}
	
	
	
	

