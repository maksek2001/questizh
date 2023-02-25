/**
 * Функция форматирования времени
 * 
 * @param {number} seconds время в секундах
 * @returns {string}
 */
function secondsToTimeString(seconds) {
    let timeString = '';

    let hours = Math.floor(seconds / 3600);
    let minutes = Math.floor(seconds / 60 - hours * 60);
    seconds = seconds - hours * 3600 - minutes * 60;

    if (hours > 0)
        timeString += `${hours} ч. `;

    if (minutes > 0)
        timeString += `${minutes} мин. `;

    if (seconds > 0)
        timeString += `${seconds} сек. `;

    return timeString;
}

/**
 * Получение текущих результатов прохождения квеста и запись их в объект
 * 
 * @param {object} questPassingInfo 
 */
function getQuestPassingInfo(questPassingInfo) {
    $.ajax({
        url: "get-quest-passing-info",
        type: 'GET',
        data: { 'quest_id': questPassingInfo.questId },
        success: function (result) {
            // перевод в миллисекунды для удобства дальнейшего использования
            questPassingInfo.submitTimeout = result.submitTimeout * 1000;

            result.futureHints.forEach(function (elem) {
                questPassingInfo.futureHints.push({
                    'id': elem.id,
                    'remainingTime': elem.remainingTime
                });
            });
        },
        error: function () {
            Toast.fire({
                icon: 'error',
                text: 'Произошла непредвиденная ошибка'
            });
        }
    })
}

/**
 * Отображение заметки
 * 
 * @param {number} hintId 
 */
function showHint(hintId) {
    $.ajax({
        url: "show-hint",
        type: 'GET',
        data: { 'id': hintId },
        dataType: 'html',
        success: function (result) {
            $('#hints-block').append(result);
            let $hintLink = $(`#hint-link-${hintId}`);
            $hintLink.removeClass('not-available');
            $hintLink.addClass('available');
        },
        error: function () {
            Toast.fire({
                icon: 'error',
                text: 'Произошла непредвиденная ошибка'
            });
        }
    })
}

/**
 * Таймер, к которому привязаны заметки и завершение квеста
 * 
 * @param {object} questPassingInfo 
 */
function timer(questPassingInfo) {
    if (questPassingInfo.futureHints.length > 0) {
        if (--questPassingInfo.futureHints[0].remainingTime == 0) {
            showHint(questPassingInfo.futureHints[0].id);
            questPassingInfo.futureHints.shift();
            if (questPassingInfo.canOpenNewSwal) {
                Toast.fire({
                    icon: 'info',
                    text: 'Вам доступна новая подсказка'
                });
            }
        }
    }

    $.ajax({
        url: "timer",
        type: 'GET',
        data: { 'quest_id': questPassingInfo.questId },
        cache: false,
        success: function (result) {
            if (result.remainingTime > 0) {
                $("#timer").html('Оставшееся время: ' + secondsToTimeString(result.remainingTime));
            } else {
                window.location = `result?quest_id=${questPassingInfo.questId}`;
            }
        }
    });
}

$(document).ready(function () {
    window.scrollTo(0, 0);

    const questPassingInfo = {
        questId: $('#questtaskform-questid').val(),
        submitTimeout: 0,
        futureHints: [],
        canOpenNewSwal: true
    };

    getQuestPassingInfo(questPassingInfo);

    setInterval(timer, 1000, questPassingInfo);

    let $message = $('.message');

    $('.hints-selection').on('click', '.hint-link.available', function () {
        let hintId = $(this).attr('data-id');

        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
            $(`#hint-${hintId}`).fadeOut();
        } else {
            $('.hint-link.available').removeClass('active');
            $('.hint').hide();

            $(`#hint-${hintId}`).fadeIn('hidden');
            $(this).addClass('active');
        }
    });

    $('.exit').on('click', function (e) {
        e.preventDefault();

        questPassingInfo.canOpenNewSwal = false;
        BootstrapSwal.fire({
            text: 'Вы действительно хотите завершить выполнение квеста? Следующая попытка не будет учитываться в рейтинге!',
            confirmButtonText: 'Да',
            denyButtonText: 'Нет',
            icon: 'question',
            showDenyButton: true,
            showCloseButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.location.href = $(this).attr('href');
            }
            questPassingInfo.canOpenNewSwal = true;
        });
    });

    $('#quest-task-form').on('beforeSubmit', function () {
        $message.html('');

        let $form = $(this);
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serializeArray(),
            success: function (result) {
                if (result.success) {
                    questPassingInfo.canOpenNewSwal = false;
                    BootstrapSwal.fire({
                        icon: 'success',
                        text: result.message,
                        willClose: () => {
                            window.location.reload();
                        }
                    });
                } else {
                    let message = `<div class="alert alert-danger">${result.message}</div>`;
                    $message.html(message);

                    if (questPassingInfo.submitTimeout > 0) {
                        questPassingInfo.canOpenNewSwal = false;
                        $('#check-answer').prop('disabled', true);
                        Toast.fire({
                            icon: 'info',
                            timer: questPassingInfo.submitTimeout,
                            html: ' Следующая попытка будет доступна через <span>' + (questPassingInfo.submitTimeout / 1000) + '</span> сек.',
                            didOpen: () => {
                                const time = Swal.getHtmlContainer().querySelector('span');
                                setInterval(() => {
                                    let currentTime = Swal.getTimerLeft() ? Swal.getTimerLeft() : 0;
                                    time.textContent = Math.ceil(currentTime / 1000);
                                }, 1000)
                            },
                            willClose: () => {
                                $('#check-answer').prop('disabled', false);
                                questPassingInfo.canOpenNewSwal = true;
                            }
                        });
                    }
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