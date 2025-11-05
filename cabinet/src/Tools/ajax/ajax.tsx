import { $ } from "@rocet/rocet";
import { Fire } from "../../UI/element/fire";
import { input } from "@rocet/RocetNodeElements";
import { inputTextFormRender } from "@tools/UI/input";
import { ErrorInput } from "../ErrorInput";
export class ajax {
    static async send(name:string, body:any, isJson:boolean = true) {
        return JSON.parse(await ajax.post(body, {}, '/ajax/' + name));
    }


    static async post(data: BodyInit | null | any | HTMLFormElement, headers:any = {}, href:string = null) {
        let body: any;
        let url: string = href || location.href;
        if (data instanceof HTMLFormElement) {
            body = new FormData(data);
            this.checkboxNormalize(data, body);
            if (data.hasAttribute('name')) { 
                headers['form-name'] = data.getAttribute('name');
            }

            body.append('csrf-token', data.getAttribute('csrf-token'));
        } else { 
            body = new FormData();
            Object.keys(data).forEach((key: string) => {
                if (typeof data[key] == 'object') {
                    body.append(key, JSON.stringify(data[key]));
                } else {
                    body.append(key, data[key]);
                }
            });
        }

       const result =  await fetch(url, {
            method: 'POST',
            body,
            headers,
            redirect: 'follow'
       })
        if (result.headers.get('Content-type') == 'application/json;') { 
            return await result.json();
        }
        return await result.text();
    }

    public static eventForm(data: any = {}): boolean
    {
        if (data?.type == 'fire') {
            return Fire.show(data);
        }
        if (data?.type == 'error-input') {
            if (Array.isArray(data.name)) {
                data.name.forEach((e: any, i: any) => {
                    new ErrorInput({
                        message: data.message[i],
                        name: e
                    });
                    
                });
            } else { 
                new ErrorInput(data);
            }
            return false;
        }
        if (data?.type ==  'modal') ajax.modalOpen(data)
        if (data?.type == 'reload') location.reload();
        if (data?.type == 'redirect') location.href = data.href;
        return true;
    }

    public static modalOpen(data: any , callback:Function = null) {
        const callbackModal = data['callbackModal'] || null;

        ajax.post(data, {}, '/modal').then((response) => {
            const parse = JSON.parse(response)
            const body = document.body
            $('.modal-carset').remove();
            const modal:HTMLElement = document.createElement('div') as HTMLElement
            modal.classList.add('modal-carset');
            modal.innerHTML = parse.html;
            body.append(modal);

            $('input[ui=text-form]').render(inputTextFormRender)
            $(modal).find('button[type=submit]').on('click', function (evt: MouseEvent) {
                evt.preventDefault();
                const $button = $(evt.target as HTMLElement)
                const form: HTMLFormElement = $button.closest('form').item(0) as  HTMLFormElement;
                ajax.post(form).then((dataForm) => ajax.eventForm(dataForm));
            });

            $('.close-modal').on('click', () => { 
                modal.remove();
            })
            if (typeof window[callbackModal] === 'function') {
                (window[callbackModal] as Function)(data)
            }
            if (callback) { 
                callback()
            }
        });
    }


    private  static checkboxNormalize(form:HTMLFormElement , fdata:FormData) { 
        form.querySelectorAll('input[type=checkbox]').forEach((el:HTMLInputElement) => { 
            fdata.delete(el.name);
            fdata.append(el.name, String(el.checked ? 1 : 0));
        })
    }
}