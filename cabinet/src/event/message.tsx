import { $, Rocet } from "@rocet/rocet";
import { Cookie } from "@src/Tools/Cookie";
import { ajax } from "@tools/ajax";
import { wa } from "./../page/home";

var TimeInerval: any = null;
$('[data-open=message]').on('click', (ev: MouseEvent) => {
    const $message = $('.open-message');
    if ($message.length == 0) return;
    $message.classToggle('open-deactive');
    !$message.classList.contains('open-deactive') ? Cookie.set('message-block', '1') : Cookie.delete('message-block')
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

if (Cookie.get('message-block')) {
    $('[data-open=message]').trigger('click');
}


function getData(date: string) {
    ajax.send('status_get', {
        date: date
    }).then((data) => {
        if (data.html == '') return;
        const $content = $(data.html);
        const $contaner = $('.open-items');
        const oldLength = $contaner.find('.open-item').length;
        $contaner.html(' ')
        if (oldLength != 0 && oldLength != $content.length) {
            $content.each(($item: Rocet, i: number) => {
                if (i == 0) $item.classRemove('open-item-active')
                $contaner.add($item);
            })
            setTimeout(() => { 
                $($contaner.find('.open-item').item()).classAdd('open-item-active')
            }, 500)
        } else {
            $content.each(($item: Rocet) => {
                $contaner.add($item)
            })
        }
        $('[evt="wa-m"]').on('click', function (ev: MouseEvent) {
            ev.preventDefault();
            let phone = $(this).text();
            wa(phone);
        });
    })
}


