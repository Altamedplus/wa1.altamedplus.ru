export function isShow(element:HTMLElement): boolean { 
    return element.offsetWidth > 0 && element.offsetHeight > 0;
}

export function eventAdd(el:any, evt:string, Handler:Function) { 
    el.addEventListener(evt,  Handler);
}