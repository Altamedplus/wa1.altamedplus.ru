import { $ } from '@rocet/rocet'
import '../event/menu'
import './../css/page/setting.scss'
import { Cookie } from '@src/Tools/Cookie';

$('input[name="clearBuf"]').on('click', (ev:MouseEvent) => {
    if ($(ev.target).checked) {
        Cookie.set('clear-buf', '1');
        if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText('');
        }
    } else { 
        Cookie.delete('clear-buf');
    }
});