<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { 
    ShieldCheckIcon, 
    UserIcon, 
    BuildingOfficeIcon,
    ArrowLeftIcon,
    CheckCircleIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
    user: Object,
    roles: Array,
    companies: Array,
});

const form = useForm({
    job_title: props.user.job_title || '',
    employee_id: props.user.employee_id || '',
    is_active: props.user.is_active,
    roles: props.user.roles.filter(r => r !== 'Super Admin'),
    current_company_id: props.user.current_company_id,
});

const submit = () => {
    form.patch(route('admin.users.update', props.user.id));
};

const toggleRole = (role) => {
    const index = form.roles.indexOf(role);
    if (index > -1) {
        form.roles.splice(index, 1);
    } else {
        form.roles.push(role);
    }
};
</script>

<template>
    <Head :title="'Edit ' + user.name" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Link 
                    :href="route('admin.users.index')" 
                    class="p-2 text-slate-400 hover:text-slate-900 bg-white rounded-xl border border-slate-200 transition-all shadow-sm"
                >
                    <ArrowLeftIcon class="h-4 w-4" />
                </Link>
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Edit Profile & Permissions</h2>
                    <p class="text-sm text-slate-500 font-medium">Configuring system access for {{ user.name }}</p>
                </div>
            </div>
        </template>

        <div class="max-w-4xl">
            <form @submit.prevent="submit" class="space-y-8">
                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 text-white">
                    <!-- Left Column: Primary Details -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Profile Card -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute -right-8 -top-8 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
                            
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                                <UserIcon class="h-4 w-4" />
                                Profile Identity
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 relative z-10">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Display Name</label>
                                    <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold text-sm shadow-inner">
                                        {{ user.name }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Email Address</label>
                                    <div class="px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-medium text-sm shadow-inner">
                                        {{ user.email }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Job Title</label>
                                    <input 
                                        v-model="form.job_title" 
                                        type="text" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all"
                                        placeholder="e.g. Senior Product Manager"
                                    />
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Employee ID</label>
                                    <input 
                                        v-model="form.employee_id" 
                                        type="text" 
                                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all"
                                        placeholder="CSI-XXXX"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Organization Card -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                                <BuildingOfficeIcon class="h-4 w-4" />
                                Organizational Placement
                            </h3>

                            <div class="space-y-2 max-w-md">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Primary Company Entity</label>
                                <select 
                                    v-model="form.current_company_id"
                                    class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all"
                                >
                                    <option :value="null">Global / Unassigned</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">
                                        {{ company.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Status & Access -->
                    <div class="space-y-8">
                        <!-- Status Toggle -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm overflow-hidden relative">
                            <div 
                                class="absolute top-0 right-0 p-2"
                                :class="form.is_active ? 'text-green-500' : 'text-slate-200'"
                            >
                                <CheckCircleIcon class="h-6 w-6" />
                            </div>
                            
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">System Access</h3>
                            
                            <div class="flex items-center justify-between p-4 rounded-3xl group cursor-pointer transition-all"
                                :class="form.is_active ? 'bg-green-50 border border-green-100 shadow-sm' : 'bg-slate-50 border border-slate-100'"
                                @click="form.is_active = !form.is_active"
                            >
                                <div class="flex flex-col">
                                    <span class="text-xs font-black uppercase tracking-widest" :class="form.is_active ? 'text-green-700' : 'text-slate-500'">
                                        Account {{ form.is_active ? 'Active' : 'Disabled' }}
                                    </span>
                                    <span class="text-[9px] font-medium text-slate-400 uppercase mt-0.5 tracking-tighter">
                                        {{ form.is_active ? 'Access Granted' : 'Access Restricted' }}
                                    </span>
                                </div>
                                <div class="w-12 h-6 rounded-full relative transition-colors duration-300 shadow-inner"
                                    :class="form.is_active ? 'bg-green-500' : 'bg-slate-200'"
                                >
                                    <div class="absolute top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 shadow-sm"
                                        :class="form.is_active ? 'left-7' : 'left-1'"
                                    ></div>
                                </div>
                            </div>
                        </div>

                        <!-- Roles Checklist -->
                        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <ShieldCheckIcon class="h-4 w-4" />
                                Permission Roles
                            </h3>

                            <div class="space-y-2">
                                <div v-for="role in roles" :key="role" 
                                    @click="toggleRole(role)"
                                    class="flex items-center gap-3 p-3 rounded-2xl cursor-pointer transition-all border group"
                                    :class="[
                                        form.roles.includes(role) 
                                            ? 'bg-csired/5 border-csired/20' 
                                            : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50'
                                    ]"
                                >
                                    <div class="w-5 h-5 rounded-md border-2 flex items-center justify-center transition-all"
                                        :class="[
                                            form.roles.includes(role) 
                                                ? 'bg-csired border-csired' 
                                                : 'bg-white border-slate-200 group-hover:border-slate-300'
                                        ]"
                                    >
                                        <div v-if="form.roles.includes(role)" class="w-2 h-2 bg-white rounded-full"></div>
                                    </div>
                                    <span class="text-xs font-black uppercase tracking-tight" :class="form.roles.includes(role) ? 'text-csired' : 'text-slate-500'">
                                        {{ role }}
                                    </span>
                                </div>
                            </div>

                            <p class="mt-6 text-[9px] font-medium text-slate-400 leading-relaxed uppercase tracking-widest italic">
                                * Roles grant access to specific platform modules and administration tools.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="flex items-center justify-end gap-4 p-8 bg-white/50 backdrop-blur-md rounded-[2.5rem] border border-white sticky bottom-8 shadow-2xl z-20">
                    <Link :href="route('admin.users.index')" class="text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest">Cancel</Link>
                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-csidark text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/10"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Syncing...</span>
                        <span v-else>Update Permissions</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
