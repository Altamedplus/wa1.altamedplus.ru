<?

use APP\Module\UI\UI;

include __DIR__ . '/header.php' ?>
<div class="content">
    <div class="wrapper">
        <? include __DIR__ . "/menu.php" ?>
        <div class="desktop-wrapper">
            <? view('loader') ?>
            <? if (!empty($buttonsHeader)): ?>
                <div class="buttons-header-desktop">
                    <? foreach ($buttonsHeader as $but) : ?>
                        <? UI::show($but); ?>
                    <? endforeach; ?>
                </div>
            <? endif ?>
            <? include __DIR__ . '/page' . ($desktop ?? 'desktop.php')  ?>
        </div>
    </div>
</div>
<? include __DIR__ . '/footer.php' ?>