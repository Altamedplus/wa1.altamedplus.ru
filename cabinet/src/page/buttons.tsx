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
}
const $table = Datatable.get('buttonslist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/buttons/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}