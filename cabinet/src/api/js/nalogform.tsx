import { $, Rocet } from '@rocet/rocet'
import './../css/nalogform.scss'
import { ajax } from '@src/Tools/ajax/ajax';
import { integ } from '@rocet/integration';

$('.radio-year > input').on('change', function () {
    const btn = $(this);
    if (btn.checked) {
        btn.closest('.radio-year').classAdd('check');
    } else {
        btn.closest('.radio-year').classRemove('check');
    }
});
$('.radio-cl > input').on('change', function () {
    const btn = $(this);
    console.log(btn)
    if (btn.checked) {
        btn.closest('.radio-cl').classAdd('check');
    } else {
        btn.closest('.radio-cl').classRemove('check');
    }
});

$('[name=name]').on('change', function () {
    if ($('[name=taxpayer]').val() == '139') {
        $('[name=fio_nalog]').val($(this).val())
        $('[name=taxpayer_date_birth]').val($('[name=date_birth]').val())
    }
});
$('[name=date_birth]').on('change', () => $('[name=name]').trigger('change'));
$('[name=taxpayer]').on('change', () => {
    $('[name=name]').trigger('change');
    if ($('[name=taxpayer]').val() != '139') {
        $('[name=fio_nalog]').val() == $('[name=name]').val() ? $('[name=fio_nalog]').val('') : '';
        $('[name=taxpayer_date_birth]').val() == $('[name=date_birth]').val() ? $('[name=taxpayer_date_birth]').val('') : '';
    }
}
);

$('[evt=modal-open]').on('click', () => {
    ajax.sendApi('nalog_consent', {}).then((data) => {
        $('form').add(<div className='modal-fone'>
            <div className='modal-body'>
                <div className='modal-header'>
                    <p>Согласие на обработку персональных данных</p>
                </div>
                <div clasName="modal-message">{data.html}</div>
                <div className='modal-buttons'>
                    <button className='green' onclick={() => {
                        $('[name=consent]').attr('checked', 'checked');
                        $('.modal-fone').remove();
                    }}>ПРИНИМАЮ</button>
                    <button onclick={() => {
                        $('.modal-fone').remove();
                        $('[name=consent]').attrRemove('checked');
                    }}>НЕ ПРИНИМАЮ</button>
                </div>
            </div>
        </div>)
    })
})
$('button[type=submit]').on('click', () => { 
    if (!$('[name="consent"]').checked) { 
        $('[evt=modal-open]').trigger('click');
    }
})

window['callbackSubmit'] = function (data: any) {
    if (data.type == 'nalog-ok') { 
        ajax.sendApi('nalog_finishForm', {}).then((data) => { 
            $('.form').add($(data.html))
        })
    }
}
