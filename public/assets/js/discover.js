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
}