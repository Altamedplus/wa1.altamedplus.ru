import { $, Rocet } from '@rocet/rocet'
import '../event/menu'
import { Datatable } from '@tools/Datatable';
import { integ } from '@rocet/integration';

if ($('[name="sample/Add"]').length >= 1) {
    const $form =  $('[name="sample/Add"]')
    const $btn = $form.find('[data-variable-add]');
    $btn.on('click', function () { 
        const $textarea = $('textarea[name="text"]');
        const $variable = $('[data-variable]');
        const val = $textarea.val() ?? '';
        $textarea.val(val + `{{${$variable.val()}}}`)
    })

    const $headerType = $form.find('select[name=header_type]');
    $headerType.on('change', function () {
        const $ht =  $('[data-header-type]')
        $ht.classAdd('d-none');
        $ht.each(($el: Rocet) => $el.find('input').each(($elm:Rocet)=> $elm.val('')));
        $(`[data-header-type=${$(this).val()}]`).classRemove('d-none');
    })
}

const $table = Datatable.get('samplelist');

if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/sample/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
    $table.buildCells = (row: any, alias: any, indexRow: any, indexElem: any) => {
        if (alias == 'text') {
            return <td innerHTML={row[alias]}></td>
        }
    }
}