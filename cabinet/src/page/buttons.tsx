import { $, Rocet } from "@rocet/rocet";
import '../event/menu'
import { Datatable } from "@tools/Datatable";
import { integ } from "@rocet/integration";

if ($('form[name="buttons/Add"]').length != 0) {
    const $type = $('select[name=type]');
    $type.on('change', function () {
        let type = $type.val()
        $('[data-type]').each(($el: Rocet) => {
            if ($el.data('type') == type) {
                $el.classList.remove('d-none');
            } else {
                $el.classAdd('d-none');
            }
        });
    })

    initCreateButton()
}
const $table = Datatable.get('buttonslist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/buttons/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}
function initCreateButton() {
    const type = localStorage.getItem('type');
    if (type) {
        $('[name=type]').val(type);
        $('[name=type]').trigger('change');
    }
    localStorage.removeItem('type');

    let url = localStorage.getItem('url');
    if (url) {
        if (url.match(/\{\{[^}]*\}\}/g).length != 0) {
            url = url.replace(/\{\{[^}]*\}\}/g, '')
            $('[name="is_url_postfix"]').attr('checked', '');
        }
        $('[name="url"]').val(url);
    }

    localStorage.removeItem('url')

    const phone = localStorage.getItem('phone');
    if (phone) $('[name="phone"]').val(phone);
    localStorage.removeItem('phone');

    const payload = localStorage.getItem('payload');
    if (payload) $('[name="payload"]').val(payload);
    localStorage.removeItem('payload');

    const text = localStorage.getItem('text');

    if (text) $('[name="text"]').val(text);
    localStorage.removeItem('text');


}
