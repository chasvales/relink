(function(){

	var app = angular.module('linker', [ ]);
	
	app.controller('LinkerController', function($scope,$http){
			$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'getlinks'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
				console.log(response);
				$scope.redirects = response;
			});
			this.checkbox = function(){
				var selector = $('input[type="checkbox"]');
				selector.change(function(event){
					event.stopImmediatePropagation();

					if($(this).attr('id') == "checkall"){
						console.log('check all');
						if($(this).is(':checked')){
							selector.prop('checked', true);
						}else{
							selector.prop('checked', false);
						}
					}

					var numChked = $('input[type="checkbox"]:checked').length;
					if(numChked >= 1){
						$('#trash').addClass('showing');
					}else{
						$('#trash').removeClass('showing');
					}

        		});
			}

			this.addLinks = function(){
			$http({
			    method: "post",
			    url: "_assets/action.php",
			    data: { param1: 'addlinks', param2: $scope.orglnk, param3: $scope.nwlnk },
			    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
			}).success(function(response) {
				console.log(response);
				$('#orginal_link, #new_link').val(' ');
				$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'getlinks'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
					$scope.redirects = response;
				});
				successPrompt('Redirect Link updated. <small>You must compile the list in order for this to take effect.</small>');
			});
		}//end addLinks
		this.compileList = function(){
			$('#loading-icon').addClass('show-load');
			$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'compilelinks'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
				console.log(response);
				if(response == "file not found"){
					warningPrompt();
				}else{
				successPrompt('Your file has been compiled. <a href="showfile.php" target="_blank">Click here to view the file.</a>');
				}
				$('#loading-icon').removeClass('show-load');
			});
		}// end complie list
		this.removeFromList = function(){
			console.log('function called');
			var allVals = [];
			 $('input[type=checkbox]:checked').each(function() {
			 	if($(this).val() === "all"){

			 	}else{
			 		 allVals.push($(this).val());
			 	}			  
			 });
			 var numDelete = allVals.length;
				var r = confirm("Are you sure you want to remove "+numDelete+" redirect links?");
				if(r == true){
					$('#trash').removeClass('showing');
					$http({
					    method: "post",
					    url: "_assets/action.php",
					    data: { param1: 'removelinks', param2: allVals},
					    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
					}).success(function(response) {
						allVals.forEach(function(entry){
							console.log('row id: row'+entry);
							$('input[type="checkbox"]').prop('checked', false);
							$('#row'+entry).slideUp(500);
						});// end for each
					});//end success
				}else{

				}
		}//end removeFromList function
	});//end controller

	app.controller('RelinkConfig', function($scope,$http){
			$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'getsettings'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
					console.log(response.settings);
					$scope.settings = response.settings;
				});
				this.updatesettings = function(){
					$('#loading-pulse').show();
				$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'updatesettings', param2: $scope.rootdomain, param3: $scope.htades, param4: $scope.startpoint},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
					$('#loading-pulse').hide();
					$('#loading-done').show();
					$('#con-domain, #con-dir, #con-st').val('');
					$http({
						    method: "post",
						    url: "_assets/action.php",
						    data: { param1: 'getsettings'},
						    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
						}).success(function(response) {
							$('#update-success').addClass('showing');
							setTimeout(function(){
								$('#loading-done').hide();
								$('#update-success').removeClass('showing');
							},1500);
							console.log(response.settings);
							$scope.settings = response.settings;
						});//end success
				});//end success	
			}//end update settings
			this.createhtac = function(){
				$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'movehtacess'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
					$('#warning-bar').removeClass('prompt');
					successPrompt('Your .htaccess file has been created.');
				});//end success

			}
			this.logout = function(){
				$http({
				    method: "post",
				    url: "_assets/action.php",
				    data: { param1: 'logout'},
				    headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				}).success(function(response) {
					window.location.reload();
				});//end success
			}
	});//end controller

	/* ======================================================
				NON ANGULAR DEPENDANT FUNCTIONS 
	=================================================== */
	var successPrompt = function(msg){
		$('#success-bar .innerText').html(msg);
		$('#success-bar').addClass('prompt');
	}
	var warningPrompt = function(msg){
		//$('#warning-bar .innerText').html(msg);
		$('#warning-bar').addClass('prompt');
	}
	$('.close-btn').on('click',function(event){ $('#success-bar, #warning-bar').removeClass('prompt'); });
	$('.form-control.linkswt').on('blur',function(){
		var obj = $(this);
		var linkvalue = obj.val();
		var firstChar = linkvalue.charAt(0);
		if(linkvalue.length >= 1 && firstChar != "/"){
			obj.val('/'+linkvalue);
		}
	});
	$('.navigation-slide').on('click',function(event){
		event.preventDefault();
		var obj = $(this);
		if(obj.hasClass('slid')){
			obj.removeClass('slid').next('.slide-content').slideUp(500);
		}else{
			obj.addClass('slid').next('.slide-content').slideDown(500);
		}
	})
})();//end closure
$(window).scroll(function(){
	var sTop = $(window).scrollTop();
	if(sTop >= 250){
		$('#under-add-wrapper').addClass('fixed');
	}else{
		$('#under-add-wrapper').removeClass('fixed');
	}
});
