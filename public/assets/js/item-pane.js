$( document ).ready(function() {

    $('#itemSettingsModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var type = button.data('type'); // Extract info from data-* attributes
        var value = button.data('value');
        var description = button.data('description');

        var settingsValueTextarea = $('#settingsValue');
        var settingsDescriptionTextarea = $('#settingsDescription');

        var modal = $(this);
        modal.find('.modal-title').text(type + ' Settings');
        settingsValueTextarea.val(value);
        settingsDescriptionTextarea.val(description);

        autosize(settingsValueTextarea);
        autosize(settingsDescriptionTextarea);
    });

});