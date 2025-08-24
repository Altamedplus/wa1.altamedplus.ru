import { Datatable } from "@tools/Datatable";
import '../event/menu'
import { $, Rocet } from '@rocet/rocet';
const $table:Datatable|null = Datatable.get('messagelist');
if ($table) {
    $table.initCallback = function () { 
        const input = $('[name="filter"]').find('[name="message.id"]');
        input.on('change', function () {
            $table.setUrlParam('id', ($(this).value || null));
        })
    }
}