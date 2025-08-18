import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";
import { RocetNode } from "@rocet/RocetNode";
import { input } from "@rocet/RocetNodeElements";
import { UI } from "@rocet/UI";

export function texttareaMaskedFormRender(Rocet: Rocet, i: number) {
    const el = $(Rocet.Elements[i]);
    if (el.attr('render')) return;
    el.attr('render', '1');
    let retranslateDiv = (ev: any) => {
        const tt = $(ev.target);
        const div = tt.closest('div').find('[ui=divMasked]');
        div.item().innerHTML = tt.val();

    }
    const input = <textarea onkeydown={retranslateDiv}>{el.val()}</textarea>;
    const props = el.getObjectAttr();
    input.props = Object.assign(input.props, props);
    input.props.className = el.attr('ui')

    return <div class="form-row">
        <label>{el.attr('label')}</label>
        <div className="box-input">
            {input}
            <div ui="divMasked">{el.val()}</div>
        </div>
    </div>
}

$('textarea[ui="masked-p"]').render(texttareaMaskedFormRender);
$('textarea[ui="unmasked"]').render(texttareaMaskedFormRender);