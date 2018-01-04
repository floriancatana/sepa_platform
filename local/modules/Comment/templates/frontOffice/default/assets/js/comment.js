;(function($) {

    $(document).ready(function () {

        var $commentTop = $('#comment-top'),
            $commentMessage = $('#comment-top-message'),
            $commentList = $('#comment-list'),
            $commentCustomer = $('#comment-customer'),
            $commentForm = $('#form-add-comment');

        var displayMessage = function displayMessage($element, cssClass, messages, timeout){
            $element.slideUp('fast', function(){
                var i = 0,
                    domP;
                if (messages.length > 0) {
                    $element.html("");
                    $element
                        .removeClass('alert-success alert-info alert-warning alert-danger hidden')
                        .addClass('alert-' + cssClass)
                    ;
                    for ( ; i < messages.length ; i++ ) {
                        domP = document.createElement( "p" );
                        $element.append( $(domP).html(messages[i]) );
                    }
                    $element.slideDown('slow');

                    if (timeout) {
                        setTimeout(function(){$element.slideUp();}, timeout);
                    }
                }
            });
        };

        var loadComments = function loadComments(start, count) {

            $.ajax({
                type: "GET",
                data: {
                    'ref': commentConfig['ref'],
                    'ref_id': commentConfig['id'],
                    'start': start,
                    'count': count
                },
                url: commentConfig['get']
            }).done(function(data){
                $commentList.append(data);
            }).fail(function(jqXHR, textStatus, errorThrown){
                displayMessage($commentMessage, 'danger', [textStatus]);
            });

        };

        var abuseComment = function abuseComment(ev) {

            var $link, $form, $alert;

            $link = $(ev.currentTarget);
            $form = $link.parents('form').first();
            $alert = $form.parents('.comment-item').first().find('.comment-alert');

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: $form.serialize(),
                url: $form.attr('action')
            }).done(function(data, textStatus, jqXHR){
                if (data.success) {
                    displayMessage($alert, 'success', [data.message], 5000);
                    $form.parents('.comment-abuse').first().remove();
                } else {
                    displayMessage($alert, 'danger', [data.message]);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                displayMessage($alert, 'danger',  [textStatus]);
            });

        };

        var deleteComment = function deleteComment($btn) {

            var $form, $alert;

            $form = $btn.parents('form').first();
            $alert = $form.parents('.comment-item').first().find('.comment-alert');

            $.ajax({
                type: "GET",
                url: $btn.attr('href')
            }).done(function(data){
                if (data.success) {
                    displayMessage($alert, 'success', [data.message], 5000);
                    $('#comment-customer').remove();
                } else {
                    displayMessage($alert, 'danger', [data.message]);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                displayMessage($commentMessage, 'danger', [textStatus]);
            });

        };

        $commentForm.on('submit', function (ev) {

            ev.preventDefault();

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: $(this).serialize(),
                url: commentConfig['post']
            }).done(function(data, textStatus, jqXHR){
                if (data.success) {
                    displayMessage($commentMessage, 'success', data.messages);
                    $commentForm.slideUp(function(){
                        $commentForm.remove();
                    });
                } else {
                    displayMessage($commentMessage, 'warning', data.messages);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){
                displayMessage($commentMessage, 'danger', [textStatus]);
            });

        });

        loadComments(commentConfig['start'], commentConfig['count']);

        $commentList.on( "click", ".comments-more-link", function(ev) {
            ev.preventDefault();

            commentConfig['start'] += commentConfig['count'];
            loadComments(commentConfig['start'], commentConfig['count']);

            $(ev.currentTarget).parents('.comments-more').first().remove();
        });

        $commentList.on( "click", ".abuse-trigger", function(ev) {
            ev.preventDefault();

            abuseComment(ev);
        });

        $commentList.on( "click", ".delete-trigger", function(ev) {
            ev.preventDefault();

            $trigger = $(ev.currentTarget);

            if ($trigger.data("confirmed") == "1") {
                deleteComment($trigger);
            } else {
                $trigger.data("confirmed", "1");
                $trigger.html($trigger.data("message"));
            }

        });

    });

})(jQuery);
