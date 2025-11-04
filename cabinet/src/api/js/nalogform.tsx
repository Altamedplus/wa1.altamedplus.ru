import { $ } from '@rocet/rocet'
import './../css/nalogform.scss'

$('.radio-year > input').on('change', function () {
    const btn = $(this);
    console.log(btn.checked);
    if (btn.checked) {
        btn.closest('.radio-year').classAdd('check');
    } else { 
        btn.closest('.radio-year').classRemove('check');
    }
})