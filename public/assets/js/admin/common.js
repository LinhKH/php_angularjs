$(function () {
    // init_app();
    $('#login-form').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this),
                formData = $form.serialize(),
                url = $form.attr('action');
        $form.find('.error').empty();
        $form.find('button').attr('disabled', 'disabled');
        var posting = $.post(url, formData);
        posting.done(function (data) {
            if (data.code === 200) {
                location = baseURL + 'management';
            } else {
                $.each(data.error, function (index, value) {
                    $form.find('.error.' + index).html(value);
                });
                if (typeof data.message !== 'undefined') {
                    $form.find('.error.message').html(data.message);
                }
            }
            $form.find('button').removeAttr('disabled').removeClass('loading');
        });
    });
    $('#forgot-form').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this),
                formData = $form.serialize(),
                url = $form.attr('action');                
        $form.find('.error').empty();
        $form.find('button').attr('disabled', 'disabled');
        var posting = $.post(url, formData);
        posting.done(function (data) {
            if (data.code === 200) {
                location = baseURL + 'auth/confirm/1';
            } else {
                $.each(data.error, function (index, value) {
                    $form.find('.error.' + index).html(value);
                });
                if (typeof data.message !== 'undefined') {
                    $form.find('.error.message').html(data.message);
                }
            }
            $form.find('button').removeAttr('disabled').removeClass('loading');
        });
    });
    $('#new-password-form').on('submit', function (event) {
        event.preventDefault();
        var $form = $(this),
                formData = $form.serialize(),
                url = $form.attr('action');
        $form.find('.error').empty();
        $form.find('button').attr('disabled', 'disabled');
        var posting = $.post(url, formData);
        posting.done(function (data) {
            if (data.code === 200) {
                location = baseURL + 'auth/confirm/2';
            } else {
                $.each(data.error, function (index, value) {
                    $form.find('.error.' + index).html(value);
                });
                if (typeof data.message !== 'undefined') {
                    $form.find('.error.message').html(data.message);
                }
            }
            $form.find('button').removeAttr('disabled').removeClass('loading');
        });
    });
    
});
