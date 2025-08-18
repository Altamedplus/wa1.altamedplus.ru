export class DateF extends Date { 
    public equality(date: Date,):boolean { 
        return this.getFullYear() === date.getFullYear() &&
            this.getMonth() == date.getMonth() && this.getDate() === date.getDate();
    }
    public format($format = "Y-m-d") { 
        const year = String(this.getFullYear());
        const month = String(this.getMonth() + 1).padStart(2, '0');
        const day = String(this.getDate()).padStart(2, '0');

        $format = $format.replace('Y', year);
        $format = $format.replace('m', month);
        $format = $format.replace('d', day);
        return $format;
    }

    static timeToSeconds(timeStr: string|null) {
        // if (!timeStr) return 0; 
        let h, m, s = 0;
         [h, m, s] = timeStr.split(':').map(Number);
        return h * 3600 + m * 60 + (s||0);
    }
    static secondsToTime(totalSeconds: number, format:string = 'hh:mm')
    {
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;

        const h = String(hours);
        const m = String(minutes)
        const hh = String(hours).padStart(2, '0');
        const mm = String(minutes).padStart(2, '0');
        const ss = String(seconds).padStart(2, '0');

        format = format.replace('hh', hh);
        format = format.replace('mm', mm);
        format = format.replace('ss', ss);
        format = format.replace('h', h);
        format = format.replace('m', m);
        return format;
    }
}