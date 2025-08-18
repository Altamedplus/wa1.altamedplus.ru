import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";
import { RocetElement, RocetNode } from "@rocet/RocetNode";
import { UI } from "@rocet/UI";
import { ReactNode } from "react";

$('img[ui=slide]').render((el: Rocet, i:number) => {
        const images: Array<JSX.Element> = [];
    
        el.Exec(initCarousel, i);
        const $iel = $(el.item(i));
        const maxWidth: string = $iel.attr('width') || '200';
        const maxHeight: string = $iel.attr('height') || '200';
        const DelayBTN = Number(($iel.attr('dalay') || '6000'));
        const intervalAnimation = Number(($iel.attr('interval') || '5000'));
        const isAnimation = ($iel.isAttr('animation') || false);
        if ($iel.isAttr('images')) { 
            let active: boolean = true;
            try {
                JSON.parse($iel.attr('images')).forEach((path: string, i: number) => {
                    images.push(<div class={"carousel-item" + (active ? " active" : "")}   onclick={openFoto}>
                        <img src={($iel.attr('path') || '') + path} class="d-block w-100" alt="..."/>
                    </div>)
                    active = false
                });
            } catch (e: any) { }
            if (active) {
                images.push(<div class="carousel-item  active" >
                    <img src={'/view/img/UI/image.png'} class="d-block w-50" alt="..." />
                </div>)
            }
        }
    let interval:any = null;
    function initCarousel($caruosel: Rocet) {
        if (isAnimation) { 
            interval = setInterval(()=>next($caruosel), intervalAnimation);
        }
    }
    function openFoto(evt:MouseEvent) {
        let $img = $(evt.target as HTMLElement).closest('.carousel-inner').find('.active').find('img');
        $img = $img.clone();
        $('body').add(<div className="open-foto">
            <div className="open-foto-content">
                <span className="open-foto-close fa fa-close" onclick={()=>$('.open-foto').remove()}></span>
                { $img as unknown as RocetElement}
            </div>

        </div>)
    }
    let PuchTime = null;
    const puch = (next: Function, $caruosel:Rocet) => {
        if (isAnimation) { 
            clearInterval(interval);
            clearTimeout(PuchTime);
        }
        next();
        if (isAnimation) { 
            TimeBack = setTimeout(() => { 
                el.ExecElements[i]($caruosel);
            }, DelayBTN)
        }
    }

    function next($caruosel: Rocet, back:boolean = false) { 
        const $inner = $caruosel.find('.carousel-inner');
        const $items = $inner.find('.carousel-item');
        $items.each(($item: Rocet, i: number) => {
            if ($item.classList.contains('active')) {
                $item.classList.remove('active');

                if (back) { 
                    let intgB = ((i - 1) < 0 ? $items.length - 1 : (i - 1));
                    $($items.item(intgB)).classList.add('active');
                    return false;
                }

                let intg = ((i + 1) == $items.length ? 0 : (i + 1));
                $($items.item(intg)).classList.add('active');
                return false;
            }
        })
    }

    function nextBtn(evt: any) {
        evt.preventDefault()
        const $caruosel = $(evt.target as HTMLElement).closest('.carousel');
        puch(() => next($caruosel), $caruosel);

    }
    let TimeBack:any = null;
    function backBtn(evt: any) {
        evt.preventDefault()
        const $caruosel = $(evt.target as HTMLElement).closest('.carousel');
        puch(() => next($caruosel, true), $caruosel);
    }

    return <div className="carousel slide" data-role="carousel"
        style={`width: ${maxWidth}px; height:${maxHeight}px`}
        >
        <div className="carousel-inner">{...images}</div>
        <a class="carousel-control-prev"  role="button" onclick={(evt:any)=>backBtn(evt)}>
            <span class="carousel-control-prev-icon" aria-hidden="true" ></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next"  role="button" onclick={(evt:any)=>nextBtn(evt)}>
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
});