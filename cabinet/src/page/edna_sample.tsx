import { $ } from '@rocet/rocet'
import '../event/menu'
import './../css/page/edna_sample.scss'
$('[evt="create"]').on('click', function () {
    const tr = $(this).closest('tr');
    localStorage.setItem('text', tr.find('[data-text]').data('text'));
    localStorage.setItem('footer', tr.find('[data-footer]').text());
    localStorage.setItem('headerType', tr.find('[data-header-type]').data('headerType'))
    localStorage.setItem('headerText', tr.find('[data-header-text]').data('headerText'))
    window.location.href = '/sample/add';
})
$('[evt="create-btn"]').on('click', function () {
    const td = $(this).closest('td');
    localStorage.setItem('text', td.find('[data-text]').data('text'));
    localStorage.setItem('type', td.find('[data-type]').data('type'));
    localStorage.setItem('phone', td.find('[data-phone]').data('phone'));
    localStorage.setItem('url', td.find('[data-url]').data('url'));
    localStorage.setItem('payload', td.find('[data-payload]').data('payload'));
    window.location.href = '/buttons/add';
})
