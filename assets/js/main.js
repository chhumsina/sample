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
			invalid: 'glyphicon glyphicon-remove',
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
					notEmpty: {
						message: 'The username is required'
					},
					stringLength: {
						min: 6,
						max: 30,
						message: 'The username must be more than 6 and less than 30 characters long'
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
					notEmpty: {
						message: 'The password is required'
					},
					different: {
						field: 'username',
						message: 'The password cannot be the same as username'
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
	$.uploadPreview({
		input_field: "#image-upload",
		preview_box: "#image-preview, .jumbotron",
		label_field: "#image-label"
	});

	// RTE
	$('#editor1').each( function(index, element)
	{
		$(element).wysiwyg({
			classes: 'some-more-classes',
			// 'selection'|'top'|'top-selection'|'bottom'|'bottom-selection'
			toolbar: index == 0 ? 'top-selection' : (index == 1 ? 'bottom' : 'selection'),
			buttons: {
				// Dummy-HTML-Plugin
				dummybutton1: index != 1 ? false : {
					html: $('<input id="submit" type="button" value="bold" />').click(function(){
						// We simply make 'bold'
						if( $(element).wysiwyg('shell').getSelectedHTML() )
							$(element).wysiwyg('shell').bold();
						else
							alert( 'Please selection some text' );
					}),
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				// Dummy-Button-Plugin
				dummybutton2: index != 1 ? false : {
					title: 'Dummy',
					image: '\uf1e7',
					click: function( $button ) {
						alert('Do something');
					},
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				// Smiley plugin
				insertimage: {
					title: 'Insert image',
					image: '\uf030', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: index == 2 ? true : false    // wanted on selection
				},
				insertvideo: {
					title: 'Insert video',
					image: '\uf03d', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: index == 2 ? true : false    // wanted on selection
				},
				insertlink: {
					title: 'Insert link',
					image: '\uf08e' // <img src="path/to/image.png" width="16" height="16" alt="" />
				},
				// Fontname plugin
				fontname: index == 1 ? false : {
					title: 'Font',
					image: '\uf031', // <img src="path/to/image.png" width="16" height="16" alt="" />
					popup: function( $popup, $button ) {
						var list_fontnames = {
							// Name : Font
							'Arial, Helvetica' : 'Arial,Helvetica',
							'Verdana'          : 'Verdana,Geneva',
							'Georgia'          : 'Georgia',
							'Courier New'      : 'Courier New,Courier',
							'Times New Roman'  : 'Times New Roman,Times'
						};
						var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
							.attr('unselectable','on');
						$.each( list_fontnames, function( name, font ){
							var $link = $('<a/>').attr('href','#')
								.css( 'font-family', font )
								.html( name )
								.click(function(event){
									$(element).wysiwyg('shell').fontName(font).closePopup();
									// prevent link-href-#
									event.stopPropagation();
									event.preventDefault();
									return false;
								});
							$list.append( $link );
						});
						$popup.append( $list );
					},
					//showstatic: true,    // wanted on the toolbar
					showselection: index == 0 ? true : false    // wanted on selection
				},
				// Fontsize plugin
				fontsize: index != 1 ? false : {
					title: 'Size',
					image: '\uf034', // <img src="path/to/image.png" width="16" height="16" alt="" />
					popup: function( $popup, $button ) {
						// Hack: http://stackoverflow.com/questions/5868295/document-execcommand-fontsize-in-pixels/5870603#5870603
						var list_fontsizes = [];
						for( var i=8; i <= 11; ++i )
							list_fontsizes.push(i+'px');
						for( var i=12; i <= 28; i+=2 )
							list_fontsizes.push(i+'px');
						list_fontsizes.push('36px');
						list_fontsizes.push('48px');
						list_fontsizes.push('72px');
						var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
							.attr('unselectable','on');
						$.each( list_fontsizes, function( index, size ){
							var $link = $('<a/>').attr('href','#')
								.html( size )
								.click(function(event){
									$(element).wysiwyg('shell').fontSize(7).closePopup();
									$(element).wysiwyg('container')
										.find('font[size=7]')
										.removeAttr("size")
										.css("font-size", size);
									// prevent link-href-#
									event.stopPropagation();
									event.preventDefault();
									return false;
								});
							$list.append( $link );
						});
						$popup.append( $list );
						/*
						 var list_fontsizes = {
						 // Name : Size
						 'Huge'    : 7,
						 'Larger'  : 6,
						 'Large'   : 5,
						 'Normal'  : 4,
						 'Small'   : 3,
						 'Smaller' : 2,
						 'Tiny'    : 1
						 };
						 var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
						 .attr('unselectable','on');
						 $.each( list_fontsizes, function( name, size ){
						 var $link = $('<a/>').attr('href','#')
						 .css( 'font-size', (8 + (size * 3)) + 'px' )
						 .html( name )
						 .click(function(event){
						 $(element).wysiwyg('shell').fontSize(size).closePopup();
						 // prevent link-href-#
						 event.stopPropagation();
						 event.preventDefault();
						 return false;
						 });
						 $list.append( $link );
						 });
						 $popup.append( $list );
						 */
					}
					//showstatic: true,    // wanted on the toolbar
					//showselection: true    // wanted on selection
				},
				// Header plugin
				header: index != 1 ? false : {
					title: 'Header',
					image: '\uf1dc', // <img src="path/to/image.png" width="16" height="16" alt="" />
					popup: function( $popup, $button ) {
						var list_headers = {
							// Name : Font
							'Header 1' : '<h1>',
							'Header 2' : '<h2>',
							'Header 3' : '<h3>',
							'Header 4' : '<h4>',
							'Header 5' : '<h5>',
							'Header 6' : '<h6>',
							'Code'     : '<pre>'
						};
						var $list = $('<div/>').addClass('wysiwyg-toolbar-list')
							.attr('unselectable','on');
						$.each( list_headers, function( name, format ){
							var $link = $('<a/>').attr('href','#')
								.css( 'font-family', format )
								.html( name )
								.click(function(event){
									$(element).wysiwyg('shell').format(format).closePopup();
									// prevent link-href-#
									event.stopPropagation();
									event.preventDefault();
									return false;
								});
							$list.append( $link );
						});
						$popup.append( $list );
					}
					//showstatic: true,    // wanted on the toolbar
					//showselection: false    // wanted on selection
				},
				bold: {
					title: 'Bold (Ctrl+B)',
					image: '\uf032', // <img src="path/to/image.png" width="16" height="16" alt="" />
					hotkey: 'b'
				},
				italic: {
					title: 'Italic (Ctrl+I)',
					image: '\uf033', // <img src="path/to/image.png" width="16" height="16" alt="" />
					hotkey: 'i'
				},
				underline: {
					title: 'Underline (Ctrl+U)',
					image: '\uf0cd', // <img src="path/to/image.png" width="16" height="16" alt="" />
					hotkey: 'u'
				},
				strikethrough: {
					title: 'Strikethrough (Ctrl+S)',
					image: '\uf0cc', // <img src="path/to/image.png" width="16" height="16" alt="" />
					hotkey: 's'
				},
				forecolor: {
					title: 'Text color',
					image: '\uf1fc' // <img src="path/to/image.png" width="16" height="16" alt="" />
				},
				highlight: {
					title: 'Background color',
					image: '\uf043' // <img src="path/to/image.png" width="16" height="16" alt="" />
				},
				alignleft: index != 0 ? false : {
					title: 'Left',
					image: '\uf036', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				aligncenter: index != 0 ? false : {
					title: 'Center',
					image: '\uf037', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				alignright: index != 0 ? false : {
					title: 'Right',
					image: '\uf038', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				alignjustify: index != 0 ? false : {
					title: 'Justify',
					image: '\uf039', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				subscript: index == 1 ? false : {
					title: 'Subscript',
					image: '\uf12c', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: true    // wanted on selection
				},
				superscript: index == 1 ? false : {
					title: 'Superscript',
					image: '\uf12b', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: true    // wanted on selection
				},
				indent: index != 0 ? false : {
					title: 'Indent',
					image: '\uf03c', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				outdent: index != 0 ? false : {
					title: 'Outdent',
					image: '\uf03b', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				orderedList: index != 0 ? false : {
					title: 'Ordered list',
					image: '\uf0cb', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				unorderedList: index != 0 ? false : {
					title: 'Unordered list',
					image: '\uf0ca', // <img src="path/to/image.png" width="16" height="16" alt="" />
					//showstatic: true,    // wanted on the toolbar
					showselection: false    // wanted on selection
				},
				removeformat: {
					title: 'Remove format',
					image: '\uf12d' // <img src="path/to/image.png" width="16" height="16" alt="" />
				}
			},
			// Submit-Button
			submit: {
				title: 'Submit',
				image: '\uf00c' // <img src="path/to/image.png" width="16" height="16" alt="" />
			},
			// Other properties
			selectImage: 'Click or drop image',
			placeholderUrl: 'www.example.com',
			placeholderEmbed: '<embed/>',
			maxImageSize: [600,200],
			onImageUpload: function( insert_image ) {
				// A bit tricky, because we can't easily upload a file via
				// '$.ajax()' on a legacy browser without XMLHttpRequest2.
				// You have to submit the form into an '<iframe/>' element.
				// Call 'insert_image(url)' as soon as the file is online
				// and the URL is available.
				// Example server script (written in PHP):
				/*
				 <?php
				 $upload = $_FILES['upload-filename'];
				 // Crucial: Forbid code files
				 $file_extension = pathinfo( $upload['name'], PATHINFO_EXTENSION );
				 if( $file_extension != 'jpeg' && $file_extension != 'jpg' && $file_extension != 'png' && $file_extension != 'gif' )
				 die("Wrong file extension.");
				 $filename = 'image-'.md5(microtime(true)).'.'.$file_extension;
				 $filepath = '/path/to/'.$filename;
				 $serverpath = 'http://domain.com/path/to/'.$filename;
				 move_uploaded_file( $upload['tmp_name'], $filepath );
				 echo $serverpath;
				 */
				// Example client script (without upload-progressbar):
				var iframe_name = 'legacy-uploader-' + Math.random().toString(36).substring(2);
				$('<iframe>').attr('name',iframe_name)
					.load(function() {
						// <iframe> is ready - we will find the URL in the iframe-body
						var iframe = this;
						var iframedoc = iframe.contentDocument ? iframe.contentDocument :
							(iframe.contentWindow ? iframe.contentWindow.document : iframe.document);
						var iframebody = iframedoc.getElementsByTagName('body')[0];
						var image_url = iframebody.innerHTML;
						insert_image( image_url );
						$(iframe).remove();
					})
					.appendTo(document.body);
				var $input = $(this);
				$input.attr('name','upload-filename')
					.parents('form')
					.attr('action','/script.php') // accessing cross domain <iframes> could be difficult
					.attr('method','POST')
					.attr('enctype','multipart/form-data')
					.attr('target',iframe_name)
					.submit();
			},
			forceImageUpload: false,    // upload images even if File-API is present
			videoFromUrl: function( url ) {
				// Contributions are welcome :-)

				// youtube - http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
				var youtube_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?(?:youtu\.be|youtube\.com)\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/)([^\?&\"'>]+)/ );
				if( youtube_match && youtube_match[1].length == 11 )
					return '<iframe src="//www.youtube.com/embed/' + youtube_match[1] + '" width="640" height="360" frameborder="0" allowfullscreen></iframe>';

				// vimeo - http://embedresponsively.com/
				var vimeo_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?vimeo\.com\/([0-9]+)$/ );
				if( vimeo_match )
					return '<iframe src="//player.vimeo.com/video/' + vimeo_match[1] + '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

				// dailymotion - http://embedresponsively.com/
				var dailymotion_match = url.match( /^(?:http(?:s)?:\/\/)?(?:[a-z0-9.]+\.)?dailymotion\.com\/video\/([0-9a-z]+)$/ );
				if( dailymotion_match )
					return '<iframe src="//www.dailymotion.com/embed/video/' + dailymotion_match[1] + '" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

				// undefined -> create '<video/>' tag
			},
			onKeyPress: function( code, character, shiftKey, altKey, ctrlKey, metaKey ) {
				// E.g.: submit form on enter-key:
				//if( (code == 10 || code == 13) && !shiftKey && !altKey && !ctrlKey && !metaKey ) {
				//    submit_form();
				//    return false; // swallow enter
				//}
			}
		})
			.change(function(){
				if( typeof console != 'undefined' )
					console.log( 'change' );
			})
			.focus(function(){
				if( typeof console != 'undefined' )
					console.log( 'focus' );
			})
			.blur(function(){
				if( typeof console != 'undefined' )
					console.log( 'blur' );
			});
	});
	// Raw editor
	var option = {
		element: $('#editor0').get(0),
		onkeypress: function( code, character, shiftKey, altKey, ctrlKey, metaKey ) {
			if( typeof console != 'undefined' )
				console.log( 'RAW: '+character+' key pressed' );
		},
		onselection: function( collapsed, rect, nodes, rightclick ) {
			if( typeof console != 'undefined' && rect )
				console.log( 'RAW: selection rect('+rect.left+','+rect.top+','+rect.width+','+rect.height+'), '+nodes.length+' nodes' );
		},
		onplaceholder: function( visible ) {
			if( typeof console != 'undefined' )
				console.log( 'RAW: placeholder ' + (visible ? 'visible' : 'hidden') );
		}
	};
	var wysiwygeditor = wysiwyg( option );

});

