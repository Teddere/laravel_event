import Moment from "moment";
import {extendMoment} from "moment-range"

export default class Month{
    constructor(month,year) {
        this.month=month;
        this.year=year;
        this.start=Moment([this.year,this.month]);
        this.end= this.start.clone().endOf('month');
    }
    // Début du mois
    getWeekStart () {
        return this.start.weekday();
    }
    // Nombre de jours par semaine
    getWeeks(){
        return this.end.week() - this.start.week() +1;
    }
    // Nombre de jours dans le mois
    getDays() {
        const momentRange = extendMoment(Moment);
        return momentRange.range(this.start,this.end).by('days');
    }
    // Mois formaté
    getFormatted(){
        return this.start.format('MMMM YYYY');
    }

}
