<?

use APP\Enum\UsersType;
use APP\Module\Auth;
use APP\Module\Max\Messenger;
?>


<? if (UsersType::SYSADMIN == (int)Auth::$profile['type']) : ?>
    <h2>Информация</h2>
    <? s((new Messenger())->me()); ?>
    <h2>Веб-хуки</h2>
    <? s((new Messenger())->subscriptions()); ?>
    <? if (isset($_GET['hook'])): ?>
        <h3>Установка хука</h3>
        <?= s((new Messenger())->subscriptionsAdd([
            "url" => 'https://wa1.altamedplus.ru/api/max/subscriptions',
            "update_types" => [
                'user_added',
                'bot_added',
                'bot_removed',
                'message_callback',
                'message_removed',
                'message_created',
                'message_edited',
                'bot_started',
                'chat_title_changed',
                'message_chat_created',
                'user_removed',
            ],
            'secret' => '1267iisashasaoUi'
        ])) ?>
    <? endif; ?>
<? else: ?>
    <h1>Страница в разработке</h1>
<? endif; ?>