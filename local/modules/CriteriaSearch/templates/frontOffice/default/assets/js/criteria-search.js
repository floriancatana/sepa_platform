jQuery(function($){

    if ( $("#price-filter").length ) {
        $("#price-filter").slider({}).on('slideStop', function(e) {
            $("input[name=price_min]").val(e.value[0]);
            $("input[name=price_max]").val(e.value[1]);
            updateUrl();
            displaySearchProductList();
        });
    }

    $(".select-search").multiselect({
        nonSelectedText: $("#nonSelectedText").html(),
        allSelectedText: $("#allSelectedText").html(),
        nSelectedText: $("#nSelectedText").html()
    });

    var criteria = getURLParameter('criteria');
    var search_form = $('#criteria-search-form');

    if (criteria == "true") {
        fillForm(false);
    }

    var searchTimer = null;

    $('.input-search').on('change', function(e) {
        if (searchTimer !== null) {
            clearTimeout(searchTimer)
        }

        searchTimer = setTimeout(function() {
            $('input[name=page]').val(1);
            updateUrl();

            displaySearchProductList();
        }, 500, this);
    });

    $('.criteria-pagination').on('click', function(e) {
        $('input[name=page]').val($(this).data('page'));
        $("html, body").animate({ scrollTop: 0 }, "slow");

        displaySearchProductList();

        updateUrl();
    });


    function emptyForm() {
        $('.select-search').multiselect('deselectAll', false).multiselect('updateButtonText');
        $('.input-search').each(function(){ this.checked = false; });
    }

    function fillForm(displayProduct) {
        $.ajax({
            url : "/criteria/page/info/search"+window.location.search,
            type: "GET"
        }).done(function(data) {
            $.each(data.multiCheckBox, function(k, v) {
                if($('#'+k).length) {
                    $('#' + k).multiselect('select', v);
                } else {
                    $.each(v, function(k2, v2){
                        $('#'+k+'-'+v2).each(function(){ this.checked = true; });
                    });
                }
            });

            $.each(data.simpleCheckBox, function(k, v) {
                $('#'+v).each(function(){ this.checked = true; });
            });

            if (displayProduct) {
                displaySearchProductList();
            }
        });
    }


    function displaySearchProductList() {

        $.ajax({
            url: "/criteria/render/search/",
            type: "GET",
            data: search_form.serialize()
        }).done(function(data) {
            $(".products-content").html(data);
            $(".amount").html($("#total-search-results").html());

            $('.criteria-pagination').on('click', function(e) {
                $('input[name=page]').val($(this).data('page'));
                $("html, body").animate({ scrollTop: 0 }, "slow");

                displaySearchProductList();

                updateUrl();
            });

            if ($('.toolbar').length > 0) {
                var $toolbar = $('.toolbar'),
                    $filterBtns = $('[data-toggle="filter"]', $toolbar),
                    $productList = $('.products-list');

                $filterBtns.each(function () {
                    var $btn = $(this);

                    $btn.on('click', function () {

                        $filterBtns.removeClass('active');
                        $btn.addClass('active');

                        var filter = $btn.data('filter');

                        if ($productList.hasClass('grid')) {
                            $productList.removeClass('grid').addClass(filter);
                        }

                        if ($productList.hasClass('list')) {
                            $productList.removeClass('list').addClass(filter);
                        }
                    });
                });
            }
        });
    }

    function updateUrl() {
        $.ajax({
            url : "/criteria/url/search/",
            type: "GET",
            data: search_form.serialize()
        }).done(function(data) {
            history.pushState({}, "search", data.url);
        });
    }

    if (typeof History.Adapter !== 'undefined') {
        History.Adapter.bind(window,'statechange',function(e){
            emptyForm();
            fillForm(true);
        });
    }

    function getURLParameter(name) {
        return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20'))||null
    }
});