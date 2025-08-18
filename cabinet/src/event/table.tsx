import { Datatable } from "@tools/Datatable";


const tables = document.querySelectorAll('table');
tables.forEach((el) => { 
    if (el instanceof HTMLTableElement && el.getAttribute('name')) { 
       const datatable = new Datatable(el);
    }
})