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
        autosize($('textarea#add-tags'));
        openAddPane(function () {
            triggerInfoPane();
        });
        $('input.select2-search__field').prop('tabindex', "3");
    });
    $('textarea#add').change(function() {
        // Load the info pane if necessary
        triggerInfoPane();
    });

    // Welcome Page listeners

    $('div#welcome-blue-hitbox').click(function() {
        $('textarea#add-welcome').focus();
    });

    $('textarea#add-welcome').focus(function() {
        $(this).attr('placeholder', '').val("wikipedia.org/wiki/Abraham_Lincoln");
        autosize($('textarea#add'));
        autosize($('textarea#add-description'));
        autosize($('textarea#add-tags'));
        openAddPane();
        welcomeOpenInfoPane(
            '/assets/images/abraham-wiki-portrait.jpg',
            'Abraham Lincoln - Wikipedia, the free encyclopedia'
        );
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
        var discovery_setting = $('#discovery-setting').val();

        if ($('#info-pane').attr('data-url') != "") {
            type = "Link";
            value = $('#info-pane .title').val();
            url = $('#info-pane').attr('data-url');
            photo_url = $('#info-pane .image-container').attr('data-image-url');
            title = $('#info-pane .title').val();
        }

        var tags = $("#add-tags").val();

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
                discovery_setting: discovery_setting,
                type: type,
                tags: tags,
                _token: csrf
            }
        })
        .error(function (response) {
            // Get response text
            var responseArray = $.parseJSON(response.responseText);
            var responseVal = responseArray.value;
            addButtonSpinner.stop();
            addButtonSpinner = null;
            button.html('Add');
            button.prop("disabled", false);
            showAlert('error', responseVal);
        })
        .success(function(response) {
            closeAddPane(response);
            addButtonSpinner.stop();
            addButtonSpinner = null;
            button.html('Add');
            button.prop("disabled", false);
            clearAddPane();
        });
    });

    $('button#welcome-add-button').click(function() {
        var button = $(this);
        button.prop("disabled", true);
        button.html('&nbsp;');

        // Show the spinner
        addButtonSpinner = addSpinnerToElement(button.get(0));

        // Close the pane and show alert
        window.setTimeout(function() {
            closeAddPane(true);
            addButtonSpinner.stop();
            addButtonSpinner = null;
            button.html('Add');
            button.prop("disabled", false);
        }, 500);
    });

    $('textarea#add-description').on('autosize:resized', function() {
        adjustPaneHeight();
    });
    $('textarea#add-tags').on('autosize:resized', function() {
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
        var discovery_setting = $(this).val();
        changeDiscoveryOptionDisplay(discovery_setting);
    });
    $("#discoverable-checkbox").click(function() {
        var checked = $(this).prop('checked');
        if (checked) {
            var discovery_setting = $('#discovery-setting-default').val();
            if (discovery_setting != 'off') {
                $("input[name=discoverable-setting][value=" + discovery_setting + "]").prop('checked', true);
                changeDiscoveryOptionDisplay(discovery_setting);
            } else {
                $("input[name=discoverable-setting][value=attributed]").prop('checked', true);
                changeDiscoveryOptionDisplay('attributed');
            }
        } else {
            changeDiscoveryOptionDisplay('off');
        }
    });
    $('#show-settings-modal-link').click(function(e){
        e.preventDefault();
        $('#discoverable-options-modal')
            .modal('hide')
            .on('hidden.bs.modal', function (e) {
                $('#user-settings-modal').modal('show');
                $(this).off('hidden.bs.modal');
            });
    });
    function clearAddPane()
    {
        $('textarea#add').html("");
        $('textarea#add-description').html("");
        $("textarea#add-tags").html("");
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
        var addPaneNewHeight = parseInt(containerHeight) + 26;
        $('div#add-pane').css('min-height', addPaneNewHeight + 'px');
        $('input#add-pane-height').val(addPaneNewHeight);
    }

    function openAddPane(callback)
    {
        if ($('div#blue-hitbox-add-pane').hasClass('flash')) {
            revertHitboxStyle();
        }
        if ($('div#add-pane').attr('data-toggle') == 'open') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'open');

        var windowWidth = $(window).width();
        var height = 153;

        if (windowWidth <= 767) {
            height = 210;
        }

        if($('input#add-pane-height').val() != '') {
            height = $('input#add-pane-height').val();
        }
        $('div#add-pane').show().velocity({
            minHeight: [ height, "easeOutCubic" ]
        }, 200, function() {
            $('div#add-fader').show().velocity({
                opacity: 0.25
            }, 300, function() {
                $('div#add-pane textarea').velocity({
                    opacity: 1
                }, 200, function() {
                    $('div#add-pane button').velocity({
                        opacity: 1
                    }, 200, function() {
                        $('div#add-fader').on("click", function() {
                            closeAddPane();
                        });
                        if ($('#info-pane').data('open') && $('#add').length) {
                            $('#discovery-options-container').fadeIn('fast');
                        }
                        if (callback) {
                            callback();
                        }
                    });
                });
            });
        });
    }

    function closeAddPane(newItemData)
    {
        // If we are in the welcome pane, stop the typing timeout
        if ($('#add-welcome').length) {
            $('#add-welcome').val('').attr('placeholder', 'Add URL or Note');
        }

        $('div#add-fader').off("click");
        if ($('div#add-pane').attr('data-toggle') == 'closed') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'closed');

        // Store the height so we can reopen correctly
        var heightPx = $('div#add-pane').css('height');
        var height = heightPx.split('px');
        $('input#add-pane-height').val(height[0]);

        // Hide discovery options
        $('#discovery-options-container').fadeOut('fast');

        // Fade out fields and slide up
        $('div#add-pane textarea, div#add-pane .select2, div#add-pane button').velocity({
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
                        showAlert('success', 'Saved!');
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
        var height = 0;
        var addPaneHeight = $('input#add-pane-height').val();
        if (addPaneHeight == '') {
            if ($(window).width() <= 767) {
                if (height == 0) {
                    height = 260;
                }
                $('div#add-pane').show().velocity({
                    minHeight: [ height, "easeOutCubic" ]
                }, 200);
            }
            if ($(window).width() >= 768) {
                if (height == 0) {
                    height = 176;
                }
                $('div#add-pane').show().velocity({
                    minHeight: [ height, "easeOutCubic" ]
                }, 200);
            }
        }
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
                    $('#discovery-options-container').fadeIn('fast');
                });
            });
        }
    }

    function closeInfoPane()
    {
        $('#discovery-options-container').fadeOut('fast');
        var infoPane = $('#info-pane');
        if (infoPane.attr('data-open') == 'true') {
            if ($(window).width() <= 767 && $('div#add-pane').css('height') == '260px') {
                height = 210;
                $('div#add-pane').show().velocity({
                    minHeight: [ height, "easeOutCubic" ]
                }, 200);
            }
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

    function changeDiscoveryOptionDisplay(discovery_setting) {
        $('.scope span').each(function() {
            $(this).attr('class', 'hide');
        });
        $('#discoverable-' + discovery_setting + '-display').attr('class', 'show');
        if (discovery_setting == 'off') {
            $('.scope').attr('data-toggle', 'false');
        } else {
            $('.scope').attr('data-toggle', 'modal');
        }
        $('#discovery-setting').val(discovery_setting);
    }

    // Welcome Hitbox functions

    function welcomeOpenInfoPane(image, title)
    {
        var infoPane = $('#info-pane');
        if (infoPane.attr('data-open') == 'false') {
            infoPane.delay(10).show().velocity({
                top: [ -60, "easeOutCubic" ]
            }, 200, function() {
                infoPane.attr('data-open', 'true');
                var imageContainer = $("#info-pane .image-container");
                var titleContainer = $("#info-pane .title");
                imageContainer.css("background-image", "url('" + image + "')");
                imageContainer.attr('data-image-url', image);
                var decoded = $('#info-pane .title-decode').html($.trim(title)).text();
                titleContainer.val(decoded);
                imageContainer.fadeIn('fast');
                titleContainer.fadeIn('fast');
            });
        }
    }

});
