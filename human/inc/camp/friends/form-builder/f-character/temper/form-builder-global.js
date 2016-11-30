jQuery ( document ).
	  ready ( function ( $ ) {

		  function hide_reply_form () {

			  $ ( '.reply-expanded' ).
				    fadeOut ();
			  $ ( '.reply-expanded' ).
				    removeClass ( 'reply-expanded' );
		  }


		  $ ( '.comment_reply_link a' ).
			    live ( 'click', function (
				      e ) {
				    e.preventDefault ();
				    var c_id = '';
				    //   console.log($(this).attr('data-comment-id'));
				    c_id = $ ( this ).
					      attr ( 'data-comment-id' );
				    c_id = '<input type="hidden" name="comment_parent_ID" class="comment_parent_ID" value="'+c_id+'">';
				    if ( $ ( this ).closest ( '.comment-wrapper' ).find ( 'div.human-form-wrapper' ).find ( 'form' ).find ( '.comment_parent_ID' ).length<1 ) {
					    $ ( this ).
						      closest ( '.comment-wrapper' ).
						      find ( 'div.human-form-wrapper' ).
						      find ( 'form' ).
						      append ( c_id );
					    console.log ( $ ( this ).closest ( '.comment-wrapper' ).find ( 'div.human-form-wrapper' ) );
				    }
				    hide_reply_form ();
				    $ ( this ).
					      closest ( '.comment-wrapper' ).
					      find ( 'div.human-form-wrapper' ).
					      toggle ();
				    $ ( this ).
					      closest ( '.comment-wrapper' ).
					      find ( 'div.human-form-wrapper' ).
					      addClass ( 'reply-expanded' );
				    // e,stopPropagation();
				    //  console.log($(this).closest('.comment-wrapper').find('.comment_parent_ID').length);

			    } );
		  $ ( '.expand-reply-comments' ).
			    live ( 'click', function (
				      e ) {
				    e.preventDefault ();
				    var parent = $ ( this ).
					      parents ( '.level-0' );
				    if ( parent.hasClass ( 'expanded' ) ) {
					    $ ( this ).
						      find ( '.fa' ).
						      removeClass ( 'fa-caret-up' ).
						      addClass ( 'fa-caret-down' );
					    parent.removeClass ( 'expanded' );
				    } else {
					    //console.log(parent);
					    parent.addClass ( 'expanded' );
					    $ ( this ).
						      find ( '.fa' ).
						      removeClass ( 'fa-caret-down' ).
						      addClass ( 'fa-caret-up' );
				    }
			    } );

		  function validateEmail ( $email ) {
			  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			  return emailReg.test ( $email );
		  }
		  $ ( ".form-label" ).each ( function () {

			  $ ( '#'+$ ( this ).attr ( 'for' ) ).live ( 'change', function () {
				  if ( $ ( this ).hasClass ( 'selected-elem' ) ) {
					  $ ( '#'+$ ( this ).attr ( 'for' ) ).prop ( "checked", false );
					  $ ( this ).removeClass ( 'selected-elem' );
				  } else {

					  $ ( '#'+$ ( this ).attr ( 'for' ) ).prop ( "checked", true );
					  $ ( this ).addClass ( 'selected-elem' );
					  $ ( this ).removeClass ( "human-alert" );

				  }

			  } );
		  } );
		  $ ( ".form-elem" ).live ( 'keyup', function () {

			  if ( $ ( this ).attr ( 'name' )==='EMAIL'||$ ( this ).attr ( 'name' )==='email' ) {
				  if ( !validateEmail ( $ ( this ).val () ) ) {

				  } else {
					  $ ( this ).
						    removeClass ( "human-alert" );
				  }
			  } else if ( $ ( this ).val ().length>2 ) {

				  $ ( this ).
					    removeClass ( "human-alert" );
			  }
		  } );

		  function over_stars ( rated, star ) {

			  var stars = [ ];
			  stars = star.parent ( 'span' ).
				    find ( '.to-star' );
			  console.log ( stars );
			  stars.each ( function () {
				  if ( $ ( this ).attr ( 'data-star-number' )<=rated ) {
					  $ ( this ).
						    addClass ( 'fa-star' );
					  $ ( this ).
						    removeClass ( 'fa-star-o' );
				  } else {
					  $ ( this ).
						    addClass ( 'fa-star-o' );
					  $ ( this ).
						    removeClass ( 'fa-star' );
				  }
			  } );
		  }
		  $ ( ".to-star" ).
			    on ( 'mouseover', function () {

				    var rated = $ ( this ).
					      attr ( 'data-star-number' );
				    var star = $ ( this );
				    over_stars ( rated, star );
			    } );
		  $ ( '.human-comment-form-rating' ).
			    on ( 'mouseleave', function () {
				    if ( $ ( this ).find ( '.human-rating-field' ).val ().length<1 ) {
					    $ ( this ).
						      find ( ".to-star" ).
						      each ( function () {

							      $ ( this ).
									addClass ( 'fa-star-o' );
							      $ ( this ).
									removeClass ( 'fa-star' );
						      } );
				    } else {

					    var star = $ ( '.human-comment-form-rating [data-star-number="'+$ ( '#rating' ).val ()+'"]' );
					    over_stars ( $ ( this ).val (), star );
				    }
			    } );
		  $ ( ".to-star" ).
			    on ( 'click', function (
				      e ) {
				    e.preventDefault ();
				    e.stopPropagation ();
				    var rated = $ ( this ).
					      attr ( 'data-star-number' );
				    $ ( this ).
					      parents ( '.human-comment-form-rating' ).
					      find ( '.human-rating-field' ).
					      val ( rated );
				    $ ( this ).
					      parents ( '.human-comment-form-rating' ).
					      find ( '.human-rating-field' ).
					      removeClass ( 'human-alert' );
				    var star = $ ( this );
				    over_stars ( rated, star );
			    } );

		  function human_load_more ( data ) {

			  var form_id = data['form_id'];
			  var msg = $ ( '#'+form_id+' .human-form-msg-success' );
			  if ( $ ( '#ajax' ).length>0 ) {
				  // alert($('#ajax').text());
			  } else {
				  $ ( 'body' ).
					    append ( '<div id="ajax"></div>' );
				  // console.log(data);
				  $.post ( humanAjax.ajaxurl, data, function (
					    response ) {
					  console.log ( response );
					  if ( response.success ) {
						  //   console.log(response.data.content);

						  if ( typeof data['data_insert']!=="undefined"&&data['data_insert']!=="0" ) {

							  //console.log(response.data.content.trim().length);
							  if ( response.data.content.length>0 ) {

								  $ ( data['new_wrap']+response.data.content+'</div>' ).
									    insertAfter ( $ ( '#'+data['data_insert'] ) );
								  console.log ( response.data.content );
								  hide_reply_form ();
							  } else {
								  msg.fadeIn ();
							  }
							  //console.log('data-insert');
						  } else if ( typeof data['data_insert']!=="undefined"&&data['data_insert']=="0" ) {

							  if ( response.data.content.trim ().length>''&&response.data.content.trim ().length!=0 ) {

								  $ ( '.human-comments' ).
									    prepend ( response.data.content );
							  } else {

								  //console.log('trimed');
								  msg.fadeIn ();
								  //alert(msg.html()+form_id);
							  }

						  } else {


							  $ ( response.data.content ).
								    insertBefore ( '.current_load_more' );
						  }


						  var dat_extra = $ ( '#data-extra' ).
							    attr ( 'data-extra' );
						  $ ( '.current_load_more' ).
							    attr ( 'data-extra', dat_extra );
						  $ ( '.current_load_more' ).
							    removeClass ( 'current_load_more' );
						  $ ( '#data-extra' ).
							    remove ();
						  $ ( '.loading-gif' ).
							    hide ();
					  } else {
						  //console.log(response);
					  }
					  $ ( '#ajax' ).remove ();
				  } );
			  }
		  }
		  $ ( ".human-form-submit" ).
			    live ( "click", function ( e ) {

				    e.preventDefault ();
				    e.stopPropagation ();
				    var form_id = $ ( this ).closest ( "form" );
				    $ ( this ).closest ( "form" ).
					      find ( ".form-elem" ).
					      each ( function () {
						      if ( $ ( this ).attr ( 'type' )==='checkbox'||$ ( this )
								.attr ( 'type' )==='radio' ) {
							      if ( $ ( this ).is ( ":checked" ) ) {

							      } else {
								      $ ( this ).addClass ( "human-alert" );
							      }
						      } else if ( $ ( this ).val ().length<2 ) {
							      // alert($(this).val());
							      if ( $ ( this ).attr ( "aria-required" )=="required" ) {
								      if ( $ ( this ).hasClass ( 'human-rating-field' ) ) {
									      if ( $ ( this ).val ().length!=1 ) {
										      $ ( this ).addClass ( "human-alert" );
										      alert ( $ ( this ).parents ( '.elem-star_rating' ).find ( 'div.form_field_error' ).text () );
									      }
								      } else {
									      //  alert('done');
									      $ ( this ).addClass ( "human-alert" );
									      $ ( this ).next ( 'div.form_field_error' ).
											fadeIn ();
								      }
							      }
						      } else if ( $ ( this ).attr ( 'name' )==='EMAIL'||$ ( this ).attr ( 'name' )==='email' ) {
							      if ( !validateEmail ( $ ( this ).val () ) ) {

								      $ ( this ).addClass ( "human-alert" );
							      }
						      }
					      } );
				    var statusdiv = form_id.find ( ".human-form-msg" );
				    var commentform = form_id.find ( ".comment-elem" );
				    if ( form_id.find ( ".human-alert" ).length==0 ) {

					    $ ( this ).next ( '.loading-gif' ).show ();
					    $ ( '.form_field_error' ).fadeOut ();
					    var comment_post_id = '';
					    if ( form_id.find ( ".comment_post_ID" ).length>0 ) {
						    var comment_post_id = form_id.find ( ".comment_post_ID" ).val ();
					    }

					    var post_id = '';
					    if ( form_id.find ( ".post_ID" ).length>0 ) {
						    var post_id = form_id.find ( ".post_ID" ).val ();
					    }
					    var current_user = '';
					    if ( form_id.find ( ".current_user" ).length>0 ) {
						    var current_user = form_id.find ( ".current_user" ).
							      val ();
					    }
					    var comment_parent_id = '';
					    if ( form_id.find ( ".comment_parent_ID" ).length>0 ) {
						    var comment_parent_id = form_id.find ( ".comment_parent_ID" ).val ();
					    }
					    var comment_metas = [ ];
					    var post_metas = [ ];
					    var user_metas = [ ];
					    var subject = form_id.find ( ".form-subject" ).
						      val ();
					    user_metas = form_id.find ( ".user_meta" ).
						      serializeArray ();
					    post_metas = form_id.find ( ".post_meta" ).
						      serializeArray ();
					    comment_metas = form_id.find ( ".comment_meta" ).
						      serializeArray ();
					    datas = {
						    user_metas: user_metas,
						    post_metas: post_metas,
						    comment_metas: comment_metas,
					    };
					    var mailchimp_subscribe = '';
					    if ( form_id.find ( '.subscribe_mailchimp' ).length>0 ) {
						    if ( form_id.find ( '.subscribe_mailchimp' ).val () ) {
							    mailchimp_subscribe = form_id.find ( '.subscribe_mailchimp' ).val ();
						    } else {

						    }
					    }
					    var mailchimp = '';
					    if ( form_id.find ( '.human_mailchimp' ).length>0 ) {
						    if ( form_id.find ( '.human_mailchimp' ).val () ) {
							    mailchimp = form_id.find ( '.human_mailchimp' ).
								      val ();
						    } else {

						    }
					    }
					    var data = {
						    action: "cssFormAjax",
						    nonce: humanAjax.nonce,
						    form_id: form_id.attr ( 'id' ),
						    form_data: datas,
						    comment_post_id: comment_post_id,
						    comment_parent_id: comment_parent_id,
						    post_id: post_id,
						    current_user: current_user,
						    form_notify: form_id.find ( '.form_notify_admin' ).
							      val (),
						    mailchimp_subscribe: mailchimp_subscribe,
						    mailchimp: mailchimp
					    };
					    console.log ( data );
					    // return;
					    $.post ( humanAjax.ajaxurl, data, function (
						      response ) {
						    $ ( '.form-messages' ).
							      hide ();
						    console.log ( response );
						    if ( response.success ) {

							    var msg = form_id.data ( "success" );
							    if ( response['data']['new_comment_id'] ) {
								    var new_comment = response['data']['new_comment_id'];
								    var replies = $ ( '#'+comment_parent_id ).
									      parents ( '.comment-reply' ).length;
								    if ( replies>0 ) {
									    var template = $ ( '.human-comments a.load-more' ).
										      attr ( 'data-reply' ).
										      replace ( '@parent_id@', comment_parent_id );
								    } else if ( $ ( '.human-comments a.load-more' ).attr ( 'data-new' ) ) {

									    var template = $ ( '.human-comments a.load-more' ).
										      attr ( 'data-new' ).
										      replace ( '@comment_id@', new_comment );
								    } else {

								    }
								    var new_wrap = '<div class="comment-reply level-'+replies+'">';
								    if ( !comment_parent_id ) {
									    comment_parent_id = '0';
									    var new_wrap = '';
									    var end_wrap = '';
								    }
								    var data = {
									    action: "human_loop_ajax",
									    nonce: humanAjax.nonce,
									    ajax_type: 'comments',
									    data_extra: template,
									    data_insert: comment_parent_id,
									    new_wrap: '<div class="comment-reply level-'+replies+' expanded">',
									    replies: replies,
									    form_id: form_id.attr ( 'id' )
								    };
								    console.log ( data );
								    human_load_more ( data );
							    } else {

								    form_id.find ( ".human-form-msg-success" ).
									      fadeIn ();
								    form_id.find ( ".human-form-msg-fail" ).
									      hide ();
							    }
							    //window.external.AutoCompleteSaveForm(form_id.attr('id'));
						    } else {

							    form_id.find ( ".human-form-msg-fail" ).
								      fadeIn ();
							    form_id.find ( ".human-form-msg-success" ).
								      hide ();
						    }
						    $ ( '.loading-gif' ).
							      hide ();
					    } );
				    }

			    } );

		  function comment_thumbs ( comment_id, thumb ) {
			  var data = {
				  action: "cssFormAjax",
				  nonce: humanAjax.nonce,
				  ajax_type: 'thumbs',
				  comment_id: comment_id,
				  thumb: thumb
			  };
			  $.post ( humanAjax.ajaxurl, data, function (
				    response ) {

				  if ( response.success ) {

					  var thumbs = response['data']['thumbs'];
					  var thumb_holder = thumb+comment_id;
					  $ ( '#'+thumb_holder ).
						    html ( thumbs );
				  }
			  } );
		  }
		  $ ( '.comment_flag_holder' ).
			    live ( 'click', function () {
				    var comment_id = $ ( this ).
					      attr ( 'data-comment-id' );
				    var data = {
					    action: "cssFormAjax",
					    nonce: humanAjax.nonce,
					    ajax_type: 'flag',
					    comment_id: comment_id,
					    flag: comment_id
				    };
				    $.post ( humanAjax.ajaxurl, data, function (
					      response ) {

					    if ( response.success ) {

						    $ ( '#flag'+comment_id ).
							      addClass ( 'flagged' );
					    }
				    } );
			    } );
		  $ ( '.comment_thumbs i' ).
			    live ( 'click', function () {
				    var comment_id = $ ( this ).
					      attr ( 'data-comment-id' );
				    var thumb = $ ( this ).
					      attr ( 'data-thumb' );
				    comment_thumbs ( comment_id, thumb );
			    } );
		  $ ( window ).
			    scroll ( function (
				      e ) {

				    if ( $ ( '.loading-more' ).hasClass ( 'auto-loading' ) ) {
					    e.preventDefault ();
					    e.stopPropagation ();
					    if ( $ ( window ).scrollTop ()+$ ( window ).height ()>=
						      $ ( '.loading-more' ).offset ().top+$ ( '.loading-more' ).height () ) {
						    //$('.main-navigation').parent('div').addClass('shrink');
						    var load_btn = $ ( '.loading-more' ).
							      find ( '.load-more' );
						    //   console.log(load_btn);
						    load_btn.trigger ( 'click' );
					    } else {

						    $ ( '.scrolled' ).
							      removeClass ( 'scrolled' );
					    }
				    }
			    } );
		  $ ( '.load-more' ).
			    on ( 'click', function (
				      e ) {
				    e.preventDefault ();
				    e.stopPropagation ();
				    var type = $ ( this ).
					      data ( 'type' );
				    var data = {
					    action: "human_loop_ajax",
					    nonce: humanAjax.nonce,
					    ajax_type: type,
					    data_extra: $ ( this ).
						      attr ( 'data-extra' )

				    };
				    // console.log(data);
				    $ ( this ).
					      addClass ( 'current_load_more' );
				    //console.log('.'+type+'-loading-gif');
				    $ ( '.'+type+'-loading-gif' ).
					      show ();
				    human_load_more ( data );
			    } );
	  } );