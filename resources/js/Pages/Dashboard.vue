<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    UsersIcon, 
    Square2StackIcon, 
    SwatchIcon, 
    ShieldCheckIcon 
} from '@heroicons/vue/24/outline';
import { computed } from 'vue';

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ users: 0, companies: 0, ous: 0 })
    }
});

const parsedStats = computed(() => [
    { name: 'Total Users', value: props.stats.users || 0, icon: UsersIcon, color: 'text-csired', bg: 'bg-red-50' },
    { name: 'Companies', value: props.stats.companies || 0, icon: SwatchIcon, color: 'text-emerald-600', bg: 'bg-emerald-100' },
    { name: 'Org Units', value: props.stats.ous || 0, icon: Square2StackIcon, color: 'text-amber-600', bg: 'bg-amber-100' },
    { name: 'Security Purity', value: '100%', icon: ShieldCheckIcon, color: 'text-indigo-600', bg: 'bg-indigo-100' },
]);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-black text-csired uppercase tracking-[0.2em] mb-1">Overview</p>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Welcome back, {{ $page.props.auth.user.name }}</h1>
                    <p class="text-slate-500 mt-1 font-medium text-sm">Managing the core CSI Shared Services infrastructure.</p>
                </div>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div v-for="stat in parsedStats" :key="stat.name" class="bg-white rounded-[2rem] border border-slate-100 px-6 py-6 flex items-center gap-5 group hover:border-csired/20 transition-all duration-300 shadow-sm hover:shadow-xl hover:shadow-red-500/5">
                <div :class="['p-4 rounded-2xl transition-transform group-hover:scale-110 duration-500', stat.bg]">
                    <component :is="stat.icon" :class="['h-6 w-6', stat.color]" />
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ stat.name }}</p>
                    <p class="text-2xl font-black text-slate-900 tracking-tight">{{ stat.value }}</p>
                </div>
            </div>
        </div>

        <!-- Quick Access Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Modules Section -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm relative overflow-hidden group">
                    <div class="absolute -right-16 -top-16 w-48 h-48 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
                    
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2 relative z-10">
                        <Square2StackIcon class="h-4 w-4" />
                        System Modules
                        <span class="ml-2 bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full text-[9px] font-black tracking-widest uppercase">2 Integrated</span>
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                        <div class="p-8 rounded-[2rem] border border-slate-100 bg-slate-50/30 group/module hover:bg-white hover:border-csired/20 hover:shadow-xl hover:shadow-red-500/5 transition-all cursor-pointer relative overflow-hidden">
                            <h4 class="font-black text-slate-900 uppercase tracking-tighter text-sm mb-2 group-hover/module:text-csired transition-colors">Ticketing System</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Enterprise support and internal request management workflow.</p>
                            <div class="mt-4 h-1 w-8 bg-slate-200 group-hover/module:w-full group-hover/module:bg-csired transition-all duration-500"></div>
                        </div>
                        <Link :href="route('evaluation.index')" class="p-8 rounded-[2rem] border border-slate-100 bg-slate-50/30 group/module hover:bg-white hover:border-csired/20 hover:shadow-xl hover:shadow-red-500/5 transition-all cursor-pointer relative overflow-hidden block text-left">
                            <h4 class="font-black text-slate-900 uppercase tracking-tighter text-sm mb-2 group-hover/module:text-csired transition-colors">Evaluation Hub</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest leading-relaxed">Performance monitoring and periodic staff evaluations.</p>
                            <div class="mt-4 h-1 w-8 bg-slate-200 group-hover/module:w-full group-hover/module:bg-csired transition-all duration-500"></div>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Branding Quick Link -->
                <div class="bg-csidark rounded-[2.5rem] p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors duration-700"></div>
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                        <SwatchIcon class="h-4 w-4" />
                        Admin Quick Action
                    </h3>
                    
                    <div class="space-y-3 relative z-10">
                        <Link :href="route('admin.branding.index')" class="flex items-center justify-between p-5 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 transition-all group/btn">
                            <div class="flex flex-col">
                                <span class="text-xs font-black uppercase tracking-widest">System Branding</span>
                                <span class="text-[8px] text-slate-400 uppercase font-bold tracking-tighter mt-0.5">Themes & Assets</span>
                            </div>
                            <SwatchIcon class="w-5 h-5 text-slate-500 group-hover/btn:text-white group-hover/btn:scale-110 transition-all" />
                        </Link>
                        
                        <Link :href="route('admin.ou.index')" class="flex items-center justify-between p-5 rounded-2xl bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/10 transition-all group/btn">
                            <div class="flex flex-col">
                                <span class="text-xs font-black uppercase tracking-widest">Org Management</span>
                                <span class="text-[8px] text-slate-400 uppercase font-bold tracking-tighter mt-0.5">Structure & Hierarchy</span>
                            </div>
                            <Square2StackIcon class="w-5 h-5 text-slate-500 group-hover/btn:text-white group-hover/btn:scale-110 transition-all" />
                        </Link>
                    </div>

                    <p class="mt-8 text-[9px] font-medium text-slate-500 leading-relaxed uppercase tracking-widest italic text-center">
                        CSI Shared Services Infrastructure v1.0
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
