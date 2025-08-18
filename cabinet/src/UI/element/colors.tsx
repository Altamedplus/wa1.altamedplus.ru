import "./colors"
import { $, Rocet } from "@rocet/rocet"; 
import { integ } from "@rocet/integration";
import { UI } from "@rocet/UI";



$('colors').render((Rocet: Rocet, i:number) => {
    const el = $(Rocet.item(i));
    let click: EventListenerRecord[]  = Rocet.item(i)?.getEventListeners('click') as EventListenerRecord[]
    let fclick: Function = click[0]?.listener as Function;
    const className = el.className;

    function onclick(evt: MouseEvent | any) {
        const colors  = evt.srcElement.getAttribute('value');
        const [r, g, b]: Array<number> = colors.split(' ');

        evt['islight'] = (0.299 * r + 0.587 * g + 0.114 * b) > 128;
        evt['colorsHover'] = evt['islight'] ? `${r} ${g - 80} ${b}` : `${r} ${g + 80} ${b}`;
        evt['colors'] = `rgb(${r} ${g} ${b})`;
        evt['rgb'] = [r, g, b]

        if(fclick) fclick(evt);
    }   
    return <div id="colorPanel" style="display: flex; gap: 1px;" onclick={onclick}>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(48 60 108); cursor:pointer;" value="48 60 108"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(255 168 188); cursor:pointer;" value="255 168 188"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(193 193 193); cursor:pointer;" value="193 193 193"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(100 190 243); cursor:pointer;" value="100 190 243"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(37 115 51); cursor:pointer;" value="37 115 51"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(0 0 0); cursor:pointer;" value="0 0 0"></div>
            <div class="colorBox" style="width:18px; height:18px; background:rgb(54 23 49); cursor:pointer;" value="54 24 49"></div>
        </div>
});

