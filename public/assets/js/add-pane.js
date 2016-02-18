//  ====================  Add Pane JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     September 16, 2015

//    This file contains javascript for the base Add pane of Linken

//  ===========================================================

$( document ).ready(function() {

    var addButtonSpinner;
    var infoPaneSpinner;

    if ($('div#blue-hitbox-add-pane.flash').length) {

        // Show the message
        $('textarea#add').hide();
        $('#flash').show();

        // Transition the background color back to normal
        setTimeout(function() {
            revertHitboxStyle();
        }, 2000);
    }
    $('div#blue-hitbox-add-pane').click(function() {
        $('textarea#add').focus();
    });
    $('textarea#add').focus(function() {
        $(this).attr('placeholder', '');
        autosize($('textarea#add'));
        autosize($('textarea#add-description'));
        openAddPane();
        triggerInfoPane();
        $('input.select2-search__field').prop('tabindex', "3");
    });
    $('textarea#add').change(function() {
        // Load the info pane if necessary
        triggerInfoPane();
    });
    $('select#add-tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: "Tags",
        ajax: {
            url: '/tags/search?scope=user',
            delay: 100,
            processResults: function (data) {
                return {
                    results: data.items
                    };
            }
        }
    });
    $('button#add-button').click(function() {
        var button = $(this);
        button.prop("disabled", true);
        button.html('&nbsp;');

        // Show the spinner
        addButtonSpinner = addSpinnerToElement(button.get(0));

        // Gather form data
        var csrf = $('input#csrf_token').val();
        var value = $('textarea#add').val();
        var description = $('textarea#add-description').val();
        var type = "Note";
        var url = null;
        var photo_url = null;
        var title = null;

        if ($('#info-pane').attr('data-url') != "") {
            type = "Link";
            value = $('#info-pane .title').val();
            url = $('#info-pane').attr('data-url');
            photo_url = $('#info-pane .image-container').attr('data-image-url');
            title = $('#info-pane .title').val();
        }

        var tags = $("select#add-tags").val();

        $.ajax({
            url: "/item/add",
            cache: false,
            method: 'post',
            data: {
                title: title,
                value: value,
                description: description,
                url: encodeURIComponent(url),
                photo_url: encodeURIComponent(photo_url),
                type: type,
                tags: tags,
                _token: csrf
            }
        })
        .done(function(response) {
            closeAddPane(response);
            addButtonSpinner.stop();
            addButtonSpinner = null;
            button.html('Add');
            button.prop("disabled", false);
            clearAddPane();
        });
    });
    $('textarea#add-description').on('autosize:resized', function() {
        adjustPaneHeight();
    });
    $('select#add-tags').on("change", function (e) {
        adjustPaneHeight();
    });
    $("#info-pane .image-container").click(function () {

        if (infoPaneSpinner) {
            infoPaneSpinner.stop();
        }

        var container = $(this);
        var url = $('textarea#add').val();
        var csrf = $('input#csrf_token').val();
        var originalUrl = $(this).css("background-image");
        $(this).css("background-image", "url('')");
        infoPaneSpinner = addSpinnerToElement($('#info-pane').get(0), '30px', '#448dff');

        var nextImageNumber = parseInt($('#info-pane .image-container').attr('data-image-number'));

        $.ajax({
            url: "/link/parse",
            cache: false,
            method: 'post',
            data: { url: url, _token: csrf, image: true, image_number: nextImageNumber }
        })
        .done(function( response ) {
            if (response.image == '') {
                infoPaneSpinner.stop();
                container.css("background-image", originalUrl);
            } else {
                updateInfoPane(response.image);
                $('#info-pane .image-container').attr('data-image-number', response.image_number);
            }
        });
    });
    $(".discoverable-option").click(function() {
        $('.scope').html($(this).data('display'));
    });
    $('#show-settings-modal-link').click(function(e){
        e.preventDefault();
        $('#discoverable-options-modal')
            .modal('hide')
            .on('hidden.bs.modal', function (e) {
                $('#userSettingsModal').modal('show');
                $(this).off('hidden.bs.modal');
            });
    });
    function clearAddPane()
    {
        $('textarea#add').html("");
        $('textarea#add-description').html("");
        $("select#add-tags").select2("val", "");
        $("#info-pane input.title").val("");
        $("#info-pane .image-container").attr('style', '');
    }
    function triggerInfoPane()
    {
        var entry = $.trim($('textarea#add').val());
        var foundUrls = entry.match(/(([a-z]{3,6}:\/\/)|(^|\s))([a-zA-Z0-9\-]+\.)+[a-z]{2,13}[\.\?\=\&\%\/\w\-]*\b([^@]|$)/mg);
        var isUrl = false;
        var infoPaneOpen = $('#info-pane').attr('data-open');
        if(foundUrls && foundUrls.length > 0) {
            isUrl = true;
            var url = $.trim(foundUrls[0]);

            // If data has already been loaded for this url, just open
            var sameUrl = true;
            var loadedUrl = $('#info-pane').attr('data-url');
            if (loadedUrl != url) {
                sameUrl = false;
            }
            if(sameUrl) {
                openInfoPane();
                return;
            }
        }

        // If they clear the add field, or if its no longer a URL, close the info pane
        if((entry == "" || isUrl === false) && infoPaneOpen == 'true') {
            closeInfoPane();
        } else if (isUrl) {
            loadInfoPaneData(foundUrls[0]);
        }
    }
    function adjustPaneHeight()
    {
        var containerHeightPx = $('div#add-pane-container').css('height');
        var containerHeightAr = containerHeightPx.split('px');
        var containerHeight = containerHeightAr[0];
        var addPaneNewHeight = parseInt(containerHeight) + 27;
        $('div#add-pane').css('min-height', addPaneNewHeight + 'px');
    }

    function openAddPane()
    {
        if ($('div#blue-hitbox-add-pane').hasClass('flash')) {
            revertHitboxStyle();
        }
        if ($('div#add-pane').attr('data-toggle') == 'open') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'open');
        var windowWidth = $(window).width();
        var height = 180;

        if($('input#add-pane-height').val() != '') {
            height = $('input#add-pane-height').val();
        }
        $('div#add-fader').show().velocity({
            opacity: 0.25
        }, 300, function() {
            $('div#add-pane').show().velocity({
                minHeight: [ height, "easeOutCubic" ]
            }, 200, function() {
                $('div#add-pane textarea').velocity({
                    opacity: 1
                }, 200, function() {
                    $('div#add-pane .select2').velocity({
                        opacity: 1
                    }, 200, function() {
                        $('div#add-pane button').velocity({
                            opacity: 1
                        }, 200, function() {
                            $('div#add-pane .link-options').velocity({
                                opacity: 1
                            }, 200, function() {
                                $('div#add-fader').on("click", function() {
                                    closeAddPane();
                                });
                            });
                        });
                    });
                });
            });
        });
    }

    function closeAddPane(newItemData)
    {
        $('div#add-fader').off("click");
        if ($('div#add-pane').attr('data-toggle') == 'closed') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'closed');

        // Store the height so we can reopen correctly
        var heightPx = $('div#add-pane').css('height');
        var height = heightPx.split('px');
        $('input#add-pane-height').val(height[0]);

        // Fade out fields and slide up
        $('div#add-pane textarea, div#add-pane .select2, div#add-pane button, div#add-pane .link-options').velocity({
            opacity: 0
        }, 100, function() {
            $('div#add-pane').velocity({
                minHeight: [ 0, "easeInCubic" ]
            }, 200, function() {
                $('div#add-pane').hide();
                $('div#add-fader').velocity({
                    opacity: 0
                }, 200, function() {
                    closeInfoPane();
                    $('div#add-fader').hide();
                    var addVal = $.trim($('textarea#add').val());
                    if (addVal == '') {
                        $('textarea#add').val('').attr('placeholder', 'Add URL or Note');
                    }
                    if (newItemData) {
                        showNewItem(newItemData);
                        showHitboxAlert('success', 'Saved!');
                    }
                });
            });
        });
    }

    function showNewItem(newItemHtml)
    {
        $("#item-list-container").prepend(newItemHtml).slideDown();
        $('textarea#add').val('').attr('placeholder', 'Add URL or Note');
    }

    function loadInfoPaneData(url)
    {
        // Open the info pane if its not already open
        openInfoPane();

        // Show the spinner
        infoPaneSpinner = addSpinnerToElement($('#info-pane').get(0), '30px', '#448dff');

        // Load info from URL and slide info pane up
        var csrf = $('input#csrf_token').val();
        $.ajax({
            url: "/link/parse",
            cache: false,
            method: 'post',
            data: { url: url, _token: csrf }
        })
            .done(function( response ) {
                $('#info-pane').attr('data-url', url);
                updateInfoPane(response.image, response.title);
                $('#info-pane .image-container').attr('data-image-number', 0);
            });
    }

    function updateInfoPane(image, title)
    {
        var imageContainer = $("#info-pane .image-container");
        var titleContainer = $("#info-pane .title");
        infoPaneSpinner.stop();
        if (image) {
            imageContainer.css("background-image", "url('" + image + "')");
            imageContainer.attr('data-image-url', image);
        }
        if (title) {
            var decoded = $('#info-pane .title-decode').html($.trim(title)).text();
            titleContainer.val(decoded);
        }
        imageContainer.fadeIn('fast');
        titleContainer.fadeIn('fast');
    }

    function openInfoPane(image, title)
    {
        var infoPane = $('#info-pane');
        if (infoPane.attr('data-open') == 'false') {
            infoPane.delay(10).show().velocity({
                top: [ -60, "easeOutCubic" ]
            }, 200, function() {
                $('div.container-header').velocity({
                    marginTop: [ 15, "easeOutElastic"],
                    height: [ 135, "easeOutElastic"]
                }, 200, function() {
                    infoPane.attr('data-open', 'true');
                    if (image != null || title != null) {
                        updateInfoPane(image, title);
                    }
                });
            });
        }
    }

    function closeInfoPane()
    {
        var infoPane = $('#info-pane');
        if (infoPane.attr('data-open') == 'true') {
            $('#info-pane').velocity({
                top: [0, "easeInCubic"]
            }, 100, function () {
                $('div.container-header').velocity({
                    marginTop: [25, "easeInElastic"],
                    height: [125, "easeInElastic"]
                }, 200, function () {
                    infoPane.attr('data-open', 'false');
                });
            });
        }
    }

});
