import { Datatable } from '@tools/Datatable';
import '../event/menu'
import './../css/page/nalog.scss'
import { initBtnModal } from '@src/event/modal';
import { $, Rocet } from '@rocet/rocet';
import { ajax } from '@tools/ajax';
const $table = Datatable.get('naloglist');
if ($table) {
    $table.initCallback = () => { 
        initBtnModal();
    }
}

$('[evt="add-file"]').on('change', function () { 
    const $file = $(this);
    ajax.send('nalog_file',
        {
            file: $file.files[0],
            requestId: $('input[name="id"]').val()
        }).then((data) => {
            console.log(data);
            const $items = $file.closest('.document-item');
            $items.find('[data-name]').val(data.name);
            $items.find('[data-origin]').val(data.origin);
            $items.find('[data-url]').val(data.url);
            $items.find('[data-path]').val(data.path);
            $items.find('[data-relat]').val(data.relat_path);
            $items.find('[name="file-label"]').text(data.origin);
        });
})

$('[evt="delete-field"]').on('click', function () {
    const $btn = $(this);
    const $items = $btn.closest('.document-item');
    ajax.send('nalog_fdelete',
        {
            path: $items.find('[data-path]').val(),
            name: $items.find('[data-name]').val()
        }).then(() => {
            if ($('.document-item').length > 1) {
                $btn.closest('.document-item').remove();
            } else {
                $items.find('[name="file-label"]').text('')
                $items.find('input').val('');
            }
            $('button[type=submit]').trigger('click');
        });
    
});

$('[evt="eye-field"]').on('click', function () {
    const $dataUrl = $(this).closest('.document-item').find('[data-url]');
    window.open($dataUrl.val());
});

$('[evt="add-field"]').on('click', function () {
    const $items = $('.document-block');
    const $clone = $($items.find('.document-item').item()).clone();
    $clone.find('input').val('');
    $clone.find('[name="file-label"]').text('');
    $items.add($clone);
});

