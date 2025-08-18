import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";
import { DateF } from "@src/Tools/DateF";

export class Records {
    private contaniers: Rocet;
    private data: any = null
    private intervals = 900;

    constructor() {
        this.setContanier('#record');
    }
    public setData(data: any) {
        this.data = data;
        this.build();
    }

    public isConteners(): boolean {
        return this.contaniers.length > 0;
    }
    public setContanier(selector: string) {
        this.contaniers = $(selector);
    }
    private clear() {
        let isContainer = false;
        this.contaniers.each((el: Rocet) => {
            isContainer = true;
            el.item().innerHTML = '';
        });
        if (!isContainer) {
            return console.warn('is not container record');
        }
    }

    public build() {
        if (this.contaniers.length == 0) {
            return;
        }
        this.clear();
        const $table = $(<table className="table-record">
            <tbody>{...this.initBody()}</tbody>
        </table>)
        this.contaniers.add($table);
    }

    private initBody(): Array<JSX.Element> {
        const tbody: Array<JSX.Element> = [];
        const isNullData = this.initIsNull();
        if (isNullData) {
            tbody.push(isNullData);
            return tbody;
        }
        const from = DateF.timeToSeconds(this.data.from);
        const to = DateF.timeToSeconds(this.data.to);

        let controlTimeEnd = '';
        let controlTimeEndBreak = '';
        for (let i = from; i <= to; i = i + this.intervals) {
            let isToTime: boolean = false;
            for (let ins = 0; ins < this.data.break.length; ins++) {
                let inter = this.data.break[ins];
                const st = DateF.timeToSeconds(inter.from);
                const en = DateF.timeToSeconds(inter.to);
                if (st <= i && en >= i) {
                    if (controlTimeEndBreak != inter.from) {
                        tbody.push(<tr><td className="td-time" >{inter.from + '-' + inter.to}</td><td className="w-100"> <div className="flex-row-center w-100">{"Перерыв"}</div></td></tr>);
                        controlTimeEndBreak = inter.from
                    }
                    isToTime = true;
                }
            }

            for (let ins = 0; ins < this.data.intervals.length; ins++) {
                let inter = this.data.intervals[ins];
                const st = DateF.timeToSeconds(inter._from);
                const en = DateF.timeToSeconds(inter._to);
                if (st <= i && en >= i) {
                    if (controlTimeEnd != inter._from) {
                        tbody.push(<tr><td className="td-time" >{inter._from + '-' + inter._to}</td><td className="w-100"> <div className="flex-row-center w-100">{inter.name}</div></td></tr>);
                        controlTimeEnd = inter._from
                    }
                    isToTime = true;
                }
            }

            if (!isToTime) {
                let but = <button className="btn-plus-small"></button>;
                if (new DateF().getTime() > new DateF(this.data.date + "T" + DateF.secondsToTime(i, 'hh:mm:ss')).getTime()) {
                    but = <div></div>;
                }

                tbody.push(<tr><td className="td-time" >{DateF.secondsToTime(i)}</td><td className="w-100"><div className="flex-row-center w-100">{but}</div></td></tr>);
            }
        }
        return tbody;
    }

    private initIsNull() {
        if (!this.data || !this.data.from) {
            return <div className="flex-column-center w-100">
                <p> Расписание на этот день не открыто </p>
                <a type="button" class="btn-submit-blue" href="/shedule/edit">Открыть</a>
            </div>
        }
        return false;
    }
}