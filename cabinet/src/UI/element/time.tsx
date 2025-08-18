import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";

new Rocet('input[ui=time]').render((r:Rocet, i:number) => { 
    const $el:HTMLInputElement = r.item(i) as HTMLInputElement;
    const optinsMin = [];
    const optinsHour = [];
    let val:any = $el.value.split(':');
    if (val.length == 0) val = [0, 0, 0];
    for (let m = 0; m < 60; m++) optinsMin.push(<option value={String(m)} selected={m == Number(val[1]) }>{String(m).padStart(2,'0')}</option>)
    for (let h = 0; h < 12; h++) optinsHour.push(<option value={String(h)} selected={h == Number(val[0]) }>{String(h).padStart(2, '0')}</option>)

    function change(evt: MouseEvent, type: string)
    {
        const select: HTMLSelectElement = evt.target as HTMLSelectElement
        const input = new Rocet(select.closest('[data-time]') as HTMLElement).find('input');
        let val = input.attr('value').split(':');
      
        switch (type) { 
            case 'h': val[0] = select.value.padStart(2, '0'); break;
            case 'm': val[1] = select.value.padStart(2,'0'); break;
        }

        input.attr('value', val.join(':'))
    }
    return <div className="mb-3 d-flex flex-column p-1" data-time>
            <input type="hidden" name={$el.getAttribute('name')} value={$el.getAttribute('value') || "00:00:00"}/>
            <div className="d-flex flex-row w-100 justify-content-around">
                <label>Часы</label><label>Минуты</label>
            </div>
            <div className="d-flex flex-row">
                <select onchange={(evt:any)=>change(evt, 'h')}>{...optinsHour}</select> <select onchange={(evt:any)=>change(evt, 'm')}>{...optinsMin}</select>
            </div>
        </div>
})