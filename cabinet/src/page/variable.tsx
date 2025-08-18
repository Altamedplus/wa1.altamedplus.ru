import { integ } from "@rocet/integration";
import { Datatable } from "@tools/Datatable";
import '../event/menu'

const $table = Datatable.get('variablelist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/variable/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}