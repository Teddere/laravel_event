<template>
    <MainLayout :canLogin="canLogin" :canRegister="canRegister">
        <section class="text-gray-600 body-font">
            <div class="container px-5 py-24 mx-auto">
                <div class="flex flex-col text-center w-full mb-20">
                    <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Master Cleanse Reliac Heirloom</h1>
                    <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Whatever cardigan tote bag tumblr hexagon brooklyn asymmetrical gentrify, subway tile poke farm-to-table. Franzen you probably haven't heard of them man bun deep jianbing selfies heirloom.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="block">
                        <button @click="showModal()" type="button" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Nouveau</button>
                    </div>
                    <div class="flex justify-end items-center">

                        <div class="block w-full">
                            <div v-if="selectedDate!==null">
                                <input @click="showDatePicker" readonly  type="text" :value="selectedDate['from'].format('DD-MM-YYYY')+' A '+selectedDate['to'].format('DD-MM-YYYY')"   placeholder="Recherche..." name="" id="" class=" w-full p-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 ">
                            </div>
                            <div v-else>
                                <input type="text"   readonly  @click="showDatePicker"   placeholder="Recherche..."  class="w-full p-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-blue-600 focus:border-blue-600 ">
                            </div>
                            <div class="flex justify-end">
                                <DateRangePicker :visible="isVisible" @cancel="hideDatePicker" @change="selectDate"></DateRangePicker>
                            </div>
                        </div>
                        <select v-model="selectedEvent" @change="filterContent"  class="w-full py-2 mt-1 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500 text-gray-500">
                            <option value="all">Tous les Evenements</option>
                            <option value="progarms">Evenements Programmés</option>
                            <option value="futures">Evenements Futurs</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-wrap -m-4 mt-3">
                    <div class="lg:w-1/3 sm:w-1/2 p-4" v-for="event in events.data" :key="event.id">
                        <div class="flex relative">
                            <img alt="gallery" class="absolute inset-0 w-full h-full object-cover object-center" src="https://dummyimage.com/606x366">
                            <div class="px-8 py-10 relative z-10 w-full border-4 border-gray-200 bg-white opacity-0 hover:opacity-100 transition cursor-pointer">
                                <h2 class="tracking-widest text-sm title-font font-medium text-indigo-500 mb-1">
                                    {{ formatDate(event.start) }} Au {{ formatDate(event.end) }}
                                </h2>
                                <h1 class="title-font text-lg font-medium text-gray-900 mb-3">
                                    {{event.title}}
                                </h1>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
                                <div class="text-center mt-10">
                                    <button  @click="editEvent(event)" type="button" class="p-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 mx-1">Modifier</button>
                                    <button @click="deleteEvent(event.id)" type="button" class="p-2 text-white p-2 text-white bg-red-600 rounded-lg hover:bg-red-700 mx-1">Masquer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <nav class="flex justify-end items-center mt-3">
                    <ul class="inline-flex items-center">
                        <li v-if="events.current_page!==1">
                            <Link :href="prev" class="block px-3 py-3 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 cursor-pointer">
                                <svg  class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </Link>
                        </li>
                        <li v-if="events.current_page !== events.last_page">
                            <Link :href="next" class="block px-3 py-3   leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 cursor-pointer">
                                <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                            </Link>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
        <Modal :show="show" @close="showModal">
            <header class="flex shrink-0 items-center justify-between border-b border-gray-400 p-4">
                <h1 class="text-lg font-semibold text-gray-700">
                    <span v-if="editModal">Modifier l'Evenement</span>
                    <span v-else>Nouvel Evenement</span>
                </h1>
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
                            <input type="text" v-model="eventForm.title" :class="{'border border-red-600':eventForm.errors.title }"  placeholder="Nom de l'événement" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                            <div v-if="eventForm.errors.title">
                                <InputError :message="eventForm.errors.title"></InputError>
                            </div>
                        </label>
                    </div>
                    <div class="my-2">
                        <div class="grid grid-rows-1 grid-flow-col w-full">
                            <label class="block text-sm font-medium px-2 relative">
                                <span class="text-gray-700">Date de Début</span>
                                <input   type="datetime-local" v-model="eventForm.start" :class="{'border border-red-600':eventForm.errors.start }" placeholder="Date de début et de fin" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                                <div v-if="eventForm.errors.start">
                                    <InputError :message="eventForm.errors.start"></InputError>
                                </div>
                            </label>
                            <label class="block text-sm font-medium px-2">
                                <span class="text-gray-700">Date de fin</span>
                                <input v-model="eventForm.end" type="datetime-local" :class="{'border border-red-600':eventForm.errors.end }"  placeholder="Date de début et de fin" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:border-blue-500">
                                <div v-if="eventForm.errors.end">
                                    <InputError :message="eventForm.errors.end"></InputError>
                                </div>
                            </label>
                        </div>
                        <div class="my-2">
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
                <button  v-if="editModal" form="formSubmit"  type="submit"  class="mx-2 px-4 py-2 font-medium text-white leading-5 bg-blue-600 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-blue-700 hover:border-blue-700 focus:border-blue-700 active:text-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Modifier
                </button>
                <button   v-else form="formSubmit" type="submit" class="mx-2 px-4 py-2 font-medium text-white leading-5 bg-blue-600 transition-colors duration-150 border border-gray-300 rounded-lg sm:px-4 sm:py-2 sm:w-auto active:bg-blue-700 hover:border-blue-700 focus:border-blue-700 active:text-blue-700 focus:outline-none focus:shadow-outline-blue">
                    Ajouter
                </button>
            </footer>
        </Modal>
    </MainLayout>
