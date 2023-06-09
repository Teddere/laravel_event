import moment from 'moment';
export default moment.defineLocale('lang-fr',{
    months:'Janvier_Février_Mars_Avril_Mai_Juin_Juillet_Août_Septembre_Octobre_Novembre_Décembre'.split('_'),
    monthsShort: 'Janv._Févr._Mars_Avr._Mai_Juin_Juil._Août_Sept._Oct._Nov._Déc.'.split('_'),
    monthsParseExact : true,
    weekdays : 'Lundi_Mardi_Mercredi_Jeudi_Vendredi_Samedi_Dimanche'.split('_'),
    weekdaysShort: 'Lun._Mar._Mer._Jeu._Ven._Sam._Dim.'.split('_'),
    weekdaysMin: 'Lu_Ma_Me_Je_Ve_Sa_Di'.split('_'),
    week:{
        dow:1,
    }
})

