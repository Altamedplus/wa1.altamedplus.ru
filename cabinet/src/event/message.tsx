import { $ } from "@rocet/rocet";
import { ajax } from "@tools/ajax";

var TimeInerval:any = null;
$('[data-open=message]').on('click', (ev: MouseEvent) => { 
    const $message = $('.open-message');
    if ($message.length == 0) return;
    $message.classToggle('open-deactive');
    const isActive = !$message.classList.contains('open-deactive');
    if (isActive) {
        const $inputDate = $('[name="serch-date-status"]');
        const date = $inputDate.val();
        getData($inputDate.val());
        $inputDate.on('change', () => getData($inputDate.val()));
        TimeInerval = setInterval(() => {
            getData($inputDate.val())
        }, 30000);
    } else {
         $('.open-items').html('');
        if (TimeInerval) {
            clearInterval(TimeInerval)
        }
    }
})

function getData(date: string) {
    ajax.send('status_get', {
        date: date
    }).then((data) => {
        $('.open-items').html(data.html);
    })
}
