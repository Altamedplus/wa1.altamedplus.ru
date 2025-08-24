import { $, Rocet } from '@rocet/rocet'
import '../event/menu'
import { Datatable } from '@tools/Datatable';
import { integ } from '@rocet/integration';
import "../css/page/sample.scss"
import { ajax } from '@tools/ajax';

if ($('[name="sample/Add"]').length >= 1) {
    const $form =  $('[name="sample/Add"]')
    const $btn = $form.find('[data-variable-add]');
    $btn.on('click', function () { 
        const $textarea = $('textarea[name="text"]');
        const $variable = $('[data-variable]');
        let val = $textarea.val() ?? '';
        const pos = ($textarea.Elements[0] as any).selectionStart;
        val = val.substring(0, pos) +  `{{${$variable.val()}}}` +  val.substring(pos);
        $textarea.val(val);
    })

    const $headerType = $form.find('select[name=header_type]');
    $headerType.on('change', function () {
        const $ht =  $('[data-header-type]')
        $ht.classAdd('d-none');
        $ht.each(($el: Rocet) => $el.find('input').each(($elm:Rocet)=> $elm.val('')));
        $(`[data-header-type=${$(this).val()}]`).classRemove('d-none');
    })
    initCreateSample();
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

function initCreateSample()
{

        const text = localStorage.getItem('text');
        if (text) $('textarea[name=text]').html(text)
        localStorage.removeItem('text');
        
        const footer = localStorage.getItem('footer');
        if (footer) $('input[name=footer]').val(footer);
        localStorage.removeItem('footer');
    
    
        const HeaderType = localStorage.getItem('headerType');
        if (HeaderType) {
            $('[name="header_type"]').val(HeaderType);
            $('[name="header_type"]').trigger('change');
        }
        localStorage.removeItem('headerType');
    
        const HeaderText = localStorage.getItem('headerText');
        if (HeaderType == 'IMAGE') $('[name="img_url"]').val(HeaderText)
        if (HeaderType == 'TEXT') $('[name="header_text"]').val(HeaderText)
        localStorage.removeItem('headerText');
}

$('[data-load]').on("change", function () { 
    const $file = $(this);
    const nameInput = $file.data('load');
    ajax.send('sample_file',
        {
            file: $file.files[0],
        }).then((data) => {
            const $form = $file.closest('form[name="sample/Add"]');
            $form.find(`input[name=${nameInput}]`).val(data.url)
        });
})