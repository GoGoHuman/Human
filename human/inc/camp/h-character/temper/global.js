function roundHalf ( num ) {
	return Math.round ( num*100 )/100;
}

function replaceAll ( str, find, replace ) {
	if ( typeof str!=='undefined' ) {

		return str.replace ( new RegExp ( find, 'g' ), replace );
	} else {
		//  console.log(str + find + replace);
		return;
	}
}

var getUrlParameter = function getUrlParameter ( sParam ) {
	var sPageURL = decodeURIComponent ( window.location.search.substring ( 1 ) ),
		  sURLVariables = sPageURL.split ( '&' ),
		  sParameterName,
		  i;

	for ( i = 0;i<sURLVariables.length;i++ ) {
		sParameterName = sURLVariables[i].split ( '=' );

		if ( sParameterName[0]===sParam ) {
			return sParameterName[1]===undefined ? true : sParameterName[1];
		}
	}
};

function human_set_cookie ( cookie, time, val ) {
	var data = {
		action: "human_cookie_ajax",
		nonce: humanAjax.nonce,
		cookie: cookie,
		time: time,
		val: val
	};
	//  console.log(data);
	// return;
	jQuery.post ( humanAjax.ajaxurl, data, function ( response ) {
		//  console.log(response);
		if ( response.success ) {


		} else {

		}
	} );
}

/**
 * @param Automatics ajax function
 * @requires Data = {
 action: action,
 nonce: humanAjax.nonce,
 more: data
 }
 function friend_name_ajax_call_back(callback){
 //do what you want with your callback
 }
 */
function human_ajax ( data, callback, datatype ) {
	var ajaxurl = humanAjax.ajaxurl;
	//console.log(ajaxurl);
	jQuery.ajax ( {
		url: ajaxurl,
		data: data,
		type: 'POST',
		datatype: 'json',
		success: function ( response ) {
			//  location.reload();
			//  console.log(response);
			window[callback] ( response );
		}
	} );

}


