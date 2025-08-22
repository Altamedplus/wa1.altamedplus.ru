
import { ajax } from "./ajax/ajax";
import "./interface"
    ;
import { $, r, Rocet } from "@rocet/rocet";
import { integ } from "@rocet/integration";
import { RocetElement } from "@rocet/RocetNode";

interface settingDatabase {
    hideColumsIndex: Array<number>,
    colors: [number, number, number, boolean] | null,
    limitTabPagination: number
    limit: number
}
export class Datatable {

    public rows: Function | null = null;
    public table: HTMLTableElement
    public ColumsElements: HTMLTableCellElement[] = []

    public buildCells: Function;
    public buildRows: Function;
    public initCallback: Function | null = null;
    public limitTabPagination = 3;
    public pagination: boolean = false;
    public statusInfo: boolean = false;
    public limitInfo: boolean = false;
    public wrapper: HTMLElement
    public infoFooter: boolean = false;
    public infoHeader: boolean = false;
    public gradateLimitedSelect = [10, 25, 100];
    public countsTab: boolean = false;
    public colors: boolean = false;
    public showColums: boolean = false;
    public isDataTableScrolling: boolean = false;

    public addElementHeader:Array<Function> = []

    public settings: settingDatabase = {
        hideColumsIndex: [],
        colors: null,
        limitTabPagination: 3,
        limit: 10
    }

    private page: {
        count: number
        limit: number
        all: number
    }

    private dataSend: {
        table: {
            search: any
            pages: any
        }
    }


    constructor(table: HTMLTableElement | string) {
        if (typeof table == "string") {
            const el = document.querySelector(`table[name=${table}]`);
            if (!el || !(el instanceof HTMLTableElement)) {
                throw new Error(`Table with name="${table}" not found or is not an HTMLTableElement`);
            }
            this.table = el;
        } else {
            this.table = table;
        }
        this.initWrapper()
        let limit = Number(this.table.getAttribute('limit'))
        this.page = {
            count: 1,
            limit: limit ? limit : 10,
            all: 0
        }
        this.init()
    }

    private setSetting() {
        if (this.loadSettingStorage()) {
            this.limitTabPagination = this.settings.limitTabPagination || 3
            this.page.limit = this.settings.limit || 10

        }
    }
    // построить таблицу
    private init() {
        this.setSetting()
        this.dataSend = {
            table: {
                search: this.getFilter(),
                pages: this.page
            }
        }

        const name = this.table.getAttribute("name");
        this.pagination = this.table.getAttribute('pagination') == '1';
        this.statusInfo = this.table.getAttribute('statusInfo') == '1';
        this.limitInfo = this.table.getAttribute('limitInfo') == '1';
        this.colors = this.table.getAttribute('colors') == '1'
        this.countsTab = this.table.getAttribute('countsTab') == '1'
        this.showColums = this.table.getAttribute('showColums') == '1'
        this.infoHeader = this.table.getAttribute('infoHeader') == '1' || this.limitInfo || this.colors || this.showColums;
        this.infoFooter = this.table.getAttribute('infoFooter') == '1' || this.statusInfo || this.pagination;
        this.isDataTableScrolling = this.table.getAttribute('scrolling') == '1'
        console.log(this.isDataTableScrolling);
        window.datatable = {
            [name]: this
        }

        if (isDevelopment) console.log("DataTable request: ", this.dataSend);

        ajax.post(this.dataSend, { datatable: name }).then((data) => {
            if (isDevelopment) console.log("DataTable response: ", data);
            this.page.all = data.pages.all;
            this.render(data.item);
        })

    }

    private initWrapper() {
        const wrapper = document.createElement('div');
        wrapper.classList.add('datatable-wrapper');
        if (this.isDataTableScrolling) {
            const $table = $(<div className="datatable-wrapper-table"></div>)
            $table.add(this.table)
            this.table.parentNode.insertBefore(wrapper, $table.item());
        } else { 
            this.table.parentNode.insertBefore(wrapper, this.table);
        }
        wrapper.appendChild(this.table);
        this.wrapper = wrapper;
    }

    private setHeadersElements() {
        this.ColumsElements = [];
        this.table.querySelector('[name=column]').querySelectorAll('th').forEach((th: HTMLTableCellElement) => {
            if (th.textContent != "") {
                this.ColumsElements.push(th);
            }
        })
    }

