import '../css/page/home.scss'
import { $, Rocet } from '@rocet/rocet';
import '../event/menu'
import { integ } from "@rocet/integration";
import { Datatable } from "@tools/Datatable";
import { ajax } from '@tools/ajax';
import { DateF } from '@src/Tools/DateF';
import { Cookie } from '@src/Tools/Cookie';
import { Fire } from '@tools/Fire';
import '../event/message'
import '../event/sugression'
import { button } from '@rocet/RocetNodeElements';

var tabs = 1;

const $table = Datatable.get('buttonmessanangelist');
if ($table) {
    $table.rerender = (item: any, alias: any) => {
        const result: Array<JSX.Element> = [];
        const grouping: any = {};
        const resutGroping: any = {};

        item.forEach((el: any) => {
            if (!Object.keys(grouping).includes(String(el.group_type))) {
                grouping[String(el.group_type)] = [
                    <h5 className="collapse-open" evt="collapse">{el.gname}</h5>
                ]
                resutGroping[String(el.group_type)] = [];
            }
            
            resutGroping[String(el.group_type)].push(<span
                className={"btn btn-submit-blue "}
                data-tab-btn="btn"
                data-id={el.id}>{el.name}
            </span>)
        });

        Object.keys(grouping).forEach((gn: any) => {
            result.push(<tr>
                {...grouping[gn]}
                <div className='line-span'>
                    {...resutGroping[gn]}
                </div>
            </tr>)
        })

        return result;
    }

    // После постраения таблицы выполнить код
    $table.initCallback = ((table: Datatable) => {
        const $btns = $(table.table).find("[data-tab-btn=btn]");
        const $btn = $($btns.item(0));
        $btn .classAdd('sample-active');
        $btns.on('click', function () {

            $btns.classRemove('sample-active');
            $(this).classAdd('sample-active');
            const id = $(this).data('id');
            $('input[name=id]').val(id)
            ajax.send('sample_get', { id: id }).then((data) => {
                buildFilds(data);
                tabs = 0;
                tabindex();
            })
        })
        $btn.trigger('click');

        $('[evt="collapse"]').on('click', function () {
            const $h = $(this);
            const $div = $(this).closest('tr').find('.line-span');
            if ($h.classList.contains('collapse-open')) {
                $h.classReplase('collapse-open', 'collapse-close');
                $div.classAdd('line-close')
            } else {
                $h.classReplase('collapse-close', 'collapse-open');
                $div.classRemove('line-close');
            }
        })

    });

    $('[data-clinic]').on('change', function () {
        let clinicId = $(this).val();
        const $searhInput = $('input[name=clinic_id]');
        Cookie.set('select_clinic_id', clinicId);
        $searhInput.val(clinicId);
        $searhInput.trigger('change');
        evenSelect();
    })
}

function buildFilds(data: any) {

    const $dynamic = $('[data-dynamic]');
    const $messange = $('[data-message]');
     const $button = $('[data-button]');
    $messange.html(' ');
    $dynamic.html(data.html.join(' '))
    const mess = $(<div class="pannel-message"></div>);
    mess.html(data.message);
    $messange.add(mess);
    $button.html(data.button.join(' '));
    eventMessange($messange, $dynamic);
    evenSelect();
}


$('[name=phone]').on('input', async function () {
    $('[data-but-send]').remove();
    
    let phone = parseInt(this.value.replace(/\D+/g, ""));
    if ((new String(phone)).length >= 11) {
        const $form = $('form[name="message/send"]');
        const result = await ajax.send('home_get_butSubmit', { phone });

        if (result.but && result.but.length != 0) {
            result.but.forEach((el: string) => {
                const div = $(`<div class='flex-row-center'></div>`);
                const btn = $(el);
                btn.on('click', window['submit'])
                div.add(btn)
                $form.add(div);
            });
          
        }
    } else {
        const btns = $('[data-but-send]')
        if (btns.length != 0) {
            btns.remove();
        }
    }
});

