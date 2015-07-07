$(document).ready(function(){
		$('.switchpw').on('blur',function(){
			$(this).attr('type','password');
		});
		$('.switchpw').on('focus',function(){
			$(this).attr('type','text');
		});

		$('#install-form').on('submit',function(event){
			event.preventDefault();
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$('#status').addClass('showing');
			$.ajax({
				type: 'POST',
				url: 'install.php',
				data : $(this).serialize(),
				success: function(data){
					$.ajax({
						type: 'POST',
						url: 'table.php',
						success: function(data){
							$('#status').html('');	
							$('#install-form').hide();
							$('#login-creds').show();
						}//end success
					});//end ajax
				}//end success
			});//end ajax
		});//end submit
		$('#login-creds').on('submit',function(event){
			event.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'userinfo.php',
				data: $(this).serialize(),
				success: function(data){
					$(this).hide();
					$('#status').html('Your install is complete. Please remove the /install directory.');	
					setTimeout(function(){
						window.location = '../';
					},5000)
				}//end success
			});//end ajax
		})
	});//end doc ready