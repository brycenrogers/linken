//  ====================  Add Pane JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     September 16, 2015

//    This file contains javascript for the base Add pane of Linken

//  ===========================================================

$( document ).ready(function() {

    var addButtonSpinner;
    var infoPaneSpinner;

    autosize($('textarea#add'));
    autosize($('textarea#add-description'));

    $('div#blue-hitbox-add-pane').click(function() {
        $('textarea#add').focus();
    });
    $('textarea#add').focus(function() {
        $(this).attr('placeholder', '');
        openAddPane();
        triggerInfoPane();
    });
    $('textarea#add').change(function() {
        // Load the info pane if necessary
        triggerInfoPane();
    });
    $('select#add-tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: "Tags"
    });
    $('div#add-fader').click(function() {
        closeAddPane();
    });
    $('button#add-button').click(function() {
        var button = $(this);
        //button.prop("disabled", true);
        button.html('&nbsp;');

        // Show the spinner
        addButtonSpinner = addSpinnerToElement(button.get(0));

        // Gather form data
        var csrf = $('input#csrf_token').val();
        var value = $('textarea#add').val();
        var description = $('textarea#add-description').val();
        var type = "Note";
        if ($('div#info-pane').attr('data-url') != "") {
            type = "Link";
        }
        var tags = "";
        $("select#add-tags option:selected").each(function() {
            tags += $( this ).text() + "|";
        });

        $.ajax({
            url: "/item/store",
            cache: false,
            method: 'post',
            data: { value: value, description: description, type: type, tags: tags, _token: csrf }
        })
            .done(function( response ) {
                closeAddPane();
                addButtonSpinner.stop();
                addButtonSpinner = null;
                button.html('Add');
                button.prop("disabled", false);
            });
    });
    $('textarea#add-description').on('autosize:resized', function() {
        adjustPaneHeight();
    });
    $('select#add-tags').on("change", function (e) {
        adjustPaneHeight();
    });
    function triggerInfoPane()
    {
        var entry = $.trim($('textarea#add').val());
        var foundUrls = entry.match(/(([a-z]{3,6}:\/\/)|(^|\s))([a-zA-Z0-9\-]+\.)+[a-z]{2,13}[\.\?\=\&\%\/\w\-]*\b([^@]|$)/mg);
        var isUrl = false;
        var infoPaneOpen = $('div#info-pane').attr('data-open');
        if(foundUrls && foundUrls.length > 0) {
            isUrl = true;
            var url = $.trim(foundUrls[0]);

            // If data has already been loaded for this url, just open
            var sameUrl = true;
            var loadedUrl = $('div#info-pane').attr('data-url');
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
        var addPaneNewHeight = parseInt(containerHeight) + 33;
        $('div#add-pane').css('min-height', addPaneNewHeight + 'px');
    }

    function openAddPane()
    {
        if ($('div#add-pane').attr('data-toggle') == 'open') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'open');
        var height = 160;
        if($('input#add-pane-height').val() != '') {
            height = $('input#add-pane-height').val();
        }
        $('div#add-fader').show().velocity({
            opacity: 0.25
        }, 200, function() {
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

                        });
                    });
                });
            });
        });
    }

    function closeAddPane()
    {
        if ($('div#add-pane').attr('data-toggle') == 'closed') {
            return;
        }
        $('div#add-pane').attr('data-toggle', 'closed');

        // Store the height so we can reopen correctly
        var heightPx = $('div#add-pane').css('height');
        var height = heightPx.split('px');
        $('input#add-pane-height').val(height[0]);

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
                });
            });
        });
    }

    function loadInfoPaneData(url)
    {
        // Open the info pane if its not already open
        openInfoPane();

        // Show the spinner
        infoPaneSpinner = addSpinnerToElement($('div#info-pane').get(0), '30px', '#448dff');

        // Load info from URL and slide info pane up
        var csrf = $('input#csrf_token').val();
        $.ajax({
            url: "/link/parse",
            cache: false,
            method: 'post',
            data: { url: url, _token: csrf }
        })
            .done(function( response ) {
                $('div#info-pane').attr('data-url', url);
                updateInfoPane(response.image, response.title);
            });
    }

    function updateInfoPane(image, title)
    {
        infoPaneSpinner.stop();
        $("#info-image-container").css("background-image", "url('" + image + "')").fadeIn('fast');
        $("#info-title-container").html(title).fadeIn('fast');
    }

    function openInfoPane(image, title)
    {
        var infoPane = $('div#info-pane');
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
        var infoPane = $('div#info-pane');
        if (infoPane.attr('data-open') == 'true') {
            $('div#info-pane').velocity({
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

    function addSpinnerToElement(element, left, color)
    {
        var opts = {
            lines: 13 // The number of lines to draw
            , length: 5 // The length of each line
            , width: 3 // The line thickness
            , radius: 8 // The radius of the inner circle
            , scale: 1 // Scales overall size of the spinner
            , corners: 1 // Corner roundness (0..1)
            , color: '#fff' // #rgb or #rrggbb or array of colors
            , opacity: 0.25 // Opacity of the lines
            , rotate: 0 // The rotation offset
            , direction: 1 // 1: clockwise, -1: counterclockwise
            , speed: 1 // Rounds per second
            , trail: 60 // Afterglow percentage
            , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
            , zIndex: 2e9 // The z-index (defaults to 2000000000)
            , className: 'spinner' // The CSS class to assign to the spinner
            , top: '50%' // Top position relative to parent
            , left: '50%' // Left position relative to parent
            , shadow: false // Whether to render a shadow
            , hwaccel: true // Whether to use hardware acceleration
            , position: 'absolute' // Element positioning
        };

        if (color) {
            opts.color = color;
        }

        if (left) {
            opts.left = left;
        }

        var spinner = new Spinner(opts).spin(element);

        return spinner;
    }

});
