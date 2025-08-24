import { $ } from '@rocet/rocet'
import '../event/menu'
import './../css/page/edna_sample.scss'
$('[evt="create"]').on('click', function () {
    const tr = $(this).closest('tr');
    localStorage.setItem('text', (tr.find('[data-text]').data('text')|| ''));
    localStorage.setItem('footer', (tr.find('[data-footer]').text()|| ''));
    localStorage.setItem('headerType', (tr.find('[data-header-type]').data('headerType') || ''))
    localStorage.setItem('headerText', (tr.find('[data-header-text]').data('headerText')|| ''))
    window.location.href = '/sample/add';
})
$('[evt="create-btn"]').on('click', function () {
    const td = $(this).closest('td');
    ['text', 'phone', 'type', 'url', 'payload'].forEach((n: string) => {
        localStorage.setItem(n, (td.find(`[data-${n}]`).data(n) || ''));
    });
    window.location.href = '/buttons/add';
})
