import { integ } from "@rocet/integration";
import { $, Rocet } from "@rocet/rocet";
import '../css/elements/calendar.scss';
import { DateF } from "@src/Tools/DateF";

export class Calendar {

  private month: number = null;
  private year: number = null;

  private monthName = [
    'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
    'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
  ];
  private dayOfWeekName = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];

  private contaniers: Rocet;

  private firstDay: Date; //Первый день месяца (0 - воскресенье)
  private startDay: number; // День недели первого дня (0 - воскресенье)

  private daysInMonth: number; // Количество дней в текущем месяце
  private prevMonthDays: number;  // Количество дней в предыдущем месяце
  private totalCells: number; // Расчет общего количества ячеек для заполнения недель полностью
  private currentDate: Date;

  // кобеки отображения
  public renderNextMonth: (day: number, month: number, year: number) => JSX.Element | null = null;
  public renderPreviousMonth: (day: number, month: number, year: number) => JSX.Element | null = null;
  public renderThisMonth: (day: number, month: number, year: number) => JSX.Element | null = null;
  public renderToday: (day: number, month: number, year: number) => JSX.Element | null = null;

  // next back
  public clickNext: (month: number, year: number) => void | null = null
  public clickBack: (month:number, year:number) => void | null = null

  constructor(month: number, year: number) {
    this.month = month;
    this.year = year;
    this.setContanier('#calendar');
    this.currentDate = new Date();
  }
  // public setDate(date:DateF) { 
  //   this.month = date.getMonth();
  //   this.year = date.getFullYear();
  // }

  public setContanier(selector: string) {
    this.contaniers = $(selector);
  }
  public getContanier(): Rocet
  { 
    return this.contaniers
  }

  public isConteners(): boolean {
    return this.contaniers.length > 0;
  }
  private calcDay() {
    this.firstDay = new Date(this.year, this.month);
    this.startDay = this.firstDay.getDay() === 0 ? 6 : this.firstDay.getDay() - 1;
    this.daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
    this.prevMonthDays = new Date(this.year, this.month, 0).getDate();
    this.totalCells = Math.ceil((this.startDay + this.daysInMonth) / 7) * 7;
  }
  private clear() {
    let isContainer = false;
    this.contaniers.each((el: Rocet) => {
      isContainer = true;
      el.item().innerHTML = '';
    });
    if (!isContainer) {
      return console.warn('is not container calendar');
    }
  }

  public build() {
    if (this.contaniers.length == 0) {
      return;
    }
    this.clear()
    const $table = $(<table className="table-calendar">
      <thead>{...this.iniHeader()}</thead>
      <tbody>{...this.initBody()}</tbody>
    </table>);

    this.contaniers.add($table);

  }
  private initBody(): Array<JSX.Element> {
    const tbody: Array<JSX.Element> = [];
    this.calcDay();
    let dayCounter = 1;

    for (let i = 0; i < (this.totalCells / 7); i++) { // по неделям
      const row: Array<JSX.Element> = [];

      for (let j = 0; j < 7; j++) { // по дням недели
        let day = null
        const cellIndex = i * 7 + j;

        if (cellIndex < this.startDay) {
          day = this.prevMonthDays - (this.startDay - cellIndex - 1);
          row.push(this.previousMonth(day, this.month, this.year));
        } else if (dayCounter > this.daysInMonth) {
          day = dayCounter - this.daysInMonth;

          dayCounter++;
          row.push(this.nextMonth(day, this.month, this.year));
        } else {
          day = dayCounter;
          if (dayCounter === this.currentDate.getDate() &&
            this.year === this.currentDate.getFullYear() &&
            this.month === this.currentDate.getMonth()
          ) {
            row.push(this.today(day, this.month, this.year));
          } else {
            row.push(this.thisMonth(day, this.month, this.year));
          }
          dayCounter++;
        }
      }
      tbody.push(<tr>{...row}</tr>)
    }
    return tbody;
  }

  private nextMonth(day: number, month: number, year: number): JSX.Element {
    if (this.renderNextMonth) {
      return this.renderThisMonth(day, month, year)
    }
    return <td ><span className="calendar-day-previousmonth">{String(day).padStart(2, '0')}</span></td>
  }

  private previousMonth(day: number, month: number, year: number): JSX.Element {
    if (this.renderPreviousMonth) {
      return this.renderPreviousMonth(day, month, year)
    }
    return <td ><span className="calendar-day-previousmonth">{String(day).padStart(2, '0')}</span></td>
  }

  private thisMonth(day: number, month: number, year: number): JSX.Element {
    if (this.renderThisMonth) {
      return this.renderThisMonth(day, month, year)
    }
    return <td ><span className="calendar-day-thismonth">{String(day).padStart(2, '0')}</span></td>
  }

  private today(day: number, month: number, year: number): JSX.Element {
    if (this.renderToday) {
      return this.renderToday(day, month, year)
    }
    return <td ><span className="calendar-day-today">{String(day)}</span></td>
  }
  private iniHeader(): Array<JSX.Element> {
    const header: Array<JSX.Element> = [];
    const TR: Array<JSX.Element> = [];
    const breaks = () => {
      this.month--;
      if (this.month < 0) {
        this.month = 11;
        this.year--;
      }
       if (this.clickBack) { 
        this.clickBack(this.month, this.year)
      }
      this.build();
    }

    const next = () => {
      this.month++;
      if (this.month > 11) {
        this.month = 0;
        this.year++;
      }
      if (this.clickNext) { 
        this.clickNext(this.month, this.year)
      }
      this.build();
    }

    const Buttons: JSX.Element = <th colspan={'7'}>
      <button className="calendar-btn" onclick={() => breaks()} >{'<'}</button>
      <span className="calendar-date">{this.monthName[this.month] + ' ' + this.year + 'г.'}</span>
      <button className="calendar-btn" onclick={() => next()} >{'>'}</button>
    </th>;

    this.dayOfWeekName.forEach((day: string) => {
      TR.push(<th>{day}</th>)
    });
    header.push(<tr className="calendar-buttons">{Buttons}</tr>)
    header.push(<tr className="calendar-week">{...TR}</tr>)
    return header;
  }

}