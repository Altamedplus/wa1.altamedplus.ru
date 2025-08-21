
import { $, Rocet } from "@rocet/rocet";
import { integ } from "@rocet/integration";
import { UI } from "@rocet/UI";
import { eventAdd } from "../../Tools/ElementsTool";



$('select[ui=multi]').render((Rocet: Rocet, i: number) => {
    const $el = $(Rocet.Elements[i]);
    const sp: Array<JSX.Element> = [];
    const spVal: Array<JSX.Element> = [];
    let text: string[] = []
    $el.find('option').each(($options: Rocet) => {

        sp.push(<li className={'select-option ' + ($options.isAttr('selected') ? 'select-option-active' : '')} onclick={selectLi} value={$options.attr('value')}>{$options.item().textContent}</li>);
        if ($options.isAttr('selected')) {
            text.push($options.item().textContent)
            spVal.push(<input type="hidden" name={$el.attr('name') + '[]'} value={$options.attr('value')}></input>);
        }
    });

    function search(ev: KeyboardEvent) {
        const select = $(ev.target).closest('.select');
        const value = $(ev.target).val().toLowerCase();
        const options = select.find('li');
        if (value != '') {
            options.each((li: Rocet) => !li.text().toLowerCase().includes(value) ? li.hide() : li.show())
        } else {
            options.each((li: Rocet) => li.show());
        }
    }
    function selectLi(ev: MouseEvent) {
        const li = $(ev.target)
        li.classToggle('select-option-active');
        const select = li.closest('.select')
        const result = select.find('.select-result');
        const resultIds = select.find('.select-value');
        const lis = select.find('li');
        resultIds.item().innerHTML = '';
        result.text(' ');
        let text: string[] = [];
        let ids: string[] = [];
        lis.each((item: Rocet) => {
            item.classList.contains('select-option-active') ? text.push(item.text()) : '';
            item.classList.contains('select-option-active') ? ids.push(item.attr('value')) : ''
        });
        result.text(text.join(', '));
        ids.forEach((id) => {
            resultIds.add(<input type="hidden" name={$el.attr('name') + '[]'} value={id}></input>)
        })
    }

    function open(ev: MouseEvent) {
        const select = $(ev.target).closest('.select').find('.select-options');
        select.show();
    }

    function close(ev: MouseEvent) {
        const select = $(ev.target).closest('.select').find('.select-options');
        select.hide();
    }
    $(document.body).on('click', function (ev: MouseEvent) {
        console.log()
        if ($(ev.target).classList.contains('select-search')
            || $(ev.target).classList.contains('select-options')
            || $(ev.target).classList.contains('select-option')
        ) {
            return;
        }
        $(this).find('.select-options').hide();
    })

    return <div className="select select-hide" >
        <div className="flex-row">
            <label className="select-label" onclick={open}>{$el.attr('label')}</label>
            <div className="select-result">{text.join(', ')}</div>
        </div>
        <input className="select-search" type="text" onkeydown={search} onfocus={open} />
        <ul className="select-options" style={'display:none;'} onblur={close} >
            {...sp}
        </ul>
        <div className="select-value" style={'display:none'}>
            {...spVal}
        </div>
    </div>
});

$('select[multiple] > option').on('click', function (ev:MouseEvent) {
    ev.preventDefault();
    if (this.selected) {
        this.selected = false
    } else { 
        this.selected = true
    }
})
$('select[multiple]').on('mousedown', function(ev:MouseEvent) {
    ev.preventDefault();
});