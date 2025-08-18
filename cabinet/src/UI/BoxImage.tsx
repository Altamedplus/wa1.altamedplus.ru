import { integ } from "@rocet/integration";
import { $, r, Rocet } from "@rocet/rocet";
import { RocetElement } from "@rocet/RocetNode";
import { input } from "@rocet/RocetNodeElements";
import { ajax } from "@src/Tools/ajax/ajax";

$('div[ui=box-img]').render((Rocet: Rocet, i: number) => {
    const $el = $(Rocet.Elements[i]);
    const imgs: Array<JSX.Element> = [];
    const ids: string[] = [];

    $el.find('img').each((img: Rocet) => {
        imgs.push(<div className="box-item">
            <img src={img.attr('src')} id={img.attr('id')}></img>
            <button type="button" value={img.attr('id')} ajax={img.attr('ajax')} className="btn btn-content-trash" onclick={deleteImg}></button>
        </div>)
        ids.push(img.attr('id'));
    });
    function deleteImg(ev: MouseEvent) {
        const btn = $(ev.target);
        const ID = btn.attr('value');
        ajax.send(btn.attr('ajax'), { id: ID }).then((data) => {
            ajax.eventForm(data);
        })
    }

    if (imgs.length == 0) {
        imgs.push(<i>Нет фото</i>)
    }
    function open(ev: MouseEvent) {
        const input = $(ev.target);
        const fotos = input.closest('.box').find('.box-fotos');
        fotos.find('[data-add]').remove();

        for (let i = 0; i < input.files.length; i++) {
            var reader = new FileReader();
            reader.onload = (e) => {
                const data = e.target.result as string
                fotos.add(<div className="box-item" data-add>
                    <img src={data}></img>
                    <button type="button" className="btn btn-content-trash" data-add={i} onclick={updateFileInput}></button>
                </div>
                );
            }
            reader.readAsDataURL(input.files[i]);
        }
        if (input.files.length != 0) {
            fotos.find('i').remove();
        } else {
            if (fotos.find('img').length == 0) {
                fotos.add(<i>Нет фото</i>)
            }
        }
    }

    function updateFileInput(ev: MouseEvent) {
        const btn = $(ev.target);
        const input = btn.closest('.box').find('input[type=file]');
        const index = Number(btn.data('add'));
        let currentFiles: File[] = Array.from(input.files);
        currentFiles.splice(index, 1);
        const dataTransfer = new DataTransfer();
        currentFiles.forEach(file => dataTransfer.items.add(file));
        (input.item(0) as HTMLInputElement).files = dataTransfer.files;
        input.trigger('change');
    }

    Rocet.Exec(() => {
        const dropArea = document.getElementById('drop-area');
        const inputFile = dropArea.querySelector('input[type=file]') as HTMLInputElement;
        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.add('hover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('hover');
            }, false);
        });

        dropArea.addEventListener('drop', handleDrop, false);
        function handleDrop(e: any) {
            const dt = e.dataTransfer;
            const files = dt.files;
            const dataTransfer = new DataTransfer();
            for (let i = 0; i < files.length; i++) {
                dataTransfer.items.add(files[i]);
            }
            inputFile.files = dataTransfer.files;
            $(inputFile).trigger('change');
        }
    })


    return <div ui="box-img" className={"box " + $el.className}>
        <div className="box-fotos">
            {...imgs}
        </div>
        <div className="box-result" style={'display: none;'}>

        </div>
        <label for="input" id="drop-area">
            <div className="box-add">
                <i>Перетащите сюда фото или кликните по полю</i>
                <input type="file" name="foto[]" style={'display:none;'} onchange={open} multiple id="input" />
            </div>
        </label>
    </div>
})