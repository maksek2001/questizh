<?php

use yii\helpers\Url;
?>

<footer class="footer mt-auto pt-5 text-muted">
    <div class="container">
        <div class="row cols-xs-space cols-sm-space cols-md-space">
            <div class="col-md-4">
                <div class="col">
                    <h4 class="heading footer-title text-uppercase">Контакты</h4>
                    <ul class="footer-links contacts">
                        <li>ИП Иванов Иван Иванович</li>
                        <li>Ижевск — Россия</li>
                        <li>Телефон: <a class="footer-link" href="tel:+7 (999) 888-77-66" target="_blank">+7 (999) 888–77–66</a></li>
                        <li>Email: <a title="Напишите нам" class="footer-link" href="mailto:questizh@gmail.com">questizh@gmail.com</a></li>
                        <li>Юридический&nbsp;адрес: 123456, Россия, Удмуртская республика, г.Ижевск, ул. Удмуртская, д.1, 1</li>
                        <li><a title="Пользовательское соглашение" class="footer-link" href="/">Пользовательское соглашение</a></li>
                        <li><a title="Политика конфиденциальности" class="footer-link" href="/">Политика конфиденциальности</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col">
                    <h4 class="heading footer-title text-uppercase">Пользователям</h4>
                    <ul class="footer-links info">
                        <li><a href="<?= Url::toRoute('help/quest') ?>">Как начать прохождение квеста</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="col">
                    <h4 class="heading footer-title text-uppercase">Мы в социальных сетях</h4>
                    <ul class="footer-links info social-networks">
                        <li>
                            <a href="/">
                                <svg width="20px" height="20px" viewBox="0 0 20 20" fill="currentColor" class="icon social-network-icon" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.802 12.298s1.617 1.597 2.017 2.336a.127.127 0 0 1 .018.035c.163.273.203.487.123.645-.135.261-.592.392-.747.403h-2.858c-.199 0-.613-.052-1.117-.4-.385-.269-.768-.712-1.139-1.145-.554-.643-1.033-1.201-1.518-1.201a.548.548 0 0 0-.18.03c-.367.116-.833.639-.833 2.032 0 .436-.344.684-.585.684H9.674c-.446 0-2.768-.156-4.827-2.327C2.324 10.732.058 5.4.036 5.353c-.141-.345.155-.533.475-.533h2.886c.387 0 .513.234.601.444.102.241.48 1.205 1.1 2.288 1.004 1.762 1.621 2.479 2.114 2.479a.527.527 0 0 0 .264-.07c.644-.354.524-2.654.494-3.128 0-.092-.001-1.027-.331-1.479-.236-.324-.638-.45-.881-.496.065-.094.203-.238.38-.323.441-.22 1.238-.252 2.029-.252h.439c.858.012 1.08.067 1.392.146.628.15.64.557.585 1.943-.016.396-.033.842-.033 1.367 0 .112-.005.237-.005.364-.019.711-.044 1.512.458 1.841a.41.41 0 0 0 .217.062c.174 0 .695 0 2.108-2.425.62-1.071 1.1-2.334 1.133-2.429.028-.053.112-.202.214-.262a.479.479 0 0 1 .236-.056h3.395c.37 0 .621.056.67.196.082.227-.016.92-1.566 3.016-.261.349-.49.651-.691.915-1.405 1.844-1.405 1.937.083 3.337z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="/">
                                <svg width="20px" height="20px" viewBox="0 0 32 32" fill="currentColor" class="icon social-network-icon" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 0.5c-8.563 0-15.5 6.938-15.5 15.5s6.938 15.5 15.5 15.5c8.563 0 15.5-6.938 15.5-15.5s-6.938-15.5-15.5-15.5zM23.613 11.119l-2.544 11.988c-0.188 0.85-0.694 1.056-1.4 0.656l-3.875-2.856-1.869 1.8c-0.206 0.206-0.381 0.381-0.781 0.381l0.275-3.944 7.181-6.488c0.313-0.275-0.069-0.431-0.482-0.156l-8.875 5.587-3.825-1.194c-0.831-0.262-0.85-0.831 0.175-1.231l14.944-5.763c0.694-0.25 1.3 0.169 1.075 1.219z" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="float-left">&copy; <?= Yii::$app->name . ' ' . date('Y') ?></p>
        </div>
    </div>
</footer>