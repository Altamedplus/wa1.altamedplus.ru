<?php
namespace APP\Module;
class Telegramm
{
    const API = '7614191279:AAF45Sh5zQwNYLmTHAkhjSasFU0ZpcciOs8';
    const BOT = '@MastersUnit_bot';

    function Include_script()
    {
        return `<script async src="https://telegram.org/js/telegram-widget.js?15"
        data-telegram-login="` . self::BOT . `"
        data-size="large"
        data-userpic="false"
        data-auth-url="https://yourdomain.com/auth.php"
        data-request-access="write">
        </script>`;
    }
}