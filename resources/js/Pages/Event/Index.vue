<script>
import moment from "moment";
import {router,Link,useForm} from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import DateRangePicker from "@/Pages/DateRangePicker.vue";
import {useSuccessAlert,useErrorAlert,useConfirm} from "@/Shared/alert.js";
import InputError from "@/Components/InputError.vue";


export default {
    data:function (){
        return{
            selectedEvent:'all',
            show:false,
            date: null,
            isVisibleDatePicker:false,
            editModal:false,
            eventForm:useForm({
                id:'',
                title:'',
                start:'',
                end:'',
                status:'',
            })
        }
    },
    props:{
        events:{type:Object}
    },

    methods:{
        showModal:function (){
            if (this.editModal.value==true){
                this.editModal.value=false;
            }
            this.eventForm.reset();
            return this.show=!this.show;
        },
        showDatePicker:function(){
            this.isVisibleDatePicker=true;
            setTimeout(()=>document.addEventListener('click',this.hideDatepicker),0);
        },
        hideDatepicker:function (){
            this.isVisibleDatePicker=false;
            document.removeEventListener('click',this.hideDatepicker);
        },
        dateFormatted:function (day){
            return moment(day).format('D MMM YYYY');
        },
        editEvent:function(event){
            this.showModal();
            this.editModal=true;
            this.eventForm.id=event.id;
            this.eventForm.title=event.title;
            this.eventForm.start=event.start.substring(0,16);
            this.eventForm.end=event.end.substring(0,16);
            this.eventForm.status=event.status;
        },
        createEvent:function(){
            const data={
                title:this.eventForm.title,
                start:this.eventForm.start,
                end:this.eventForm.end,
                status:this.eventForm.status,
            }
            router.post(route('dashboard.event.store'),data,{
                onSuccess:(page)=>{
                    this.showModal();
                    useSuccessAlert('Evénement rajouté avec succès !!');
                },
                onError:(err)=>{
                    this.eventForm.errors=err;
                    useErrorAlert('Ajout non effectué !!');
                }
            })
        },
        updateEvent:function (){
            if(this.eventForm.id > 0){
                const data={
                    id:this.eventForm.id,
                    title:this.eventForm.title,
                    start:this.eventForm.start,
                    end:this.eventForm.end,
                    status:this.eventForm.status,
                }
                router.put(route('dashboard.event.update'),data,{
                    onSuccess:(page)=>{
                        this.showModal()
                        useSuccessAlert('Modification reussie !!');
                    },
                    onError:(err)=>{
                        this.eventForm.errors=err;
                        useErrorAlert('Modification non effectuée !!');
                    }
                })
            }
        },
        deleteEventConfirm:function (id){
            useConfirm(id,'Êtes-vous sure de vouloir Supprimer cet évenement définitivement ?','Suppression',this.deleteEvent)
        },
        deleteEvent:function(id){
            if(id> 0){
                router.delete(route('dashboard.event.delete',{'id':id}),{
                    onSuccess:(page)=>{
                        useSuccessAlert('Suppression reussie !!');
                    },
                    onError:()=>{
                        useErrorAlert('Suppression échouée !!');
                    }
                })
            }
        },
        hideEventConfirm:function(id){
            useConfirm(id,'Êtes-vous sure de vouloir Masquer cet évenement ?','Masque',this.hideEvent)
        },
        hideEvent:function (id){
            if(id> 0){
                router.delete(route('dashboard.event.hide',{'id':id}),{
                    onSuccess:(page)=>{
                        useSuccessAlert('Evenement masqué !!');
                    },
                    onError:()=>{
                        useErrorAlert('Evenement non masqué!!');
                    }
                })
            }
        },
        selectDate:function (days){
            this.date=days;
            this.searchData();
        },

        searchData:function (){
            if(this.selectedEvent && this.date!=null){
                const data={
                    from:this.date.from.format('YYYY-MM-DD'),
                    to:this.date.to.format('YYYY-MM-DD'),
                    status:this.selectedEvent,
                }
                router.get(route('dashboard.event.index',{
                    from:data.from,
                    to:data.to,
                    status:data.status
                }),{},{
                    replace:true,
                    preserveState:true,
                })
            }
        },
    },
    computed:{
        prev:function (){
            return this.events.prev_page_url !==null ? this.events.prev_page_url:'';
        },
        next:function (){
            return this.events.next_page_url !==null ? this.events.next_page_url:'';
        },
       linksNavigates:function (){
           this.events.links.shift();
           this.events.links.pop();
           return this.events.links;
       },
    },
    components:{
        InputError,
        ResponsiveNavLink,
        Link,
        AppLayout,
        Modal,
        DateRangePicker
    }
}

