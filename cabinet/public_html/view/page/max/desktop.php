<?

use APP\Enum\UsersType;
use APP\Module\Auth;
use APP\Module\Max\Messenger;
?>


<? if (UsersType::SYSADMIN == (int)Auth::$profile['type']) : ?>
    <div class="flex-column">
        <h2>Информация</h2>
        <div>
            <img src="<?= $me['full_avatar_url'] ?>"></img>
        </div>
        <h3><?= $me['name'] ?></h3>
        <b class="fs-13"><?= $me['username'] ?></b>
        <i class="fs-13"><?= $me['description'] ?></i>
    </div>
    <div class="flex-column">
        <h2>Веб-хуки</h2>
        <? foreach ($hook['subscriptions'] as $ho) : ?>
            <div class="flex-column">
                <div><b>URL</b> <i><?= $ho['url'] ?></i></div>
                <div class="fs-13"><b>TYPE</b> <i><?= implode(', ', $ho['update_types']) ?></i></div>
                <a href="?delete=<?= $ho['url'] ?>">Отписатьcя</a>
            </div>
            <br />
        <? endforeach; ?>
        <form action="?created_hook" class="flex-column">
            <input type="hidden" name="create_hook" value="1"></input>
            <input type="text" name="url" placeholder="url"></input>
            <input type="text" name="secret" placeholder="secret"></input>
            <select multiple name="event">
                <? foreach ($type as $v) : ?>
                    <option><?=  $v?></option>
                <? endforeach; ?>
            </select>
            <button>Создать хук</button>
        </form>
    </div>
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