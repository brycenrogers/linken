//  ====================  Add Pane JS  ====================

//    created by:        Brycen Rogers ( brycenrogers@gmail.com )
//    creation data:     September 16, 2015

//    This file contains javascript for the base Add pane of Linken

//  ===========================================================

$( document ).ready(function() {

    autosize($('textarea#add'));
    autosize($('textarea#add-description'));

    $('div#blue-hitbox-add-pane').click(function() {
        $('textarea#add').focus();
    });
    $('textarea#add').focus(function() {
        $(this).attr('placeholder', '');
        openAddPane();
    });
    $('textarea#add').change(function() {
        if($.trim($(this).val()) == "") {
            closeInfoPane();
        } else {
            openInfoPane();
        }
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
        closeAddPane();
    });
    $('textarea#add-description').on('autosize:resized', function() {
        adjustPaneHeight();
    });
    $('select#add-tags').on("change", function (e) {
        adjustPaneHeight();
    });

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
        var height = 206;
        if($('input#add-pane-height').val() != '') {
            height = $('input#add-pane-height').val();
        }
        $('div#add-fader').show().velocity({
            opacity: 0.25
        }, 200, function() {
            var addPaneOffset = $('div#add-pane').offset();
            var inputAddOffset = $('input#add').offset();
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
                            if($.trim($('textarea#add').val()) != "") {
                                openInfoPane();
                            }
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

    function openInfoPane()
    {
        $('div#info-pane').show().velocity({
            top: [ -60, "easeOutCubic" ]
        }, 200, function() {
            $('div.container-header').velocity({
                marginTop: [ 15, "easeOutElastic"],
                height: [ 135, "easeOutElastic"]
            }, 200, function() {});
        });
    }

    function closeInfoPane()
    {
        $('div#info-pane').velocity({
            top: [ 0, "easeInCubic"]
        }, 100, function() {
            $('div.container-header').velocity({
                marginTop: [ 25, "easeInElastic"],
                height: [ 125, "easeInElastic"]
            }, 200, function() {});
        });
    }

});
