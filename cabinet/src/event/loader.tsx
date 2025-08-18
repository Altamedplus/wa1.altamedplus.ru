import { $, Rocet } from "@rocet/rocet";
Rocet.loadPage(() => {
    $('.loader-page').classAdd('loader-close');
    setTimeout(() => {
        $('.loader-page').hide()
    }, 1200);
})