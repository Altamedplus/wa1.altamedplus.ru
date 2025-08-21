<?php

use Pet\Cookie\Cookie;
?>

<div class="<?=(Cookie::get('menu') == '1' ? "menu menu-hide": "menu")?>">
    <ul>
        <li style="padding-bottom: 5px; padding-top:5px;" evt="btn-roll-up-li">
            <button class="btn btn-roll-up <?=(Cookie::get('menu') == '1' ? "": "btn-roll-up-close")?>"></button>
        </li>
        <?

        use APP\Module\Auth;
        use Enum\UsersType;

        foreach ($menu as $category): ?>

            <a class="link" href="<?= $category->url ?>">
                <li class="">
                    <div class="menu-icon"><? svg($category->icon) ?></div>
                    <span><?= $category->name ?></span>
                </li>
            </a>
        <? endforeach; ?>
    </ul>

    <ul class="in-header">
        <? foreach ($headerLink as $link): ?>
            <a class="link" href="<?= $link->url ?>" target="_blank">
                <li><?= $link->name ?></li>
            </a>
        <? endforeach; ?>

    </ul>
    <div class="block-button">
        <div class="flex-row">
        </div>
    </div>
</div>