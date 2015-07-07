$(document).ready(function(){
		$('.switchpw').on('blur',function(){
			$(this).attr('type','password');
		});
		$('.switchpw').on('focus',function(){
			$(this).attr('type','text');
		});

		$('#login-form').on('submit',function(event){
			event.preventDefault();
			$.ajax({
				type: 'POST',
				url: 'validate.php',
				data: $(this).serialize(),
				success: function(data){
					console.log(data);
					
					if(data == "good"){
						$('#status').addClass('showing');
						setTimeout(function(){
							window.location = "../";
						}, 1000)
					}else{
						$('#invalid').addClass('showing');
					}
				}//end success
			});//end ajax
		})
	});//end doc ready