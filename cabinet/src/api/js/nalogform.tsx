import { $, Rocet } from '@rocet/rocet'
import './../css/nalogform.scss'
import { ajax } from '@tools/ajax';
import { integ } from '@rocet/integration';

$('.radio-year > input').on('change', function () {
    const btn = $(this);
    if (btn.checked) {
        btn.closest('.radio-year').classAdd('check');
    } else {
        btn.closest('.radio-year').classRemove('check');
    }
});

$('[evt=modal-open]').on('click', () => {
    ajax.send('nalog_consent', {}).then((data) => {
        $('form').add(<div className='modal-fone'>
            <div className='modal-body'>
                <div className='modal-header'>
                    <p>Согласие клиентов на обработку персональных данных</p>
                </div>
                <div clasName="modal-message">{data.html}</div>
                <div className='modal-buttons'>
                    <button className='' onclick={() => {
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