<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { 
    UserIcon, 
    MagnifyingGlassIcon, 
    PencilSquareIcon,
    ShieldCheckIcon,
    BuildingOfficeIcon
} from '@heroicons/vue/24/outline';
import { debounce } from 'lodash';

const props = defineProps({
    users: Array,
    filters: Object,
});

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(route('admin.users.index'), { search: value }, { preserveState: true, replace: true });
}, 300));

const getRoleColor = (role) => {
    switch (role) {
        case 'Super Admin': return 'bg-csidark text-white';
        case 'Admin': return 'bg-slate-800 text-white';
        case 'Evaluator': return 'bg-csired/10 text-csired border border-csired/20';
        default: return 'bg-slate-100 text-slate-600 border border-slate-200';
    }
};
</script>

<template>
    <Head title="User Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">User Management</h2>
                    <p class="text-sm text-slate-500 font-medium">Manage platform access, roles, and organizational assignments.</p>
                </div>
                
                <div class="relative max-w-sm w-full">
                    <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                    <input 
                        v-model="search"
                        type="text" 
                        placeholder="Search by name, email or ID..." 
                        class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all shadow-sm"
                    />
                </div>
            </div>
        </template>

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Organization</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Roles</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="user in users" :key="user.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 border border-slate-200 overflow-hidden shrink-0">
                                        <img v-if="user.profile_photo_path" :src="'/storage/' + user.profile_photo_path" class="w-full h-full object-cover" />
                                        <UserIcon v-else class="h-6 w-6" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-slate-900 truncate leading-none mb-1 uppercase tracking-tight">{{ user.name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest truncate">{{ user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                        <BuildingOfficeIcon class="h-3.5 w-3.5 text-slate-400" />
                                        {{ user.company || 'Unassigned' }}
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">{{ user.job_title || 'No Title' }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span 
                                    :class="[
                                        user.is_active ? 'bg-green-50 text-green-600 border-green-100' : 'bg-red-50 text-red-600 border-red-100',
                                        'inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border'
                                    ]"
                                >
                                    <span :class="[user.is_active ? 'bg-green-500' : 'bg-red-500', 'w-1 h-1 rounded-full mr-1.5']"></span>
                                    {{ user.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1.5">
                                    <span 
                                        v-for="role in user.roles" 
                                        :key="role"
                                        :class="[getRoleColor(role), 'px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest']"
                                    >
                                        {{ role }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link 
                                    :href="route('admin.users.edit', user.id)"
                                    class="p-2 text-slate-400 hover:text-csidark hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm inline-flex"
                                >
                                    <PencilSquareIcon class="h-4 w-4" />
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="users.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <UserIcon class="h-12 w-12 text-slate-200 mb-4" />
                                    <p class="text-slate-500 font-medium">No users found matching your search.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
