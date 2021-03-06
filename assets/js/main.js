$(document).ready(function(){
	$(function () {
		$.scrollUp({
	        scrollName: 'scrollUp', // Element ID
	        scrollDistance: 300, // Distance from top/bottom before showing element (px)
	        scrollFrom: 'top', // 'top' or 'bottom'
	        scrollSpeed: 300, // Speed back to top (ms)
	        easingType: 'linear', // Scroll to top easing (see http://easings.net/)
	        animation: 'fade', // Fade, slide, none
	        animationSpeed: 200, // Animation in speed (ms)
	        scrollTrigger: false, // Set a custom triggering element. Can be an HTML string or jQuery object
					//scrollTarget: false, // Set a custom target element for scrolling to the top
	        scrollText: '<i class="fa fa-angle-up"></i>', // Text for element, can contain HTML
	        scrollTitle: false, // Set a custom <a> title if required.
	        scrollImg: false, // Set true to use image
	        activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
	        zIndex: 2147483647 // Z-Index for the overlay
		});
	});

	// Form Validation
	function randomNumber(min, max) {
		return Math.floor(Math.random() * (max - min + 1) + min);
	};
	$('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));

	$('#validationForm').formValidation({
		message: 'This value is not valid',
		icon: {
			valid: 'glyphicon glyphicon-ok',
			//invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
			firstName: {
				row: '.col-sm-4',
				validators: {
					notEmpty: {
						message: 'The first name is required'
					}
				}
			},
			lastName: {
				row: '.col-sm-4',
				validators: {
					notEmpty: {
						message: 'The last name is required'
					}
				}
			},
			username: {
				message: 'The username is not valid',
				validators: {
					// The validator will create an Ajax request
					// sending { username: 'its value' } to the back-end
					remote: {
						message: 'The username is not available',
						url: '/path/to/backend/',
						type: 'POST'
					}
				},
				validators: {
					notEmpty: {
						message: 'The username is required'
					},
					stringLength: {
						min: 4,
						max: 20,
						message: 'The username must be more than 4 and less than 20 characters long'
					},
					regexp: {
						regexp: /^[a-zA-Z0-9_\.]+$/,
						message: 'The username can only consist of alphabetical, number, dot and underscore'
					}
				}
			},
			email: {
				validators: {
					notEmpty: {
						message: 'The email address is required'
					},
					emailAddress: {
						message: 'The input is not a valid email address'
					}
				}
			},
			password: {
				validators: {
					identical: {
						field: 'cpassword',
						message: 'The password and its confirm are not the same'
					},
					stringLength: {
						min: 6,
						max: 20,
						message: 'The password must be more than 6 and less than 20 characters long'
					},

					notEmpty: {
						message: 'The password is required'
					},
					different: {
						field: 'username',
						message: 'The password cannot be the same as username'
					}
				}
			},
			cpassword: {
				validators: {
					identical: {
						field: 'password',
						message: 'The password and its confirm are not the same'
					},
					notEmpty: {
						message: 'The confirm password is required'
					}
				}
			},
			newPassword: {
				validators: {
					identical: {
						field: 'conPassword',
						message: 'The new password and its confirm password are not the same'
					},
					stringLength: {
						min: 6,
						max: 20,
						message: 'The new password must be more than 6 and less than 20 characters long'
					},

					notEmpty: {
						message: 'The new password is required'
					}
				}
			},
			conPassword: {
				validators: {
					identical: {
						field: 'newPasswordd',
						message: 'The new password and its confirm password are not the same'
					},
					notEmpty: {
						message: 'The confirm password is required'
					}
				}
			},
			curPassword: {
				validators: {
					notEmpty: {
						message: 'The current password is required'
					}
				}
			},
			gender: {
				validators: {
					notEmpty: {
						message: 'The gender is required'
					}
				}
			},
			captcha: {
				validators: {
					callback: {
						message: 'Wrong answer',
						callback: function(value, validator, $field) {
							var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
							return value == sum;
						}
					}
				}
			},
			agree: {
				validators: {
					notEmpty: {
						message: 'You must agree with the terms and conditions'
					}
				}
			}
		}
	});

	// upload file
	$(function () {
		function readURL(input) {
			if (input.files && input.files[0]) {
				var reader = new FileReader();

				reader.onload = function (e) {
					$('#cover, #image-preview').css('background', 'url('+e.target.result+') no-repeat');
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#image-upload").change(function(){
			readURL(this);
		});;
	});

	// on change
	$(document).on('keyup','#username',function() {
		$('.pageName span').text($(this).val());
	});

});