</template>
<script setup>
import {ref, computed} from "vue";
import { Link,useForm,router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import InputError from "@/Components/InputError.vue";
import Modal from "@/Components/Modal.vue";
import DateRangePicker from "@/Pages/DateRangePicker.vue";
import Moment from "moment"
import {useSuccessAlert,useErrorAlert,useConfirm} from "@/Shared/alert.js";



//Data
const isVisible=ref(false);
const show=ref(false);
let editModal=ref(false);
const selectedDate=ref(null);
const selectedEvent=ref('all');
const eventForm =useForm({
    id:'',
    title:'',
    start:'',
    end:'',
    status:'',
});
//Props
const props=defineProps({
    canLogin:Boolean,
    canRegister:Boolean,
    events:Object,
});
//Computed
const prev=computed(()=>{
    return props.events.prev_page_url !==null ? props.events.prev_page_url:'';
});
const next=computed(()=>{
    return props.events.next_page_url !==null ? props.events.next_page_url:'';
});

//Methodes
const formatDate=(date)=>{
    return Moment(date).format('D MMM YYYY');
}


const showModal=()=>{
    if (editModal.value==true){
        editModal.value=false;
    }
    eventForm.reset();
    show.value=!show.value;
};
const showDatePicker=()=>{
    isVisible.value=true;
    setTimeout(()=>document.addEventListener('click',hideDatePicker),0);
};
const hideDatePicker=()=>{
    isVisible.value=false;
    document.removeEventListener('click',hideDatePicker);
}
const selectDate=(days)=>{
    selectedDate.value=days;
    showDatePicker()
    filterContent(days)
}

const editEvent=(event)=>{
    showModal()
    editModal.value=true;
    eventForm.id=event.id;
    eventForm.title=event.title;
    eventForm.start=event.start.substring(0,16);
    eventForm.end=event.end.substring(0,16);
    eventForm.status= event.status;

};
const updateEvent=()=>{
    if(eventForm.id > 0){
        const dataForm={
            id:eventForm.id,
            title:eventForm.title,
            start:eventForm.start,
            end:eventForm.end,
            status:eventForm.status,
        }
        router.put(route('home.update'),dataForm,{
            onSuccess:(page)=>{
                showModal();
                useSuccessAlert('Modification reussie !!');
            },
            onError:(err)=>{
                eventForm.errors=err;
                useErrorAlert('Modification non effectuée !!');
            }
        })
    }
};
const createEvent=()=>{
    const url=route('home.store');
    const data={
        title:eventForm.title,
        start:eventForm.start,
        end:eventForm.end,
        status:eventForm.status
    };
    router.post(url,data,{
        onSuccess:(page)=>{
            showModal();
            useSuccessAlert('Evénement rajouté avec succès !!');

        },
        onError:(error)=>{
            eventForm.errors=error;
            useErrorAlert('Ajout non effectué !!');
        }
    })
};

const deleteEvent=(id)=>{
    useConfirm(id,'Êtes-vous sure de vouloir masquer cet évenement ?','Masquer',deleteId)


};
const deleteId=(id)=>{
    if(id > 0){
        router.delete(route('home.destroy',{'id':id}),{
            onSuccess:(page)=>{
                useSuccessAlert('Suppression reussie !!');
            },
            onError:()=>{
                useErrorAlert('Suppression échouée !!')
            }
        })
    }
};
const filterContent=()=>{
    if(selectedDate.value!==null) {
        const data={
            from:selectedDate.value.from.format('YYYY-MM-DD'),
            to:selectedDate.value.to.format('YYYY-MM-DD'),
            event:selectedEvent.value,
        };
        router.get(route('home.index',{from:data.from,to:data.to,status:data.event}),{},{
            replace:true,
            preserveState:true,
        });
    }else {
        router.get(route('home.index',{status:selectedEvent.value}),{},{
            replace:true,
            preserveState:true,
        });
    }


};



</script>
