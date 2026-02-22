<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ClipboardDocumentCheckIcon, 
    PlusIcon, 
    MagnifyingGlassIcon,
    ArrowUpRightIcon,
    FunnelIcon,
    AdjustmentsHorizontalIcon
} from '@heroicons/vue-24/outline';

const props = defineProps({
    sessions: Array
});
</script>

<template>
    <Head title="Evaluations" />

    <AuthenticatedLayout>
        <div class="p-0 sm:p-0"> <!-- Removed padding for edge-to-edge -->
            <div class="bg-white overflow-hidden shadow-xl shadow-slate-900/10 rounded-[2.5rem] min-h-[calc(100vh-4rem)] border border-slate-100 mb-0">
                
                <!-- High-Density Header -->
                <div class="px-8 py-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6 bg-gradient-to-r from-white to-slate-50/50">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <div class="p-2 bg-csired/10 rounded-xl">
                                <ClipboardDocumentCheckIcon class="h-5 w-5 text-csired" />
                            </div>
                            <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">
                                Evaluations
                            </h1>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-10">
                            Manage & track employee performance reviews
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="relative group">
                            <MagnifyingGlassIcon class="h-4 w-4 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-csired transition-colors" />
                            <input 
                                type="text" 
                                placeholder="Search sessions..." 
                                class="pl-11 pr-6 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-csired/20 focus:border-csired/30 rounded-2xl text-sm w-full md:w-64 transition-all"
                            />
                        </div>
                        
                        <button class="p-2.5 bg-slate-50 text-slate-600 hover:bg-white hover:text-csired rounded-2xl transition-all border border-transparent hover:border-slate-200 shadow-sm">
                            <FunnelIcon class="h-5 w-5" />
                        </button>

                        <Link 
                            :href="route('evaluation.index')" 
                            class="flex items-center gap-2 bg-csidark text-white px-6 py-2.5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-slate-800 transition-all hover:scale-[1.02] active:scale-95 shadow-lg shadow-slate-900/20"
                        >
                            <PlusIcon class="h-4 w-4" />
                            New Template
                        </Link>
                    </div>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-8 py-6 bg-slate-50/30">
                    <div v-for="stat in [
                        { label: 'Total Active', value: '24', color: 'text-csired' },
                        { label: 'Pending Completion', value: '8', color: 'text-orange-500' },
                        { label: 'Avg Rating', value: '4.2', color: 'text-green-600' },
                        { label: 'Next Deadline', value: 'Mar 15', color: 'text-blue-600' }
                    ]" :key="stat.label" class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ stat.label }}</p>
                            <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ stat.value }}</p>
                        </div>
                        <div :class="[stat.color, 'font-black text-xs bg-slate-50 px-3 py-1 rounded-full']">
                            +12%
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="px-0 relative">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-y border-slate-100">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Evaluatee</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Evaluator</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Template</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Submitted</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <tr v-for="session in sessions" :key="session.id" class="group hover:bg-slate-50/80 transition-all duration-300">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center font-black text-slate-400 text-sm group-hover:scale-110 transition-transform">
                                            {{ session.evaluatee.split(' ').map(n => n[0]).join('') }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ session.evaluatee }}</p>
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Grade 12</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    <p class="font-medium text-slate-600">{{ session.evaluator }}</p>
                                </td>
                                <td class="px-8 py-4 font-medium text-slate-600">{{ session.template }}</td>
                                <td class="px-8 py-4">
                                    <span :class="[
                                        'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border',
                                        session.status === 'completed' ? 'bg-green-50 text-green-700 border-green-100' : 'bg-orange-50 text-orange-700 border-orange-100'
                                    ]">
                                        {{ session.status }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-sm text-slate-500">{{ session.submitted_at || 'N/A' }}</td>
                                <td class="px-8 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button class="p-2 text-slate-400 hover:text-csidark hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm">
                                            <AdjustmentsHorizontalIcon class="h-4 w-4" />
                                        </button>
                                        <button class="p-2 text-slate-400 hover:text-csired hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm">
                                            <ArrowUpRightIcon class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="sessions.length === 0">
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <ClipboardDocumentCheckIcon class="h-12 w-12 text-slate-200" />
                                        <p class="font-black text-slate-300 uppercase tracking-widest">No evaluation sessions found</p>
                                        <button class="mt-2 text-csired text-[10px] font-black uppercase tracking-widest hover:underline">Start a new session</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