   private getFilter(): any {
        this.setHeadersElements();
        const filter = this.table.querySelector('[name=filter]');
        if (!filter || !(filter instanceof HTMLTableRowElement))
            return {}
        const elements: NodeListOf<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement> = filter.querySelectorAll("input, select, textarea");
        const data: any = {};

        elements.forEach((el) => {

            const sign = el.getAttribute("sign");
            const name = el.getAttribute("name");
            if (el instanceof HTMLInputElement) {
                const value = el.value;

                if (name && value) {
                    data[name] = sign ? { sign: sign, value: value } : value;
                }
            }

            if (el instanceof HTMLSelectElement) {
                const value = Array.from(el.selectedOptions).map(option => option.value);
                data[name] = sign ? { sign: sign, value: value } : value;
            }
            el.onchange = () => {
                this.init();
            }
        })
        return data;
    }

    static get(name: string): Datatable | null
    {
        if (!(window.datatable && window.datatable[name])) {
            if (isDevelopment)console.warn("not found table " + name);
            return null;
        }
        return window.datatable[name] as Datatable;
    }

    private render(item: any) {

        const colunm = this.table.querySelector("[name=column]")
        const aliasAll = colunm.querySelectorAll("[alias]")
        const tbody: Array<RocetElement> = [];

        item.forEach((row: any, indexRow: number) => {
            const TR: Array<RocetElement> = []
            aliasAll.forEach((el, indexElem: number) => {
                const alias = el.getAttribute('alias');
                const noneColumn: boolean = !(this.settings.hideColumsIndex.indexOf(indexElem) !== -1)
                let result: RocetElement = <td className={noneColumn ? "" : "d-none"}>{String(row[alias])}</td>
                if (noneColumn) {
                    this.table.querySelector('[name=column]').querySelectorAll('th').forEach((el, i: number) => {
                        if (this.settings.hideColumsIndex.indexOf(i) !== -1) {
                            el.classList.add('d-none')
                        }
                    })
                }
                if (this.buildCells) {
                    const rowBuild = this.buildCells(row, alias, indexRow, indexElem)
                    result = rowBuild || result;
                }

                TR.push(result)
            });
            let Rows: RocetElement = <tr>{...TR}</tr>
            if (this.buildRows) {
                const rows = this.buildRows(row, TR, indexRow)
                Rows = rows || Rows
            }
            tbody.push(Rows);
        });

        const table = new Rocet(this.table.querySelector('tbody'));
        if (tbody.length == 0) tbody.push(<div className="not-found">По вашему запросу ничего не найдено</div>)
        table.render(() => {
            return <tbody>{...tbody}</tbody>
        })
        if (this.infoFooter) {
            this.initInfoFooter();
        }
        if (this.infoHeader) {
            this.initInfoHeader()
        }
        if (this.settings.colors) {
            this.setColors(this.settings.colors)
        }
        if (this.initCallback) {
            this.initCallback(this);
        }
    }
    private initInfoHeader() {
        const div: HTMLElement = this.wrapper.querySelector(".info-header-table") ?? document.createElement('div');
        if (!div.classList.contains('info-header-table')) {
            div.classList.add('info-header-table');
            this.wrapper.prepend(div)
        }
        const info = new Rocet(div);
        const limitedSelect = this.initLimitInfo()
        let colors: JSX.Element
        if (this.colors) {
            colors = <div className="colors"><p>colors</p><colors onclick={(evt: any) => this.eventColors(evt, this)}></colors></div>
        }
        let colums: JSX.Element
        const select: Array<JSX.Element> = []
        if (this.showColums) {
            this.ColumsElements.forEach((th: HTMLTableCellElement, i) => {
                select.push(<options value={String(i)} selected={!(this.settings.hideColumsIndex.indexOf(i) !== -1)} onclick={(evt: any) => this.eventShowColums(evt, this)}>{th.textContent}</options>)
            })
        }

        const BlockButtons: Array<JSX.Element> = [];
        if (this.addElementHeader.length != 0) { 
            this.addElementHeader.forEach((fn:Function) => { 
                BlockButtons.push(fn(this))
            })
        }

        info.render(() => {
            return <div className="info-header-table">
                <div className="limited-select">{limitedSelect}</div>
                {/* {colors} */}
                {colums}
                { <select type="multi-issuing" multiple label="Видимость столбцов">
                    {...select}
                </select> }
                <div className="block-buttons">{...BlockButtons}</div>
            </div>
        })
        this.eventlimitedInfo();
    }

