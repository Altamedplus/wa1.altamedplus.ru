import { $, Rocet } from "@rocet/rocet";
import { integ } from "@rocet/integration";
import { RocetElement } from "@rocet/RocetNode";
interface optionfire { 
    text: string
    status: string
    header?:string
}
export class Fire{
    public text = ''
    public status = 'error'; // success
    public header = ''

    constructor(option:optionfire) { 
        this.text = option.text;
        this.status = option.status
        this.header = option?.header
    }
    public static show(option: any = {}) {
        (new Fire(option)).render()
    }

    private render()
    {
        let header: RocetElement
        $('.fire').remove();
        if (this.header) header = <h3>{this.header}</h3>
        $(<div />).render((Rocet: Rocet, i:number) => {
            document.body.append(Rocet.Elements[i])
            return <div className={"fire " + this.status + '-fire'}>
                {header}
                <span className="">{this.text}</span>
            </div>
        });
    }
}