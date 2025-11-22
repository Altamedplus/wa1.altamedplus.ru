import { integ } from "@rocet/integration";
import { $ } from "@rocet/rocet"

const sugressionList = ['[name="var_doctor[]"]']

sugressionList.forEach((select) => {
        const observer = new MutationObserver(() => {
         const el = Array.from(document.querySelectorAll(select));
          if (el.length != 0) {
              el.forEach((elm) => { 
                  sugression(elm)
              })
          }
        });
        observer.observe(document.body, { childList: true, subtree: true });
});
      
function sugression(el:any) { 
    const $input = $(el);
    
    if ($input.isAttr('list')) { 
        return;
    }
    let list = [];
    try {
        if (localStorage.getItem($input.attr('name'))) { 
            list = JSON.parse(localStorage.getItem($input.attr('name')));
        }
    } catch (e) { 
        list = [];
    }
    $input.on('change', () => {
        if (!list.includes($input.val())) { 
            list.push($input.val());
            localStorage.setItem($input.attr('name'), JSON.stringify(list));
        }
    });
    $input.attr('list', $input.attr('name'))
    const options: JSX.Element[] = [];
    if (Array.isArray(list)) { 
        list.forEach((opt:string)=>options.push(<option value={opt}></option>) )
        const datalist = $(<datalist id={$input.attr('name')}>{...options}</datalist>);
        $input.item().insertAdjacentElement('afterend', datalist.item());
    }
}
    
    
