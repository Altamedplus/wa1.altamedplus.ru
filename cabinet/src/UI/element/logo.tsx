import { Rocet } from "@rocet/rocet"; 
import { integ } from "@rocet/integration";
import { UI } from "@rocet/UI";



new Rocet('logo').render((Rocet: Rocet) => {
    const el = Rocet.Elements[0];
    const className = el.className;
    return <div class={"logo " + className }>
                <img src={el.getAttribute('src')} />
                <span class="sdw-l"></span>
            </div>
});

