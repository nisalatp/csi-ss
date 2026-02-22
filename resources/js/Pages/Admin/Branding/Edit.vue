<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { PhotoIcon, SwatchIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    company: Object,
});

const form = useForm({
    name: props.company.name,
    primary_color: props.company.primary_color,
    secondary_color: props.company.secondary_color,
    logo: null,
    _method: 'PATCH',
});

const submit = () => {
    form.post(route('admin.branding.update', props.company.id), {
        onSuccess: () => form.reset('logo'),
    });
};
</script>

<template>
    <Head title="Edit Branding" />

    <AuthenticatedLayout>
        <div class="p-8 max-w-5xl mx-auto">
            <!-- Header Section -->
            <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">System Branding</h1>
                    <p class="mt-2 text-slate-500 font-medium">Customize the visual identity for <span class="text-csired font-bold">{{ company.name }}</span></p>
                </div>
                
                <div class="flex items-center gap-4 bg-white/50 backdrop-blur-md px-4 py-2 rounded-2xl border border-slate-200 shadow-sm">
                    <div class="w-3 h-3 rounded-full bg-green-500 animate-pulse"></div>
                    <span class="text-xs font-black text-slate-600 uppercase tracking-widest leading-none">Live Sync Active</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left: Form -->
                <div class="lg:col-span-2 space-y-8">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Company Basics Card -->
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm hover:shadow-xl transition-all duration-500 group">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center group-hover:bg-csired/5 transition-colors">
                                    <SwatchIcon class="w-6 h-6 text-slate-400 group-hover:text-csired transition-colors" />
                                </div>
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Identity & Colors</h3>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <InputLabel for="name" value="Company Legal Name" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1" />
                                    <TextInput id="name" type="text" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold text-slate-800 focus:ring-2 focus:ring-csired/20 transition-all" v-model="form.name" required />
                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="relative">
                                        <InputLabel for="primary_color" value="Primary Theme" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1" />
                                        <div class="flex items-center bg-slate-50 rounded-2xl overflow-hidden p-2 border border-transparent focus-within:border-csired/20 transition-all">
                                            <input type="color" id="primary_color" v-model="form.primary_color" class="h-10 w-12 p-0 border-none rounded-xl cursor-pointer bg-transparent" />
                                            <input type="text" class="bg-transparent border-none focus:ring-0 font-bold text-slate-600 text-sm flex-1 uppercase" v-model="form.primary_color" maxlength="7" />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.primary_color" />
                                    </div>

                                    <div>
                                        <InputLabel for="secondary_color" value="Secondary Accent" class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1" />
                                        <div class="flex items-center bg-slate-50 rounded-2xl overflow-hidden p-2 border border-transparent focus-within:border-csired/20 transition-all">
                                            <input type="color" id="secondary_color" v-model="form.secondary_color" class="h-10 w-12 p-0 border-none rounded-xl cursor-pointer bg-transparent" />
                                            <input type="text" class="bg-transparent border-none focus:ring-0 font-bold text-slate-600 text-sm flex-1 uppercase" v-model="form.secondary_color" maxlength="7" />
                                        </div>
                                        <InputError class="mt-2" :message="form.errors.secondary_color" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Assets Card -->
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-sm hover:shadow-xl transition-all duration-500 group">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center group-hover:bg-csired/5 transition-colors">
                                    <PhotoIcon class="w-6 h-6 text-slate-400 group-hover:text-csired transition-colors" />
                                </div>
                                <h3 class="text-lg font-black text-slate-900 uppercase tracking-tight">Brand Assets</h3>
                            </div>

                            <div class="space-y-6">
                                <div class="relative group/upload">
                                    <div class="w-full h-40 rounded-[2rem] border-2 border-dashed border-slate-200 flex flex-col items-center justify-center bg-slate-50 group-hover/upload:bg-white group-hover/upload:border-csired/30 transition-all cursor-pointer relative overflow-hidden">
                                        <div v-if="!form.logo && !company.logo_path" class="text-center p-6">
                                            <PhotoIcon class="w-10 h-10 text-slate-300 mx-auto mb-2" />
                                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Click to upload logo</p>
                                        </div>
                                        <div v-else class="h-full w-full p-6 flex items-center justify-center">
                                            <img v-if="company.logo_path && !form.logo" :src="'/storage/' + company.logo_path" class="max-h-full max-w-full object-contain filter drop-shadow-md" />
                                            <p v-if="form.logo" class="text-xs font-bold text-csired uppercase tracking-widest">New logo selected: {{ form.logo.name }}</p>
                                        </div>
                                        <input type="file" id="logo" @input="form.logo = $event.target.files[0]" class="absolute inset-0 opacity-0 cursor-pointer" />
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.logo" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-6">
                            <Transition enter-from-class="opacity-0 translate-y-2" leave-to-class="opacity-0 -translate-y-2" class="transition duration-300">
                                <p v-if="form.recentlySuccessful" class="text-xs font-black text-green-600 uppercase tracking-widest flex items-center gap-2">
                                    <CheckCircleIcon class="w-4 h-4" />
                                    Settings Synchronized
                                </p>
                            </Transition>

                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="px-10 py-5 bg-csidark hover:bg-slate-800 text-white rounded-2xl font-black text-sm uppercase tracking-widest shadow-xl shadow-slate-200 hover:shadow-2xl hover:-translate-y-1 active:scale-[0.98] transition-all disabled:opacity-50"
                            >
                                <span v-if="form.processing">Processing...</span>
                                <span v-else>Update Branding</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Right: Preview -->
                <div class="space-y-8">
                    <div class="sticky top-8">
                        <div class="bg-csidark rounded-[3rem] p-8 shadow-2xl relative overflow-hidden group">
                            <!-- Decorative Circle -->
                            <div class="absolute -right-12 -top-12 w-40 h-40 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors"></div>
                            
                            <h4 class="text-white text-[10px] font-black uppercase tracking-widest mb-8 opacity-50">Theme Preview</h4>
                            
                            <div class="space-y-8 relative z-10">
                                <!-- Theme Preview Elements -->
                                <div class="space-y-3">
                                    <p class="text-white/40 text-[9px] font-bold uppercase tracking-widest ml-1">Color Palette</p>
                                    <div class="flex gap-4">
                                        <div :style="{ backgroundColor: form.primary_color }" class="h-16 flex-1 rounded-2xl shadow-inner relative overflow-hidden group/color">
                                            <span class="absolute bottom-2 left-3 text-[10px] font-black text-white/40 group-hover/color:text-white transition-colors uppercase">Primary</span>
                                        </div>
                                        <div :style="{ backgroundColor: form.secondary_color }" class="h-16 flex-1 rounded-2xl shadow-inner relative overflow-hidden group/color">
                                            <span class="absolute bottom-2 left-3 text-[10px] font-black text-white/40 group-hover/color:text-white transition-colors uppercase">Accent</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-xl">
                                    <div class="flex items-center gap-4">
                                        <div :style="{ backgroundColor: form.primary_color }" class="w-10 h-10 rounded-xl flex items-center justify-center shadow-lg">
                                            <span class="text-white font-black">S</span>
                                        </div>
                                        <div>
                                            <p class="text-white text-xs font-black uppercase tracking-wider">{{ form.name }}</p>
                                            <p class="text-white/30 text-[9px] font-bold uppercase tracking-widest">Enterprise Instance</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 p-6 bg-amber-50 rounded-[2rem] border border-amber-100 italic">
                            <p class="text-[10px] font-bold text-amber-800 uppercase tracking-widest mb-2 opacity-60">Design Tip</p>
                            <p class="text-xs text-amber-900/70 font-medium leading-relaxed">Changes to branding are reflected globally in real-time. We recommend using high-contrast corporate palettes for better readability.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}
input[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 0.75rem;
}
</style>