    private initLimitInfo(): JSX.Element {
        let span: JSX.Element
        if (this.limitInfo) {
            const option: Array<JSX.Element> = []
            this.gradateLimitedSelect.forEach((val: number) => option.push(<option selected={val == this.page.limit}>{String(val)}</option>))

            span = <div className="limited-info">
                <span>Показать</span>
                <select evt="database-limited-info">
                    {...option}
                </select>
                <span>Записей</span>
            </div>;
        }
        return span;
    }

    private initInfoFooter(): HTMLElement {
        const div: HTMLElement = this.wrapper.querySelector(".info-footer-table") ?? document.createElement('div');
        if (!div.classList.contains('info-footer-table')) {
            div.classList.add('info-footer-table');
            this.wrapper.append(div)
        }
         $(div).render((Rocet:Rocet) => {
            return <div className="info-footer-table">
                <div className="status">{...this.statusInit(Rocet)}</div>
                <div className="pagination">{... this.paginationInit(Rocet)}</div>
            </div>
        })
        return div;
    }

    private paginationInit(info: Rocet): Array<RocetElement> {
        const span: Array<JSX.Element> = [];
        if (this.pagination) {

            const stepAll = Math.ceil(this.page.all / this.page.limit)
            if (this.countsTab) {
                const options: Array<JSX.Element> = [];
                [3, 5, 7, 10].forEach((v) => { options.push(<option selected={this.limitTabPagination == v}>{String(v)}</option>) });
                span.push(<div className="count-tabs">
                    <p>Кол. ст</p>
                    <select onchange={(evt: any) => this.eventSelectTabsPages(evt, this)}>{...options}</select></div>)
            }
            span.push(<span className="btn btn-primary" evt="back-pagination">{'<<'}</span>)
            let endLimitTabPagination = (stepAll - this.limitTabPagination) < 0 ? 0 : stepAll - this.limitTabPagination

            // событие начало
            let isSed = false;
            if (this.page.count < this.limitTabPagination) {
                let it = this.limitTabPagination > stepAll ? (stepAll == 0 ? 1 : stepAll) : this.limitTabPagination

                for (let i = 1; i <= it; i++) {
                    span.push(<span className={"btn btn-primary" + (i == this.page.count ? " active " : "")} evt="click-pagination">{String(i)}</span>);
                }
                if (this.limitTabPagination < stepAll && stepAll > 0) {
                    span.push(<span className="btn btn-primary pagination-empty">{'...'}</span>);
                    span.push(<span className="btn btn-primary" evt="click-pagination">{String(stepAll)}</span>);
                }
                isSed = true
            } else if (this.page.count >= this.limitTabPagination && this.page.count <= endLimitTabPagination + 1 && !isSed) {
                span.push(<span className={"btn btn-primary"} evt="click-pagination">{String(1)}</span>);
                span.push(<span className="btn btn-primary pagination-empty">{'...'}</span>);

                for (let i = this.page.count - 1; i <= this.page.count + 1; i++) {
                    span.push(<span className={"btn btn-primary" + (i == this.page.count ? " active " : "")} evt="click-pagination">{String(i)}</span>);
                }
                span.push(<span className="btn btn-primary pagination-empty">{'...'}</span>);
                span.push(<span className="btn btn-primary" evt="click-pagination">{String(stepAll)}</span>);
                isSed = true
            } else if (this.page.count >= endLimitTabPagination && !isSed) {
                if (stepAll > this.limitTabPagination) {
                    span.push(<span className={"btn btn-primary"} evt="click-pagination">{String(1)}</span>);
                    span.push(<span className="btn btn-primary pagination-empty">{'...'}</span>);
                    endLimitTabPagination += 1
                } else {
                    endLimitTabPagination = 1
                }

                for (let i = endLimitTabPagination; i <= stepAll; i++) {
                    span.push(<span className={"btn btn-primary" + (i == this.page.count ? " active " : "")} evt="click-pagination">{String(i)}</span>);
                }
            }

            span.push(<span className="btn btn-primary" evt="next-pagination">{'>>'}</span>)
            info.addExecAll(() => this.paginationEvent(this));
        }
        return span;
    }

