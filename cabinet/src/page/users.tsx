import '../event/menu'
import { integ } from "@rocet/integration";
import { Datatable } from "@tools/Datatable";

const $table = Datatable.get('userslist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/users/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}