function eventMessange($messange: Rocet, $dynamic: Rocet) {

    const $variable = $dynamic.find('[data-variable]');
    $variable.on('input', function () { 
        const $inputCahnge = $(this);
        const count = Number($inputCahnge.data('count'));
        $messange.find('[data-messange]').each(($span: Rocet, i: number) => { 
            if (Number(i + 1) == count) { 
                if ($inputCahnge.val()) {
                    $span.classRemove('btn-green');
                    $span.classRemove('btn');
                    let val = $inputCahnge.val()
                    if ($inputCahnge.data('format')) { 
                       val = (new DateF(val+"T00:00:00")).format($inputCahnge.data('format'))
                    }
                    $span.text(val);
                } else {
                    $span.classAdd('btn');
                    $span.classAdd('btn-green');
                    $span.text($span.data('content'));
                }
            }
        })
    })
}
function evenSelect() {
    const $select = $('select[data-clinic]');
    const $address = $("[data-consant=address]");
    const $clinic = $("[data-consant=clinic]");
    let nameClinic = '';
    let address = '';
    $select.find('option').each(($opt: Rocet) => {
        if (Number($opt.attr('value')) == Number($select.val())) {
            nameClinic = $opt.text();
            address = $opt.data('address');
        }
    });
    if ($clinic.length != 0) $clinic.text(nameClinic);
    if ($address.length != 0) $address.text(address);
   
}

window['callbackSubmit'] = function (data:any) { 
    if (data.type == 'modal' && data.template == 'resend') {
        return { 'preventDefault': true }
    } else { 
        Cookie.delete('resend');
    }
    if (Cookie.get('clear-buf') == '1') {
        if (navigator && navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText('');
        } else {
            alert('API буфера обмена не поддерживается в этом браузере.');
        }
    }
}

window['initResendModal'] = function (data: any) { 
    $('[evt="noResed"]').on('click', function () {
        $(this).closest('.modal').find('.close-modal').trigger('click');
    });
    $('[evt=resend]').on('click', function () {
        const form = $('[name="message/send"]')
        const tel:string = form.find('input[name=phone]').val();
        Cookie.set('resend', tel);
        $(this).closest('.modal').find('.close-modal').trigger('click');
        // console.log(form.find('button[type="submit"]'));
        form.find('button[type="submit"]').trigger('click');
    })
}

$('[evt="wa"]').on('click', function (ev: MouseEvent) {
    ev.preventDefault();
    let phone = $(this).closest('[name="message/send"]').find('[name="phone"]').val();
    wa(phone);
});



export function wa(phone: string | null) {
    if (phone === null) {
        Fire.show({ status: 'error', text: 'Запоните телефон', header: 'Пустое поле' })
        return
    }
    ['+', '(', ')', '-', '-', ' '].forEach((b: string) => {
        phone = phone.replace(b, '');
    });

    window.open("https://web.whatsapp.com/send?phone=" + phone, '_blank')
}

function tabindex() {
    (document.querySelector('[data-count]') as HTMLInputElement | HTMLTextAreaElement | HTMLButtonElement).focus();
    let MaxTabs = 0;
    document.querySelectorAll('[data-count]').forEach((el) => { 
        if (Number(el.getAttribute('data-count')) >= 0) { 
            MaxTabs++;
        }
    })
    console.log('mAXtABS', MaxTabs);
    document.body.addEventListener('keydown', (ev: KeyboardEvent) => {
        if (ev.key == 'Tab') {
            ev.preventDefault();
            tabs++;
            document.querySelectorAll('[data-count]').forEach((el: HTMLInputElement | HTMLTextAreaElement | HTMLButtonElement) => {

                if (Number($(el).attr('data-count')) == Number(tabs)) {
                    console.log('TABS', tabs, el)
                    el.focus()
                }
            });
            if (tabs > MaxTabs) tabs = 0;
        }
    });
}
