import { $ } from "@rocet/rocet";
import { DateF } from "@src/Tools/DateF";

let date = (new DateF()).format();
fetch('https://archive-api.open-meteo.com/v1/era5?latitude=55.64&longitude=37.27&start_date='+date+'&end_date='+date+'&hourly=temperature_2m').then(async (data) => { 
    let result = await data.json();
    console.log(result);
    let id = findNearestTimeIndex(result['hourly']['time'] || []);
    let tempr = result['hourly']['temperature_2m'][id] || false;
    if (tempr) { 
        $('[meteo]').add($(`
            <div class="flex-column-center">
            <span>Одинцово</span>
            <span>${tempr} °C</span>
            </div>
        `));
    }

}, (data) => { 
    console.log(data)
})

function findNearestTimeIndex(timeArray:Array<string>) {
    const now = new Date();
    const currentTime = now.getTime(); // текущее время в миллисекундах
    
    let nearestIndex = 0;
    let minDiff = Infinity;
    
    timeArray.forEach((timeStr, index) => {
        const timeDate = new Date(timeStr);
        const diff = Math.abs(timeDate.getTime() - currentTime);
        
        if (diff < minDiff) {
            minDiff = diff;
            nearestIndex = index;
        }
    });
    
    return nearestIndex;
}