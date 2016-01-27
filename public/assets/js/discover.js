/**
 * Discover Page Javascript
 */

if ($('#discover-tags').length) {
    $('select#discover-tags').select2({
        placeholder: "Discover by Tag",
        ajax: {
            url: '/tags/search?scope=all',
            delay: 100,
            processResults: function (data) {
                return {
                    results: data.items
                    };
            }
        }
    });

    $('#discover-tags-submit').on("click", function(e) {

        // Disable the button
        $(this).prop('disabled', true);

        // Get tags from select2
        var tags = [];
        $("#discover-tags option").each(function() {
            // Replace spaces with underscores for URL encoding
            var tagVal = $(this).val();
            tagVal = tagVal.replace(" ", "_");
            tags.push(tagVal);
        });

        // Build string of tags for GET request
        var tagString = tags.join('+');

        // Update the href and go
        var href = $(this).attr('href');
        var newHref = href.concat(tagString);
        $(this).attr('href', newHref);
    });
}