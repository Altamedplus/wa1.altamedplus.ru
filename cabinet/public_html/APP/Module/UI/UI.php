<?php

namespace APP\Module\UI;

class UI {

    public static function show($el): void {
        $tag = $el['tag'];
        $textContent = $el['textContent'] ?? "";
        unset($el['tag'], $el['textContent']);
        ?>
        <<?= $tag ?>
            <? foreach ($el as $name => $attr) : ?>
            <?= $name ?>="<?= $el[$name] ?>"
            <? endforeach ?>>
            <?= $textContent ?>
        </<?= $tag ?>>
    <?php
    }
    public static function showStr(array $el): string
    {
        $obj = (object) $el;
        $tag = $obj?->tag;
        $attr = '';
        unset($el['tag'], $el['textContent']);
        foreach ($el as $name => $value) {
            $attr .= "$name='$value' ";
        }
        return "<{$obj?->tag}  $attr >{$obj?->textContent}</{$obj?->tag}>";
    }
}
