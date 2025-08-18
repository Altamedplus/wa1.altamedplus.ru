import { integ } from "@rocet/integration";
import { Datatable } from "@tools/Datatable";
import '../event/menu'

const $table = Datatable.get('groupsamplelist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/group_sample/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}