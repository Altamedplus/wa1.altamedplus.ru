import { $ } from "@rocet/rocet";
import { ajax } from "@tools/ajax";

export function initBtnModal() { 
    $('[data-modal]').on('click', function () {
        const $btn = $(this)
        ajax.modalOpen({
            template: $btn.data('template'),
            header: $btn.data('header'),
            form_name: $btn.data('form'),
            id: $btn.data('id'),
        })
    });
}
initBtnModal();