jQuery('document').ready(function ($) {
            action = 'human_backup';

            function check_backup() {
                        toggle_human_gif();
                        var data = {
                                    action: action,
                                    nonce: humanAjax.nonce,
                                    check_backup: 1
                        };
                        $.post(ajaxurl, data, function (response) {
                                    console.log(response);
                                    if (typeof response['data']['cleared'] === 'undefined') {
                                                setTimeout(function () {

                                                            $('.baby-created').html(response['data']);
                                                            check_backup();
                                                }, 10000);
                                    } else {
                                                $('.baby-created').html(response['data']['template'] + '<br>' + response['data']['template']);
                                                $('.human-loading-gif-wrapper').fadeOut();
                                    }
                        });
            }

            $('#new-baby-form').submit(function (e) {
                        e.preventDefault();

                        var ajaxurl = humanAjax.ajaxurl;
                        var data = {
                                    action: action,
                                    nonce: humanAjax.nonce,
                                    start_backup: 1,
                                    mysqldump_path: $('#mysqldump_path').val(),
                                    template_name: $('#template_name').val()
                        };
                        toggle_human_gif();
                        $.post(ajaxurl, data, function (response) {
                                    //console.log ( response );

                                    check_backup();
                        });
            });
            check_backup();

});