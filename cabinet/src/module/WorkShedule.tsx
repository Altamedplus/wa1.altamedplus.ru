import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";
import '../css/module/WorkShedule.scss';
import { DateF } from "@src/Tools/DateF";
import { Fire } from "@src/UI/element/fire";

export class WorkShedule {

    public dateStart: DateF;
    private dateToday: DateF;
    private daysInMonth: number; // Количество дней в текущем месяце
    private dateBesy: any = {};
    public ajaxGet: (dateStart:DateF) => any | null = null;
    private monthName = [
        'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
        'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
    ];

    public contaniers: Rocet;
    private dayOfWeekName = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
    private month: number = null;
    private year: number = null;
    private day: number = null;

    public clickDate: (from: Rocet, to: Rocet) => void | null = null
    public clickDateAffter:(from:Rocet, to:Rocet )=>void | null = null

    constructor(date: Date, dateBesy: Array<any> = []) {
        this.dateStart = new DateF(date);
        this.dateBesy = dateBesy;
        this.dateToday = new DateF();
        this.setContanier('#work-shedule');
    }

    private calcDay() {
        this.day = this.dateStart.getDate();
        this.year = this.dateStart.getFullYear();
        this.month = this.dateStart.getMonth();
        this.daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
        if (this.dateStart.getFullYear() == new Date().getFullYear() && this.dateStart.getMonth() != new Date().getMonth()) {
            this.dateStart.setDate(1);
        } else { 
            this.dateStart.setDate(new Date().getDate());
        }
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
            return console.warn('is not container worck-shedule');
        }
    }

    public async build() {
        if (this.contaniers.length == 0) { 
            return;
        }
        if (this.ajaxGet) { 
            this.dateBesy = await this.ajaxGet(this.dateStart);
        }
        this.clear();
        this.calcDay();
        const $work = <div className="work-wrapper">
            {this.initController()}
            <table className="work-table">
                <thead>{this.initHeader()}</thead>
                <tbody>{this.initBody()}</tbody>
            </table>
        </div>
        this.contaniers.add($work);
        this.calcTotal();
    }
    public calcTotal() {
        let total: any = {}
        this.contaniers.find('[data-open="on"],[data-open="select"]').each(function ($el: Rocet) {
            let month = new DateF($el.data('date')).getMonth();
            let from = $el.find('[data-from]').data('from');
            let to = $el.find('[data-to]').data('to');
            let workTime = (DateF.timeToSeconds(to) - DateF.timeToSeconds(from));
            $el.find('[data-break-from]').each(($break: Rocet) => {
                workTime -= (DateF.timeToSeconds($break.data('breakTo'))) - DateF.timeToSeconds($break.data('breakFrom'));
            })
            total[month] ? total[month] += workTime : total[month] = workTime;
        })
        Object.keys(total).forEach((month) => { 
            const text = DateF.secondsToTime(total[month], 'h ч. m мин.');
            this.contaniers.find(`[date-total-month="${month}"]`).text(text);
        })
    }

    public initHeader(): JSX.Element {
        const TR: Array<JSX.Element> = [];
        let date = new DateF(this.dateStart);

        for (let i: number = 1; i <= this.daysInMonth; i++) {
            let classTh = date.equality(this.dateToday) ? 'work-th-today' : 'work-th-date';
            // Тотал заголовок 
            if (i == 1) {
                TR.push(<th>
                    <div className='work-th-date'>
                        <span className="work-th-dummy">Итого</span>
                        <span className="work-th-dummy">{'за ' + this.monthName[date.getMonth()].toLowerCase()}</span>
                    </div>
                </th>);
            }
            // Тотал заголовок сл Месяца
            if (date.getMonth() != this.dateStart.getMonth() && date.getDate() == 1) {
                TR.push(<th>
                    <div className='work-th-date'>
                        <span className="work-th-dummy">Итого</span>
                        <span className="work-th-dummy">{'за ' + this.monthName[date.getMonth()].toLowerCase()}</span>
                    </div>
                </th>);
            }
            // Даты
            TR.push(<th>
                <div className={classTh}>
                    <span className="work-th-day">{String(date.getDate()).padStart(2, '0')}</span>
                    <span className="worck-th-week">{this.dayOfWeekName[date.getDay()]}</span>
                </div>
            </th>)
            date.setDate(date.getDate() + 1);
        }

        return <tr>{...TR}</tr>;
    }

    public initController(): JSX.Element {
        let obj = this;
        return <div className="work-controller">
            <div className="work-control-date">
                <button className="work-btn" onclick={()=>this.backBtn(obj)} >{'<'}</button>
                <button className="work-btn"  onclick={()=>this.nextBtn(obj)}>{'>'}</button>
                <span className="work-date">{this.monthName[this.month] + ' ' + this.year + 'г.'}</span>
            </div>
        </div>
    }

    public initBody(): JSX.Element {
        const TR: Array<JSX.Element> = [];
        const obj = this;
        let date = new DateF(this.dateStart);
        for (let i = 1; i <= this.daysInMonth; i++) {
            // Тотал
            if (i == 1) {
                TR.push(<td>
                    <div className="work-td-dummy">
                        <span className="work-td-dummy-text" date-total-month={String(date.getMonth())} >0ч</span>
                    </div>
                </td>);
            }
            // тотал сл. Месяца
            if (date.getMonth() != this.dateStart.getMonth() && date.getDate() == 1) {
                TR.push(<td>
                    <div className="work-td-dummy">
                        <span className="work-td-dummy-text" date-total-month={String(date.getMonth())} >0ч</span>
                    </div>
                </td>);
            }
            // Тело даты
            const tb: JSX.Element[] = [];
            let br:Array<any> = this.dateBesy[date.format()]?.break || [];
            const isBreak = br.length > 0 ? 'work-is-break' : '';

            br.forEach((e: {to:string, from:string}) => {
                tb.push(<p data-break-from={e.from} data-break-to={e.to}>Перерыв</p >)
            });
            
            TR.push(<td >
                <div className={'work-td-tab ' + isBreak} data-date={date.format()} data-open={this.dateBesy[date.format()]?'on':'off'} onclick={clickOnDate}>
                    <span className="work-td-from" data-from={this.dateBesy[date.format()]?.from || '09:00' }></span>
                    <span className="work-td-to" data-to={this.dateBesy[date.format()]?.to || '18:00'}></span>
                    <div className="work-td-info">{...tb}</div>
                </div>
            </td>);
            date.setDate(date.getDate() + 1);
        }
        function clickOnDate(ev: MouseEvent) {
            const $el = $(this);
           
            if (obj.clickDate) { 
                obj.clickDate($(this).find('[data-from]'), $(this).find('[data-to]'));
            }
            const toggle = () => {
                $el.find('[data-from]').data('from', obj.dateBesy[$el.data('date')]?.from);
                $el.find('[data-to]').data('to', obj.dateBesy[$el.data('date')]?.to);
                return obj.dateBesy[$el.data('date')] ? 'on' : 'off';
            }
            $(this).data('open', $(this).data('open') == 'on' || $(this).data('open') == 'off' ? 'select' : toggle());
            if (obj.clickDateAffter) { 
                 obj.clickDateAffter($(this).find('[data-from]'), $(this).find('[data-to]'));
            }
            obj.calcTotal();
        }
        return <tr>{...TR}</tr>;
    }

    public setTime(from: string, to: string) {
        this.contaniers.find('[data-open="select"]').each(function ($el: Rocet) {
            $el.find('[data-from]').data('from', from);
            $el.find('[data-to]').data('to', to)
        });
        this.calcTotal();
    }

    public addTimeBraek(data: Array<any>) {
        this.contaniers.find('[data-open="select"]').each(function ($el: Rocet) {
            const $els = $el.find('.work-td-info');
            $els.item().innerHTML = '';
            data.forEach(function (breaks: any) {
                $els.add(<p data-break-from={breaks.from} data-break-to={breaks.to}> Перерыв</p >)
            });
        });
    }

    public getSelect() { 
        const $data:any = {};
        this.contaniers.find('[data-open="select"]').each(function (el: Rocet) {
            let breaks: any = [];
            el.find('.work-td-info').find('p').each((elp: Rocet) => {
                breaks.push({
                    from: elp.data('breakFrom'),
                    to: elp.data('breakTo')
                })
            });

             $data[el.data('date')] = {
                 from: el.find('[data-from]').data('from'),
                 to: el.find('[data-to]').data('to'),
                 break: breaks
             }
         });
        return $data;
    }

    public nextBtn(obj: WorkShedule) {
        obj.dateStart.setMonth(obj.dateStart.getMonth() + 1);
        if (obj.dateStart.getFullYear() == new Date().getFullYear() && obj.dateStart.getMonth() != new Date().getMonth()) {
            obj.dateStart.setDate(1);
        }
        obj.build();
    }

    public backBtn(obj: WorkShedule) {
        let bufDate = new DateF(this.dateStart);
        bufDate.setMonth(bufDate.getMonth() - 1)
        if (bufDate.getFullYear() == new Date().getFullYear() && bufDate.getMonth() < new Date().getMonth()) {
            new Fire({header: 'Ошибка Даты', text:'Месяц не может быть меньше текущего месяца', status:'error'});
            return;
        }
        obj.dateStart.setMonth(obj.dateStart.getMonth() - 1)
        if (bufDate.getFullYear() == new Date().getFullYear() && bufDate.getMonth() == new Date().getMonth()) {
            obj.dateStart.setDate(new Date().getDate())
        } else { 
             obj.dateStart.setDate(1)
        }
        obj.build();
    }

}