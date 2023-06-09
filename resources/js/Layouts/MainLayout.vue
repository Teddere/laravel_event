<script setup>
import { Head, Link, router} from "@inertiajs/vue3";
import ApplicationMark from "@/Components/ApplicationMark.vue";


defineProps({
    canLogin:Boolean,
    canRegister:Boolean,
    title:{type:String, default:'Accueil'}
});

</script>
<template>
    <Head :title="title"/>
    <header class="shadow">
        <nav class="bg-gray-100 border-gray-200">
            <div class="flex flex-wrap justify-between items-center mx-auto p-4">
                <Link :href="route('home.index')" class="flex items-center gap-2">
                    <ApplicationMark class="block h-9 w-auto" />
                    <span class="self-center font-semibold whitespace-nowrap text-2xl">Lync</span>
                </Link>
                <div class="flex items-center">
                    <div v-if="canLogin">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="font-semibold text-slate-100 hover:bg-green-700 rounded-lg text-center transition duration-150 p-2 bg-green-600">
                            Dashboard
                        </Link>
                        <template v-else>
                            <Link v-if="canRegister" :href="route('register')" class="font-semibold text-slate-100 hover:bg-blue-700 rounded-lg text-center transition duration-150 p-2 bg-blue-600">
                                Créer</Link>
                            <Link :href="route('login')" class="ml-2 font-semibold text-slate-100 hover:bg-green-700 rounded-lg text-center transition duration-150 p-2 bg-green-600">
                                Connexion
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <slot></slot>
    </main>
    <footer class="bg-gray-100">
    <span class="block text-sm text-gray-500 sm:text-center p-4">
        © 2023 <Link :href="route('home.index')" class="hover:underline">Lync™</Link>.
        Tout droit réservés
    </span>
    </footer>
</template>
<style>
.active{
    border-bottom: 2px solid #2563eb;
    color: #2563eb;
}
</style>
