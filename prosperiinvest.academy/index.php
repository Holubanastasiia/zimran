<?php
function detectBrowserLanguage(string $default = 'en'): string
{
    $lang_header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';

    if (!$lang_header) {
        return $default;
    }

    $languages = explode(',', $lang_header);

    return substr($languages[0], 0, 2);
}

;
$language = detectBrowserLanguage();
$domain = 'message';

putenv("LANG=$language");
putenv("LANGUAGE=$language");

setlocale(LC_ALL, $language);
textdomain($domain);
bindtextdomain($domain, './locales');
bind_textdomain_codeset($domain, 'UTF-8');

?>
<!DOCTYPE html>
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="preload" as="image" href="./assets/female.png">
    <link rel="preload" as="image" href="./assets/male.png">
    <link rel="stylesheet" href="scss/reset.css">
    <link rel="stylesheet" href="scss/style.css">
    <title>Prosperi</title>
    <style>
        .app-loader {
            align-items: center;
            background: #FFFFFF;
            display: flex;
            height: 100vh;
            overflow: hidden;
            position: fixed;
            width: 100%;
            z-index: 100;
        }

        .app-loader img,
        .appLoader .loaderAnimationLetter {
            animation: loaderPulseAnimation 1s infinite;
            left: 0;
            margin: 0 auto;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20%;
        }

        @keyframes loaderPulseAnimation {
            30% {
                transform: translateY(-50%) scale(1)
            }

            50% {
                transform: translateY(-50%) scale(1.2)
            }
        }

        .display_none {
            display: none;
        }
    </style>
</head>

<body>
<div class="app-loader"><img class="loaderAnimationLetter" alt="loader" id="loader-img"
                             src="./assets/preloader.svg">
</div>
<div class="wrapper display_none">
    <div class="container">
        <div class="header">
            <div class="header__wrapper">
                <div class="header__inner">
                    <img alt="Prosperi" src="./assets/logo.svg" width="134" height="32" decoding="async"
                         style="color:transparent">
                    <div class="header__flex">
                        <img src="./assets/question_icon.webp" class="questionButton" alt="question_icon" width="24"
                             height="24">
                        <a href="https://app.prosperi.academy/auth/signin" class="start-button">
                                    <span>
                                        <?php echo gettext('log in'); ?>
                                    </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="hiddenBg hiddenQuestion display_none">
        </div>
        <div class="display_none hiddenHint hiddenQuestion">
            <div class="hint-wrapper">
                <div class=" hint-inner min-h-[400px]">
                    <img alt="" src="./assets/icon-cross.svg" width="24" height="24"
                         decoding="async" data-nimg="1" loading="lazy" style="color: transparent;" class="close">
                    <p><?php echo gettext('Need some help?') ?></p>
                    <div>
                        <p class="text"><?php echo gettext('We will be glad to assist you. Please send your questions and feedback to') ?></p>
                        <a
                                href="mailto:support@prosperi.io">support@prosperi.io</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="main">
            <div class="main__inner">
                <div class="main__inner-text">
                    <h1><?php echo gettext('Unlock Your Passive Income Potential'); ?></h1>
                    <p><?php echo gettext('Select your gender:'); ?></p>
                </div>
                <div class="main__buttons">
                    <a href="#" class="start-button">
                        <div class="main__buttons-item"
                             style="background-image:url('./assets/blue-arch.svg')">
                            <div class="main__buttons-item-img"
                                 style="background-image:url('./assets/male.png')">
                            </div>
                            <button
                                    class="male main__buttons-item-button">
                                <span><?php echo gettext('male'); ?></span>
                                <div class="arrow"
                                     style="background-image:url('./assets/chevron-right.svg')"></div>
                            </button>
                        </div>
                    </a>
                    <a href="#" class="start-button">
                        <div class="main__buttons-item"
                             style="background-image: url('./assets/green-arch.svg');">
                            <div class="main__buttons-item-img"
                                 style="background-image: url('./assets/female.png');">
                            </div>
                            <button
                                    class="female main__buttons-item-button">
                                <span><?php echo gettext('female'); ?></span>
                                <div class="arrow"
                                     style="background-image: url('./assets/chevron-right.svg');"></div>
                            </button>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="footer__inner">
                <div class="space-y-2">
                    <p> <?php echo gettext('By clicking "Male" or "Female", you agree with'); ?> &nbsp;<a
                                target="_blank"
                                href="https://legal.prosperi.academy/terms">Terms and Conditions</a>,&nbsp;<a
                                target="_blank" href="https://legal.prosperi.academy/privacy">Privacy
                            Policy</a>,&nbsp;<a target="_blank"
                                                href="https://legal.prosperi.academy/subscription">Subscription
                            Terms</a></p>
                    <p>ZIMRAN LIMITED, Limassol, Cyprus</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const loader = document.querySelector('.app-loader');
    const mainFirstScreen = document.querySelector(".wrapper");
    const questionButton = document.querySelector(".questionButton");
    const modalWindow = document.getElementsByClassName("hiddenQuestion");
    const closeBtn = document.querySelector(".close");
    const maleBtn = document.querySelector(".male");
    const femaleBtn = document.querySelector(".female");

    document.addEventListener('onload', hideLoader());

    function hideLoader() {
        setTimeout(() => {
            loader.classList.add("display_none");
            mainFirstScreen.classList.remove("display_none");

        }, 1000)
    }

    questionButton.addEventListener("click", showModal);
    closeBtn.addEventListener("click", hideModal);

    function showModal() {
        for (let i = 0; i < modalWindow.length; i++) {
            modalWindow[i].classList.remove("display_none");
        }
    }

    function hideModal() {
        for (let i = 0; i < modalWindow.length; i++) {
            modalWindow[i].classList.add("display_none");
        }
    }

</script>
</body>

</html>