jQuery ( document ).ready ( function ( $ ) {


	if ( $ ( 'body' ).hasClass ( 'logged-in' ) ) {
		// alert ( 'loggedin' );
		// alert ( $ ( '.my-account-link' ).length );
		$ ( '.my-account-link a' ).each ( function ( ) {
			if ( $ ( this ).attr ( 'rel' ).length>0 ) {
				$ ( this ).text ( $ ( this ).attr ( 'rel' ) );
			}
			// console.log ( $ ( this ) );
		} );
	}


	var custom_uploader;
	function sharing_urls ( type ) {
		var v = '';
		if ( type==='facebook' ) {
			v = '//www.facebook.com/sharer/sharer.php?u=';
		} else if ( type==='twitter' ) {
			v = '//twitter.com/intent/tweet?url=';
		} else if ( type==='google' ) {
			v = '//plus.google.com/share?url=';
		} else if ( type==='linkedin' ) {
			v = '//www.linkedin.com/shareArticle?mini=true&url=';
		}

		return v+humanAjax.thisUrl;
	}

	function popUp ( URL ) {

		day = new Date ( );
		id = day.getTime ( );
		var height = $ ( window ).height ( );
		var top = height-370;
		top = top/2;
		if ( height<370 ) {
			top = 0;
		}

//alert(URL);
		eval ( "page"+id+" = window.open('"+URL+"', '"+id+"', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1,width=580,height=370,left = 393,top ="+top+"');" );

	}

	$ ( '.h-social-login a' ).on ( 'click', function ( e ) {
		e.preventDefault ( );
		popUp ( $ ( this ).attr ( 'href' ) );
	} );
	$ ( '.social-share a' ).on ( 'click', function ( e ) {
		e.preventDefault ( );
		var type = $ ( this ).attr ( 'href' );
		popUp ( sharing_urls ( type ) );
	} );
} );
jQuery ( document ).ready ( function ( $ ) {
	$ ( '#loader-wrapper' ).hide ( );
	// and run it again every time you scroll
	$ ( window ).scroll ( function ( ) {
		if ( $ ( document ).scrollTop ( )>100 ) {
//$('.main-navigation').parent('div').addClass('shrink');
			$ ( '.human-page' ).addClass ( 'scrolled' );
		} else {

			$ ( '.scrolled' ).removeClass ( 'scrolled' );
		}
	} );


	$.fn.scrollTo = function ( target, options, callback ) {
		var isVisibleOnScreen = function ( elem ) {
			var docViewTop = $ ( window ).scrollTop ( );
			var docViewBottom = docViewTop+$ ( window ).height ( );
			var elemTop = $ ( elem ).offset ( ).top;
			var elemBottom = elemTop+$ ( elem ).height ( );
			return ( ( elemBottom<=docViewBottom )&&( elemTop>=docViewTop ) );
		};
		if ( window.innerWidth<768&&window.innerWidth>468 ) {
			var offset = 160;
		} else {
			var offset = 130;
		}
//alert(offset);
		if ( !isVisibleOnScreen ( this ) ) {
			$ ( 'html, body' ).animate ( {
				scrollTop: $ ( this ).offset ( ).top-offset+'px'
			}, 1500 );
			return this; // for chaining...
		}
	};
	function hide_mega ( ) {
		$ ( document ).on ( 'mouseover', 'div', function ( e ) {
			//   console.log($(e.target).attr('class'));
		} );
	}


	$ ( '.mega-menu-top-wrapper' ).css ( 'top', $ ( '.site-header' ).height ( ) );
	$ ( '.human-mega a,.mega-menu' ).on ( 'mouseenter', function ( ) {
		if ( $ ( window ).width ( )>768 ) {
			if ( $ ( this ).parent ( 'li' ).hasClass ( 'human-mega' ) ) {
				$ ( '.mega-menu' ).fadeOut ( );
				$ ( '#'+$ ( this ).attr ( 'rel' ) ).fadeIn ( );
			} else if ( $ ( this ).hasClass ( 'mega-menu' ) ) {
				$ ( this ).stop ( ).show ( );
			}
		}
	} ).on ( 'mouseleave', function ( ) {
		$ ( '.mega-menu' ).fadeOut ( );
	} );


	if ( $ ( 'body' ).hasClass ( 'home' )===false ) {

		$ ( '.menu li a' ).each ( function ( ) {
			var href = $ ( this ).attr ( 'href' );
			if ( href.indexOf ( '#' )>=0 ) {
				var nhref = href.split ( '#' );
				href = humanAjax.siteurl+'\//#'+nhref[1];

				$ ( this ).removeAttr ( 'href' );
				$ ( this ).attr ( 'href', href );
			}
		} );
	}



	$.fn.scrollTo = function ( target, options, callback ) {
		var isVisibleOnScreen = function ( elem ) {
			var docViewTop = $ ( window ).scrollTop ( );
			var docViewBottom = docViewTop+$ ( window ).height ( );
			var elemTop = $ ( elem ).offset ( ).top;
			var elemBottom = elemTop+$ ( elem ).height ( );
			return ( ( elemBottom<=docViewBottom )&&( elemTop>=docViewTop ) );
		};
		if ( window.innerWidth<768&&window.innerWidth>468 ) {
			var offset = 160;
		} else {
			var offset = 130;
		}
//alert(offset);
		if ( !isVisibleOnScreen ( this ) ) {
			$ ( 'html, body' ).animate ( {
				scrollTop: $ ( this ).offset ( ).top-offset+'px'
			}, 1500 );
			return this; // for chaining...
		}
	};
	$ ( '.menu a' ).live ( 'click', function ( e ) {

		if ( $ ( this ).attr ( 'id' )==='wp-toolbar' ) {

			$ ( this ).trigger ( 'click' );
			return;
		}
		if ( $ ( this ).attr ( 'href' ).indexOf ( '#' )===0 ) {
			e.preventDefault ( );
			//alert($(this).parent('li').attr('class').split(' ')[0]);
			if ( $ ( this ).parent ( 'li' ).attr ( 'class' ).split ( ' ' )[0].indexOf ( 'human-mega' )>-1 ) {

				$ ( '#'+$ ( this ).parent ( 'li' ).attr ( 'class' ).split ( ' ' )[0] ).fadeIn ( );
				// console.log($('#' + $(this).parent('li').attr('class')));
			} else if ( $ ( this ).attr ( 'href' ).length>1 ) {

				var to = $ ( this ).attr ( 'href' ).replace ( '\/', '' );
				$ ( to ).scrollTo ( { duration: '6000', top: '200' } );
			} else {
				$ ( this ).trigger ( 'click' );
			}

			if ( window.innerWidth<768 ) {
				$ ( this ).closest ( 'ul.menu' ).toggleClass ( 'menu-expanded', function ( ) {
//$(this).toggleClass('menu-expanded');
//$(this).removeAttr('style');
				} );
			}
		}

		if ( window.innerWidth<768 ) {
			$ ( this ).closest ( 'ul.menu' ).toggleClass ( 'menu-expanded', function ( ) {
//$(this).toggleClass('menu-expanded');
//$(this).removeAttr('style');
			} );
		}
	} );
	$ ( ".toggle-menu" ).on ( "click", function ( ) {
//$(this).closest('.nav-menu').find('.main-navigation').find('ul.menu')
// alert( $(this).closest('.nav-menu').find('.main-navigation').length);
		$ ( this ).closest ( '.nav-menu' ).find ( 'ul.menu' ).toggleClass ( 'menu-expanded', function ( ) {
//$(this).toggleClass('menu-expanded');
//$(this).removeAttr('style');
		} );
	} );
	// and run it again every time you scroll
	$ ( window ).scroll ( function ( ) {
		if ( $ ( document ).scrollTop ( )>100 ) {
//$('.main-navigation').parent('div').addClass('shrink');
			$ ( '.site-header' ).addClass ( 'scrolled' );

		} else {

			$ ( '.scrolled' ).removeClass ( 'scrolled' );
		}
	} );

	function isInView ( elem )
	{
		var docViewTop = $ ( window ).scrollTop ();
		var docViewBottom = docViewTop+$ ( window ).height ();

		var elemTop = $ ( elem ).offset ().top+$ ( '.site-header' ).height ()+20;
		console.log ( elemTop );
		var elemBottom = elemTop+$ ( elem ).height ();

		return ( ( elemBottom<docViewBottom )&&( elemTop>=docViewTop ) );
	}

	$ ( document ).bind ( 'scroll', function ( e ) {
		if ( window.innerWidth<768&&window.innerWidth>468 ) {
			var offset = 60;
		} else {
			var offset = 30;
		}
		$ ( '.menu a' ).each ( function () {
			if (
				  $ ( this ).attr ( 'href' ).indexOf ( '#' )===0&&
//begins before top
				  isInView ( $ ( $ ( this ).attr ( 'href' ) ) )
//but ends in visible area
//+ 10 allows you to change hash before it hits the top border
				  ) {
				$ ( 'li.hovered' ).removeClass ( 'hovered' );
				$ ( this ).parent ( 'li' ).addClass ( 'hovered' );
				window.location.hash = $ ( this ).attr ( 'href' )+'/';

			}
		} );
	} );
	if ( $ ( '.vertical li.menu-item-has-children' ) ) {
		var li = $ ( '.vertical li.menu-item-has-children a' );
		//  console.log(li.html());
		var paddingTop = li.css ( 'padding-top' );
		var paddingBottom = li.css ( 'padding-bottom' );
		var lineHeight = li.css ( 'line-height' );
		$ ( '.vertical .sub-menu li a' ).each ( function ( ) {
//  console.log($(this));
			$ ( this ).css ( 'padding-top', paddingTop );
			$ ( this ).css ( 'padding-bottom', paddingBottom );
			$ ( this ).css ( 'line-height', lineHeight );
		} );
	}

// var override_fix = $('.horizontal ul.menu>li.menu-item-has-children a').css('padding-right')+7;
// $('.horizontal ul.menu>li.menu-item-has-children a').css('padding-right',override_fix);
} );