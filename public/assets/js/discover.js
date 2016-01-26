/**
 * Discover Page Javascript
 */

if ($('#discover-tags').length) {
    $('select#discover-tags').select2({
        tags: true,
        tokenSeparators: [','],
        placeholder: "Tags",
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
}