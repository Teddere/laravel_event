<script>
import moment from "moment";
import Month from "../Modules/Month.js";
import '../Modules/langage.js';
moment.locale('lang-fr')
export default {
    emits:['change','cancel'],
    data:function (){
        const initialDate=moment()
        return {
            dateEnter:initialDate,
            listWeek:['Lu','Ma','Me','Je','Ve','Sa','Di'],
            currentDate: new Month(initialDate.month(),initialDate.year()),
            selectFrom:null,
            selectTo:null,
        }
    },
    props:{
        visible:{type: Boolean, default: false,required:true},
    },
    methods:{
        /**Date selectionnée par defaut*/
        isSelected:function (day){
            if(this.selectTo==null && day.isSame(this.dateEnter.format('YYYY-MM-DD'))){
                this.selectFrom=day.clone();
                return true;
            }
            else if(this.selectTo !=null && day.isSame(this.dateEnter.format('YYYY-MM-DD'))){
                if(this.selectTo > day){
                    this.selectFrom=day.clone();
                }else{
                    this.selectTo=day.clone();
                }
                return true;
            }
        },
        /**Selection de date*/
        selectDate:function(day){
            if(this.selectFrom !=null && this.selectFrom <= day){
                this.selectTo=day.clone();
            }else {
                this.selectFrom=day.clone();
            }
            this.dateEnter=day.clone();
        },
        removeDateSelect:function (){
            this.selectTo=null;
        },
        /**Validation de Date*/
        submitDate:function (){
            const days=Array()
            if(this.selectFrom !=null && this.selectFrom<=this.selectTo){
                days['from']=this.selectFrom;
                days['to']=this.selectTo;
                this.$emit('change',days)
            }
        },
        /**Fermeture de DatePicker*/
        cancel:function (){
            this.$emit('cancel');
        },
        nextMonth:function (){
            let month=this.currentDate.month + 1;
            let year=this.currentDate.year;
            if(month > 11){
                year+=1;
                month=0
            }
            this.currentDate=new Month(month,year);
        },
        prevMonth:function (){
            let month=this.currentDate.month -1;
            let year=this.currentDate.year
            if(month < 0){
                year-=1;
                month=11;
            }
            this.currentDate=new Month(month,year);
        },
    },
    computed:{
        classWeek:function (){
            return 'has-'+this.currentDate.getWeeks()+'-weeks';
        },
    },
    watch:{
//           selectFrom(newValue,oldValue){
//               console.log(`New ${newValue} \n Old: ${oldValue}`);
    }
}
</script>
<template>
    <transition name="datepicker-slide">
        <div class="datepicker-container shadow-lg rounded-lg bg-gray-50" v-if="visible" @click.stop>
            <header class="datepicker-header flex justify-between items-center p-4">
                <button @click="prevMonth()" type="button" class="flex items-center justify-center p-1 rounded-xl hover:bg-gray-300">
                    <svg class="w-6 h-6 text-gray-900 stroke-current" fill="none">
                        <path
                            d="M13.25 8.75L9.75 12l3.5 3.25"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
                <div class="font-semibold text-sm">{{currentDate.getFormatted()}}</div>
                <button @click="nextMonth()" type="button" class="flex items-center justify-center p-1 rounded-xl hover:bg-gray-300" >
                    <svg class="w-6 h-6 text-gray-900 stroke-current" fill="none">
                        <path
                            d="M10.75 8.75l3.5 3.25-3.5 3.25"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
            </header>
            <section class="datepicker-body px-2">
                <div class="datepicker-week">
                    <div v-for="(day,index) in listWeek" :key="index" class="datepicker-weekday">
                        <span class="text-sm text-gray-900 font-medium">{{day}}</span>
                    </div>
                </div>
                <div class="datepicker-days" :class="classWeek">
                    <div class="datepicker-day" :style="{width:(currentDate.getWeekStart() * 45)+'px'}"></div>
                    <div class="datepicker-day" @click="selectDate(day)" v-for="(day,index) in currentDate.getDays()" :key="index" :class="{selected:isSelected(day)}" >
                        <span class="datepicker-day-effect"></span>
                        <span class="datepicker-day-text text-sm text-gray-900">{{day.format('D')}}</span>
                    </div>
                </div>
            </section>
            <footer class="datepicker-footer  p-2 border-t border-gray-600">
                <div class="flex items-center ">
                    <div v-if="selectFrom != null" class="inline-flex items-center px-1  text-sm font-medium text-center text-white bg-blue-700 rounded-lg">
                        {{selectFrom.format('DD-MM-YY')}}
                    </div>
                    <div v-if="selectFrom" class="p-1">
                        <svg class="w-6 h-6 text-gray-900 stroke-current" fill="none">
                            <path
                                d="M6.738 12.012h10.5m-4.476 4.25l4.5-4.25-4.5-4.25"
                                stroke-width="1.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                    <div v-if="selectTo !=null" class="inline-flex items-center px-1  text-sm font-medium text-center text-white bg-blue-700 rounded-lg">
                        {{selectTo.format('DD-MM-YY')}}
                        <span @click="removeDateSelect()" class="inline-flex items-center justify-center w-4 h-4 ml-2 text-xs  font-semibold text-white bg-red-500 rounded-full cursor-pointer">
                                X
                            </span>
                    </div>

                </div>
                <div class="flex justify-end gap-2 pt-1">
                    <button @click="cancel()" type="button" class="px-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-500 border border-transparent rounded-md hover:bg-red-700 focus:outline-none">
                        Annuler
                    </button>
                    <button @click="submitDate()" type="button" class="px-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none">
                        Ok
                    </button>
                </div>
            </footer>
        </div>
    </transition>
</template>
<style scoped>
.datepicker-container{
    position: absolute;
    width: 347px;
    z-index: 99;
}
.datepicker-week{
    display: flex;
    padding: 0 5px;
    margin-bottom: 10px;
}
.datepicker-week span{
    display: block;
    vertical-align: middle;
    text-align: center;
    line-height: 20px;
    width: 45px;/*day-size=45px*/
}
.datepicker-days{
    padding: 0 5px;
    height: calc(45px * 5);
    transition: height 300ms ease-in;
}
.datepicker-days.has-6-weeks{
    height: calc(45px * 6);
}
.datepicker-day{
    position: relative;
    text-align: center;
    height: 45px;
    width: 45px;
    float: left;
    cursor: pointer;
}
.datepicker-day:hover .datepicker-day-text{
    color: #fff;
}
.datepicker-day:hover .datepicker-day-effect{
    transform: scale(1);
    opacity: 0.6;
}
.datepicker-day.selected .datepicker-day-text{
    color: #fff;
}
.datepicker-day.selected .datepicker-day-effect{
    transform: scale(1);
    opacity: 1;
}
.datepicker-day-text{
    position: relative;
    transition: color 450ms cubic-bezier(0.23, 1, 0.32, 1) 0ms;
}
.datepicker-day-effect{
    position: absolute;
    left: 4px;
    top:-4px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: #2563eb;
    transform: scale(0);
    opacity: 0;
    transition: all 450ms cubic-bezier(0.23, 1, 0.32, 1) 0ms;
}
.datepicker-header button{
    cursor: pointer;
}
.datepicker-slide-enter-active,.datepicker-slide-leave-active{
    opacity: 1;
    transition: all 300ms;
    transform: translateY(0);
}
.datepicker-slide-enter-from,
.datepicker-slide-leave-to{
    opacity: 0;
    transform: translateY(-50px);
}
</style>