</script>
<template>
    <AppLayout>
        <template #header>Evenements</template>
        <section class=" m-2 p-4">
            <div class="py-6 bg-white shadow rounded-lg">
                <div class="mx-5 grid row-start-1 grid-flow-col">
                    <h1 class="text-lg font-bold text-gray-800 uppercase">Liste des événements</h1>
                    <div class="flex justify-end items-center">
                        <div class="block w-3/4">
                            <template v-if="date!=null">
                                <input @click="showDatePicker()" type="text" readonly :value="'Du '+date['from'].format('DD-MMM-YY') + ' Au '+date['to'].format('DD-MMM-YY')"  placeholder="Recherche..." class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                            </template>
                            <template v-else>
                                <input @click="showDatePicker()" type="text" readonly  placeholder="Recherche..." class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                            </template>
                            <DateRangePicker :visible="isVisibleDatePicker" @cancel="hideDatepicker" @change="selectDate"></DateRangePicker>
                        </div>
                        <div class="block w-1/4 ml-2">
                            <select v-model="selectedEvent"  class="w-full py-2 mt-1 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 text-gray-500">
                                <option value="all">Tous les Evenements</option>
                                <option value="progarms">Evenements Programmés</option>
                                <option value="futures">Evenements Futurs</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="m-6  overflow-hidden">
                    <div class="my-3 ">
                        <button @click="showModal()" type="button" class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-md active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            Nouveau
                        </button>
                    </div>
                    <div class="w-full overflow-x-auto ">
                        <template v-if="events.data.length > 0">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b  bg-gray-50 ">
                                    <th class="px-4 py-3">Nom</th>
                                    <th class="px-4 py-3">Début</th>
                                    <th class="px-4 py-3">Fin</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y">
                                <tr class="text-gray-700" v-for="event in events.data">
                                    <td class="px-4 py-3 font-semibold">{{event.title}}</td>
                                    <td class="px-4 py-3 text-sm">{{dateFormatted(event.start)}}</td>
                                    <td class="px-4 py-3 text-sm">{{dateFormatted(event.end)}}</td>
                                    <td class="flex justify-end gap-2 items-center py-3">
                                        <button @click="editEvent(event)" type="button" class="py-1 px-2 text-slate-100 text-sm rounded-lg text-center bg-blue-600 hover:bg-blue-700 duration-150">
                                            Modifier
                                        </button>
                                        <button @click="hideEventConfirm(event.id)" v-if="event.status=='futures'" type="button" class="py-1 px-2 text-slate-100 text-sm rounded-lg text-center bg-yellow-600 hover:bg-yellow-700 duration-150">
                                            Masquer
                                        </button>
                                        <button @click="deleteEventConfirm(event.id)" type="button" class="py-1 px-2 text-slate-100 text-sm rounded-lg text-center bg-red-600 hover:bg-red-700 duration-150">
                                            Supprimer
                                        </button>

                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <nav class="flex items-center justify-end">
                                <div class="isolate inline-flex -space-x-px rounded-md shadow-sm">
                                    <template v-if="events.current_page!==1">
                                        <Link :href="prev" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-500 ring-1 ring-inset ring-gray-200  hover:bg-gray-200 focus:z-20 focus:outline-offset-0">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                            </svg>
                                        </Link>
                                    </template>
                                    <template v-for="link in linksNavigates">
                                        <Link :href="link.url" :class="{'text-white bg-blue-600':link.active}" class="relative px-4 py-2 inline-flex items-center text-sm font-semibold text-gray-500 ring-1 ring-inset ring-gray-300 hover:bg-gray-200 focus:z-20 focus:outline-offset-0">
                                            {{link.label}}
                                        </Link>
                                    </template>
                                    <template v-if="events.current_page !== events.last_page">
                                        <Link :href="next" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-500 ring-1 ring-inset ring-gray-200  hover:bg-gray-200 focus:z-20 focus:outline-offset-0">
                                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                            </svg>
                                        </Link>
                                    </template>
                                </div>
                            </nav>
                        </template>
                        <div class="text-2xl text-gray-900" v-else>
                            Aucun Evenement
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <Modal :show="show" @close="showModal()" >
            <header class="flex shrink-0 items-center justify-between border-b border-gray-400 p-4">
                <h1 class="text-lg font-semibold text-gray-700">Nouvel Evenement</h1>
                <button @click="showModal()" type="button" class="w-8 h-8 text-gray-400 transition-colors duration-150 rounded border hover:text-gray-700 hover:border-gray-700">
                    <svg class="w-6 h-6 inline-block" fill="currentColor" viewBox="0 0 20 20" role="img" aria-hidden="true">
                        <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" fill-rule="evenodd"></path>
                    </svg>
                </button>
            </header>
            <section class="mt-4 mb-6">
                <form class="p-1" @submit.prevent="editModal ? updateEvent() : createEvent()" id="formSubmit">
                    <div class="my-2">
                        <label class="block text-sm font-medium px-2">
                            <span class="text-gray-700">Nom d'événement</span>
                            <input type="text" v-model="eventForm.title" :class="{'border border-red-600':eventForm.errors.title}"  placeholder="Nom de l'événement" class="block mt-1 w-full px-3 py-2 border border-gray-300  rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                            <div v-if="eventForm.errors.title">
                                <InputError :message="eventForm.errors.title"></InputError>
                            </div>
                        </label>
                    </div>
                    <div class="my-2">
                        <div class="grid grid-rows-1 grid-flow-col w-full">
                            <label class="block text-sm font-medium px-2">
                                <span class="text-gray-700">Date de Début</span>
                                <input type="datetime-local" v-model="eventForm.start" :class="{'border border-red-600':eventForm.errors.start}" placeholder="Date de début et de fin" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                                <div v-if="eventForm.errors.start">
                                    <InputError :message="eventForm.errors.start"></InputError>
                                </div>
                            </label>
                            <label class="block text-sm font-medium px-2">
                                <span class="text-gray-700">Date de fin</span>
                                <input type="datetime-local" v-model="eventForm.end" :class="{'border border-red-600':eventForm.errors.end}"  placeholder="Date de début et de fin" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                                <div v-if="eventForm.errors.end">
                                    <InputError :message="eventForm.errors.end"></InputError>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="my-2">
                        <div class="grid grid-cols-2">
                            <label class="block text-sm font-medium px-2 w-full">
                                <span class="text-gray-700">Evenement</span>
                                <select v-model="eventForm.status" :class="{'border border-red-600':eventForm.errors.status}" class="block mt-1 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                                    <option value="progarms">Evenement Programmé</option>
                                    <option value="futures">Evenement Avenir</option>
                                </select>
                                <div v-if="eventForm.errors.status">
                                    <InputError :message="eventForm.errors.status"></InputError>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </section>
            <footer class="flex shrink-0 flex-wrap items-center justify-end p-4 bg-gray-50 border-t border-gray-400">
                <button @click="showModal()" type="button" class="px-4 py-2 font-medium leading-5 text-gray-700 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-transparent hover:border-gray-500 focus:border-gray-500 active:text-gray-500 focus:outline-none focus:shadow-outline-gray">
                    Annuler
                </button>
                <button type="submit" form="formSubmit" class="mx-2 px-4 py-2 font-medium text-white leading-5 bg-blue-600 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-blue-700 hover:border-blue-700 focus:border-blue-700 active:text-blue-700 focus:outline-none focus:shadow-outline-blue">
                    <template v-if="editModal">Modifier</template>
                    <template v-else>Ajouter</template>
                </button>

            </footer>
        </Modal>
    </AppLayout>

</template>
