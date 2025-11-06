<?php

use APP\Enum\UsersType;
use APP\Module\Auth;
?>
<header>
    <div class="wrapper">
        <div class="header_block">
            <div class="header_logo">
                <img src="<?= img('logo', 'svg') ?>" />
            </div>
            <div class="system_name">
                Система Быстрых Cообщений
            </div>
            <ul class="link-row">
                <li>
                    <div class="flex-column">
                        <b class="link"><?=(Auth::$profile['name'] ?? '') . ' ' . Auth::$profile['surname'] ?></b>
                        <i><?=UsersType::get(Auth::$profile['type'])?></i>
                    </div>
                </li>
                
                <?
                foreach ($headerLink as $link) : ?>
                    <li><a class="link" href="<?= $link->url ?>" target="_blank"><?= $link->name ?></a></li>
                <? endforeach; ?>
                <ul>
        </div>
        <div class="header_block">
            <div class="header_location">
            </div>
            <div class="header_buttons">
                <? if (isset($headerButtons)): ?>
                    <? foreach ($headerButtons as $but): ?>
                        <? APP\Module\UI\UI::show($but); ?>
                    <? endforeach; ?>
                <? endif ?>

            </div>
        </div>
    </div>
</header>