    private paginationEvent(datatable: Datatable) {

        const eventElementPagination: NodeListOf<HTMLSpanElement | null> = datatable.wrapper.querySelectorAll('[evt=click-pagination]');
        const eventback: HTMLSpanElement = datatable.wrapper.querySelector('[evt=back-pagination]');
        const eventnext: HTMLSpanElement = datatable.wrapper.querySelector('[evt=next-pagination]');
        eventnext.onclick = () => {
            const limitStepAll = Math.ceil(datatable.page.all / datatable.page.limit)

            datatable.page.count = datatable.page.count >= limitStepAll ? limitStepAll : datatable.page.count + 1
            datatable.init();
        }
        eventback.onclick = () => {
            const count = (datatable.page.count - 1) <= 0 ? 1 : (datatable.page.count - 1);
            datatable.page.count = count;
            datatable.init();
        }
        eventElementPagination.forEach((span) => {
            span.onclick = (evt) => {
                datatable.page.count = Number(span.textContent)
                datatable.init();
            }
        })
    }

    private eventlimitedInfo() {
        const eventLimitedInfo: HTMLSelectElement = this.wrapper.querySelector('[evt=database-limited-info]');
        eventLimitedInfo.onchange = (evt) => {
            const newlimit = Number(eventLimitedInfo.value)
            this.page.count = Math.ceil((this.page.count - 1) * this.page.limit / newlimit)
            this.page.count = this.page.count <= 0 ? 1 : this.page.count
            this.page.limit = newlimit
            this.saveSettingStorage('limit', newlimit);
            this.init();
        }
    }


    private statusInit(Rocet: Rocet): Array<JSX.Element> {

        const status: Array<JSX.Element> = []
        if (this.statusInfo) {
            const countTo = (this.page.count * this.page.limit) > this.page.all ? this.page.all : this.page.count * this.page.limit;
            let countfrom = (this.page.count * this.page.limit) - (this.page.limit - 1)
            status.push(<div className="">{
                `Записи с ${countfrom} 
                до ${countTo}
                из ${this.page.all}`}</div>)
        }
        return status;
    }

    private eventSelectTabsPages(evt: any, datatable: Datatable) {
        datatable.limitTabPagination = Number(evt.target.value)
        datatable.initInfoFooter()
    }

    private setColors(rgb: [number, number, number, boolean]) {
        const [r, g, b, islight] = rgb;
        this.saveSettingStorage('colors', [r, g, b])
        document.documentElement.style.setProperty('--colors-theme-table', `rgb(${r} ${g} ${b}`);
        document.documentElement.style.setProperty('--colors-theme-table-hover', islight ? `rgb(${r - 20} ${g - 20} ${b - 20})` : `rgb(${r} ${g} ${b} / 90%)`)
        document.documentElement.style.setProperty('--colors-theme-table-font', islight ? "#f9f9f9" : "#111f4e")
        document.documentElement.style.setProperty('--colors-theme-table-font-invert', islight ? "#111f4e" : "#f9f9f9")
        document.documentElement.style.setProperty('--colors-theme-table-line', `rgb(${r} ${g} ${b} / 40%)`)
    }

    private eventColors(evt: any, database: Datatable) {
        evt['rgb'].push(evt['islight'])
        this.saveSettingStorage('colors', evt['rgb'])
        this.setColors(evt['rgb'])
    }

    private eventShowColums(evt: MouseEvent | any, datatable: Datatable) {
        datatable.ColumsElements.forEach((th: HTMLTableCellElement, i: number) => {
            if (i == Number(evt.selectedValue)) {
                if (!evt.selected) {
                    th.classList.add('d-none')
                    datatable.hideShowColumn(i, true)
                    datatable.settings.hideColumsIndex.push(i)
                } else {
                    th.classList.remove('d-none')
                    datatable.hideShowColumn(i, false)
                    datatable.settings.hideColumsIndex = datatable.settings.hideColumsIndex.filter((ind: number) => ind !== i);
                }
                datatable.saveSettingStorage('hideColumsIndex', datatable.settings.hideColumsIndex)
            }
        })
    }

    public hideShowColumn(index: number, hideShow: boolean) {
        this.table.querySelectorAll('tr').forEach(row => {
            const cell = row.querySelector(`td:nth-child(${index + 1})`);
            if (cell) {
                hideShow ? cell.classList.add('d-none') : cell.classList.remove('d-none');
            }
        });
    }

    private loadSettingStorage() {
        let setting: any = localStorage.getItem(this.table.getAttribute('name'))
        setting = JSON.parse(setting)
        if (setting) {
            this.settings = setting
            return true;
        } else {
            return false;
        }
    }

    public saveSettingStorage(key: keyof settingDatabase, value: any) {
        this.settings[key] = value;
        localStorage.setItem(this.table.getAttribute('name'), JSON.stringify(this.settings))
    }
}