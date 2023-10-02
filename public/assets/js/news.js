$(document).ready(function () {
    const newsForm = $('#news-form');
    const newsFormContainer = newsForm.closest('.form-container');

    // Edit Button on Click.
    $('.edit-btn').click(function () {
        // Get selected News Entry.
        let newsEntry = $(this).closest('.news-entry');

        // Change news form title.
        newsFormContainer.find('.section-title').text('Update News');

        // Change news-form action to update.
        newsForm.attr('action', '/news/' + newsEntry.data('id') + '/update');

        // Update news-form title & content.
        $('#title').val(newsEntry.find('.news-title').text());
        $('#content').val(newsEntry.find('.news-content').text());

        // Show form reset button.
        newsFormContainer.find('.actions').removeClass('hide');

        // Update submit button text.
        newsFormContainer.find('button[type="submit"]').text('Save');

        // Remove alerts.
        removeAlerts();
    });

    // Form reset button on click.
    newsFormContainer.find('.actions button').click(function () {
        // Change news form title.
        newsFormContainer.find('.section-title').text('Create News');

        // Change news-form action to create.
        newsForm.attr('action', '/news');

        // Reset form input values.
        $('#title').val('');
        $('#content').val('');

        // Hide form reset button.
        newsFormContainer.find('.actions').addClass('hide');

        // Update submit button text.
        newsFormContainer.find('button[type="submit"]').text('Create');

        // Remove alerts.
        removeAlerts();
    });

    function removeAlerts()
    {
        // Remove form input and textarea is-invalid classes.
        $('#title').removeClass('is-invalid');
        $('#content').removeClass('is-invalid');
        $('.invalid-feedback').hide();

        // Remove alerts.
        $('.alert').remove();
    }
});