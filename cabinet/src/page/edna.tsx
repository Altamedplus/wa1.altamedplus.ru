import { $, Rocet } from "@rocet/rocet";
import '../event/menu'
import { Datatable } from "@tools/Datatable";
import { integ } from "@rocet/integration";
import { ajax } from "@tools/ajax";
import '../css/page/edna.scss';

const $table = Datatable.get('ednalist');
if ($table) {
    $table.buildRows = (row: any, TR: Array<JSX.Element>, indexRow: number) => {
        TR.push(<td><a href={'/edna/edit/' + row['id']} className="btn-round btn-content-edit"></a></td>);
        return <tr>{...TR}</tr>;
    }
}
let api = $('[name=api]');
if (api.length > 0) {

    const $block = $('[data-channel]');
    $block.html('<div class="flex-row-center w-80 h-100"><div class="loader-gear"></div></div>')
    api.on('change', () => {
        getProfile()
    });

    function getProfile() { 
        ajax.send('edna_channelProfile', { api: api.val() }).then((data) => { 
            data.forEach((elm:any) => {
                buildchannel(elm)
            });
        }).catch((data) => {
            buildchannel(false)
        })
    }
    function buildchannel(data: any) {
        $block.html(' ')
        if (!data) {
             $block.add(<div>
                 <h5>Информация о каналах </h5>
                 <p>{"<strong>Неверный ключ авторизации</strong>"}</p>
            </div>)
        } else { 
        $block.add(<div>
            <h5>Информация о каналах </h5>
            <p>{"<strong>ID: </strong>" + data.id}</p>
            <p>{"<strong>Идентификатор подписи :</strong> " + data.subjectId}</p>
            <p>{"<strong>Название подписи  : </strong>" + data.subject}</p>
            <p>{"<strong>Статус канала : </strong>" + (data.qualityStatus)}</p>
            <p>{"<strong>Тип канала : </strong>"+ data.type}</p>
            <p>{"<strong>Идентификатор канала : </strong>" + data.channelAttribute}</p>
            <p>{"<strong>Суточный лимит : </strong>" + data.messagingLimit}</p>
        </div>)

        }
    }

    getProfile()
}