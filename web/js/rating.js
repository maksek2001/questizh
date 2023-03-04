/**
 * Загрузка рейтинга
 * 
 * @param {number} questId 
 * @param pageElement элемент страницы, в который будет загружаться рейтинг 
 */
function loadRating(questId, pageElement) {
    pageElement.html('');
    pageElement.addClass('load');
    if (questId) {
        $.ajax({
            url: "load-rating",
            type: 'GET',
            data: { 'questId': questId },
            dataType: 'html',
            success: function (result) {
                pageElement.removeClass('load');
                pageElement.html(result);
                showHideTeamPosition();
            },
            error: function () {
                pageElement.removeClass('load');
                Toast.fire({
                    icon: 'error',
                    text: 'Произошла непредвиденная ошибка'
                });
            }
        });
    } else {
        pageElement.removeClass('load');
        pageElement.html('<div class="alert alert-warning">Квест не выбран</div>');
    }
}

function showHideTeamPosition() {
    let $positionElement = $('.current-position');
    if ($(".current-team:in-viewport").length == 0) {
        $positionElement.fadeIn();
    } else {
        $positionElement.fadeOut();
    }
}

$(document).ready(function () {
    let $ratingResults = $('#rating-results');
    let $questSelect = $('#quest-select');

    if ($questSelect) {
        let questId = $questSelect.val();
        loadRating(questId, $ratingResults);

        $questSelect.on('change', function () {
            loadRating($(this).val(), $ratingResults);
        });
    }

    $(document).scroll(showHideTeamPosition);
    $(window).resize(showHideTeamPosition);

    $('body').on('click', '.help-link', function () {
        let dataType = $(this).attr('data-type');
        $(`.help .alert.${dataType}`).fadeToggle();
    });
});
