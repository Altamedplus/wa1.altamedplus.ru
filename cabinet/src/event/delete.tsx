import { $ } from "@rocet/rocet";
import { ajax } from "@tools/ajax";
$('[evt=delete]').on('click', function () {
    let id = $(this).data('id');
    let table = $(this).data('table');
    let redirect = $(this).data('redirect') || '/';
    ajax.send('delete', {
        id: id,
        table: table
    }).then(() => window.location.href = redirect);
})