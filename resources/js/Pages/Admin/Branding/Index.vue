<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { SwatchIcon, PaintBrushIcon, ArrowTopRightOnSquareIcon } from '@heroicons/vue/24/outline';

defineProps({
    companies: Array,
});
</script>

<template>
    <Head title="Branding Management" />

    <AuthenticatedLayout>
        <div class="px-6 py-8 w-full">
            <!-- Header Section -->
            <div class="mb-10">
                <p class="text-[10px] font-black text-csired uppercase tracking-[0.2em] mb-1">Visual Identity</p>
                <h1 class="text-3xl font-black text-slate-900 tracking-tighter uppercase">Branding Management</h1>
                <p class="mt-1 text-slate-500 font-medium text-sm">Manage global visual identities and corporate themes across organizational units.</p>
            </div>

            <!-- Companies Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="company in companies" :key="company.id" 
                    class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm hover:shadow-2xl transition-all duration-500 group relative overflow-hidden flex flex-col justify-between h-[400px]">
                    
                    <!-- Glassmorphism Background Accent -->
                    <div :style="{ backgroundColor: company.primary_color }" class="absolute -right-12 -top-12 w-32 h-32 rounded-full blur-3xl opacity-0 group-hover:opacity-20 transition-opacity duration-700"></div>
                    
                    <div>
                        <!-- Company Header -->
                        <div class="flex items-start justify-between mb-8">
                            <div class="w-16 h-16 rounded-2xl bg-slate-50 flex items-center justify-center border border-slate-100 shadow-inner group-hover:bg-white transition-colors">
                                <img v-if="company.logo_path" :src="'/storage/' + company.logo_path" class="w-10 h-10 object-contain filter drop-shadow-sm" />
                                <SwatchIcon v-else class="w-8 h-8 text-slate-300" />
                            </div>
                            
                            <div class="flex gap-2">
                                <div :style="{ backgroundColor: company.primary_color }" class="w-4 h-4 rounded-full shadow-sm border border-white/50" title="Primary Color"></div>
                                <div :style="{ backgroundColor: company.secondary_color }" class="w-4 h-4 rounded-full shadow-sm border border-white/50" title="Secondary Color"></div>
                            </div>
                        </div>

                        <h3 class="text-xl font-black text-slate-900 mb-2 truncate group-hover:text-csired transition-colors">{{ company.name }}</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">{{ company.domain || 'Global Instance' }}</p>
                        
                        <!-- Status Badge -->
                        <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-50 text-green-600 text-[9px] font-black uppercase tracking-widest border border-green-100 mb-8">
                            <div class="w-1.5 h-1.5 rounded-full bg-green-500 mr-2"></div>
                            Operational
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <Link :href="route('admin.branding.edit', company.id)" 
                            class="flex items-center gap-2 text-xs font-black text-slate-400 hover:text-csidark uppercase tracking-widest transition-colors group/btn">
                            <PaintBrushIcon class="w-4 h-4 transition-transform group-hover/btn:rotate-12" />
                            Edit Branding
                        </Link>
                        
                        <a href="#" class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center hover:bg-csidark hover:text-white transition-all text-slate-400 shadow-sm border border-slate-100">
                            <ArrowTopRightOnSquareIcon class="w-5 h-5" />
                        </a>
                    </div>
                </div>

                <!-- Add New Company Placeholder -->
                <div class="bg-slate-50/50 rounded-[2.5rem] border-2 border-dashed border-slate-200 p-8 flex flex-col items-center justify-center text-center group hover:border-csired/20 hover:bg-white transition-all duration-500 cursor-not-allowed">
                    <div class="w-16 h-16 rounded-full bg-white flex items-center justify-center mb-4 shadow-sm group-hover:scale-110 transition-transform">
                        <span class="text-3xl text-slate-300 font-light">+</span>
                    </div>
                    <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest">Provision New Company</h4>
                    <p class="text-[10px] text-slate-300 font-medium mt-1 uppercase tracking-widest italic">(Enterprise Admin Only)</p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
