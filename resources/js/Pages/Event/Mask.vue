<template>
    <app-layout>
        <template #header>Evenements Masqués</template>
        <section class=" m-2 p-4">
            <div class="py-6 bg-white shadow rounded-lg">
                <div class="mx-5 grid row-start-1 grid-flow-col">
                    <h1 class="text-lg font-bold text-gray-800 uppercase">Liste des événements</h1>
                </div>
                <div class="m-6  overflow-hidden">
                    <template v-if="events.data.length > 0">
                        <div class="w-full overflow-x-auto ">
                            <table class="w-full whitespace-nowrap" >
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
                                        <button @click="restore(event.id)" type="button" class="py-1 px-2 text-slate-100 text-sm rounded-lg text-center bg-green-600 hover:bg-green-700 duration-150">
                                            Réstaure
                                        </button>
                                        <button  @click="deleteEventConfirm(event.id)" type="button" class="py-1 px-2 text-slate-100 text-sm rounded-lg text-center bg-red-600 hover:bg-red-700 duration-150">
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
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
        </section>
    </app-layout>
</template>
<script>
import {Link,router} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import moment from "moment/moment.js";
import {useSuccessAlert,useErrorAlert,useConfirm} from "@/Shared/alert.js";
    export default {
        props:{
            events:{type:Object,required:true}
        },
        methods:{
            dateFormatted:function (day){
                return moment(day).format('D MMM YYYY');
            },
            restore:function (id){
                if(id > 0){
                    router.put(route('dashboard.event.restore'),{id:id},{
                        onSuccess:(page)=>{
                            useSuccessAlert('Evenemnt restauré !!');
                        },
                        onError:()=>{
                            useErrorAlert('Restauration échouée !!');
                        }
                    })
                }
            },
            deleteEventConfirm:function (id){
                useConfirm(id,'Êtes-vous sure de vouloir Supprimer cet évenement définitivement ?','Suppression',this.deleteEvent)
            },
            deleteEvent:function(id){
                if(id> 0){
                    router.delete(route('dashboard.event.remove',{'id':id}),{
                        onSuccess:(page)=>{
                            useSuccessAlert('Suppression reussie !!');
                        },
                        onError:()=>{
                            useErrorAlert('Suppression échouée !!');
                        }
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
            Link,
            AppLayout
        }
    }
</script>
