import { $ } from '@rocet/rocet'
import '../event/menu'
import { ajax } from '@tools/ajax'
import { Datatable } from '@tools/Datatable';
import { integ } from '@rocet/integration';

const $table = Datatable.get('licenselist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/license/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}
$('[evt="license_file"]').on('change', function () { 
    ajax.send('license_file', { file: $(this).files[0] }).then((data: any) => { 
        $('input[name="url_file"]').val(data.url);
    });
})