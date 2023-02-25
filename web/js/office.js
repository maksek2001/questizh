$(document).ready(function () {
    $('#contact-info-form').on('beforeSubmit', function () {
        let $form = $(this);
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serializeArray(),
            success: function (result) {
                if (result.success) {
                    $('#team-name').html($('#teaminfoform-name').val());
                    Toast.fire({
                        icon: 'success',
                        text: 'Данные сохранены'
                    });
                } else {
                    Toast.fire({
                        icon: 'error',
                        text: 'Не удалось сохранить данные'
                    });
                }
            },
            error: function () {
                Toast.fire({
                    icon: 'error',
                    text: 'Произошла непредвиденная ошибка'
                });
            }
        });

        return false;
    });
});