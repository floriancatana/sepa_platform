;(function($) {
    $(document).ready(function(){

        $('#comment-save').on('click', function(){

            var $link, $form, $list;

            $link = $(this);
            $form = $link.parents('form').first();
            $list = $form.find('#comment-status').first();

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {status: $list.val()},
                url: $form.attr('action')
            }).done(function(data, textStatus, jqXHR){
                if (data.success) {
                    $list.val(data.status);
                } else {
                    $list.val(data.status);
                }
            }).fail(function(jqXHR, textStatus, errorThrown){

            });

        });

        var $statusMenu = $('#dropdown-status');

        $('.dropdown-toggle').on('click.bs.dropdown', function (e) {

            var $btn = $(e.currentTarget),
                $parent = $btn.parent(),
                $menu = $parent.children('.dropdown-menu'),
                $clonedMenu = null;

            console.log($btn.data('id'));

            if ($menu.length == 0) {
                // creating the menu
                $clonedMenu = $statusMenu.children().first().clone();
                $clonedMenu.appendTo($parent);
            }
        });

        $('.dropdown-status').on('click', '.change-status', function (e) {
            var $trigger;

            e.preventDefault();

            $trigger = $(e.currentTarget);

            console.log("trigger status change", e.currentTarget, $trigger);
            console.log("trigger status change", $trigger.parents('.actions').first().data('id'), $trigger.data('status'));

            $.ajax({
                type: "POST",
                dataType: 'json',
                data: {'id': $trigger.parents('.actions').first().data('id'), 'status': $trigger.data('status')},
                url: commentConfig['status']
            }).done(function (data, textStatus, jqXHR) {
                var status;
                if (data.success) {
                    status = commentStatus[data.data.status];
                    $('#status-' + data.data.id)
                        .removeClass('btn-default btn-success btn-info btn-warning btn-danger')
                        .addClass('btn-' + status.css)
                        .html(status.label + ' <span class="caret"></span>')
                    ;
                } else {
                    $('#status-failed').modal('show');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                $('#status-failed').modal('show');
            });

        });

        $(".comment-delete").click(function () {
            $("#comment_delete_id").val($(this).data("id"));
        });

        var getQueryParams = function getQueryParams() {
            var pl = /\+/g,  // Regex for replacing addition symbol with a space
                search = /([^&=]+)=?([^&]*)/g,
                decode = function (s) {
                    return decodeURIComponent(s.replace(pl, " "));
                },
                query = window.location.search.substring(1),
                urlParams = {},
                matches;

            while (matches = search.exec(query)) {
                urlParams[decode(matches[1])] = decode(matches[2]);
            }

            return urlParams;
        };

        var getFilterLoop = function getFilterLoop() {
            var $filterForm = $('.table-filters');
            var filters = {};

            $filterForm.find('.filter-element').each(function () {
                var $this = $(this);
                filters[$this.data('name')] = $this.val();
            });

            return filters;
        };

        var setFilterLoop = function setFilterLoop() {
            var $filterForm = $('.table-filters');
            var filters = getQueryParams();

            $filterForm.find('.filter-element').each(function () {
                var $this = $(this);
                if ($this.data('name') in filters) {
                    $this.val(filters[$this.data('name')]);
                }
            });

            return filters;
        };

        $(".trigger-filter").on('click', function () {
            var queries = [],
                param,
                params,
                newParams;

            params = getQueryParams();
            newParams = getFilterLoop();

            for (param in newParams) {
                if (newParams.hasOwnProperty(param)) {
                    params[param] = newParams[param];
                }
            }

            for (param in params) {
                if (params.hasOwnProperty(param)) {
                    queries.push(encodeURIComponent(param) + '=' + encodeURIComponent(params[param]));
                }
            }

            window.location.search = '?' + queries.join('&');
        });

        setFilterLoop();
    });
})(jQuery);
