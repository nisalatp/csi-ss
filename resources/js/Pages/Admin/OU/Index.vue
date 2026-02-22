<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { 
    Square2StackIcon, 
    MagnifyingGlassIcon, 
    PencilSquareIcon,
    TrashIcon,
    PlusIcon,
    BuildingOfficeIcon,
    TagIcon,
    ChevronRightIcon,
    Bars3CenterLeftIcon,
    InboxIcon
} from '@heroicons/vue/24/outline';
import { debounce } from 'lodash';

const props = defineProps({
    organizationalUnits: Array,
    ouTypes: Array,
    companies: Array,
    filters: Object,
});

const activeTab = ref('units'); // 'units' or 'types'
const search = ref(props.filters.search || '');
const showOuModal = ref(false);
const showTypeModal = ref(false);
const editingOu = ref(null);
const editingType = ref(null);

watch(search, debounce((value) => {
    router.get(route('admin.ou.index'), { search: value }, { preserveState: true, replace: true });
}, 300));

// OU Form
const ouForm = useForm({
    name: '',
    description: '',
    ou_type_id: '',
    parent_ou_id: '',
    company_id: '',
    color: '#B91C1C',
    icon: 'BuildingOfficeIcon',
});

// Type Form
const typeForm = useForm({
    name: '',
    description: '',
    icon: 'TagIcon',
    color: '#475569',
    is_root_ou: false,
    parent_ou_type_id: '',
});

const openOuModal = (ou = null) => {
    editingOu.value = ou;
    if (ou) {
        ouForm.name = ou.name;
        ouForm.description = ou.description;
        ouForm.ou_type_id = ou.ou_type_id;
        ouForm.parent_ou_id = ou.parent_ou_id;
        ouForm.company_id = ou.company_id;
        ouForm.color = ou.color || '#B91C1C';
        ouForm.icon = ou.icon || 'BuildingOfficeIcon';
    } else {
        ouForm.reset();
    }
    showOuModal.value = true;
};

const openTypeModal = (type = null) => {
    editingType.value = type;
    if (type) {
        typeForm.name = type.name;
        typeForm.description = type.description;
        typeForm.icon = type.icon || 'TagIcon';
        typeForm.color = type.color || '#475569';
        typeForm.is_root_ou = !!type.is_root_ou;
        typeForm.parent_ou_type_id = type.parent_ou_type_id || '';
    } else {
        typeForm.reset();
    }
    showTypeModal.value = true;
};

const submitOu = () => {
    if (editingOu.value) {
        ouForm.patch(route('admin.ou.update', editingOu.value.id), {
            onSuccess: () => {
                showOuModal.value = false;
                ouForm.reset();
            },
        });
    } else {
        ouForm.post(route('admin.ou.store'), {
            onSuccess: () => {
                showOuModal.value = false;
                ouForm.reset();
            },
        });
    }
};

const submitType = () => {
    if (editingType.value) {
        typeForm.patch(route('admin.ou-types.update', editingType.value.id), {
            onSuccess: () => {
                showTypeModal.value = false;
                typeForm.reset();
            },
        });
    } else {
        typeForm.post(route('admin.ou-types.store'), {
            onSuccess: () => {
                showTypeModal.value = false;
                typeForm.reset();
            },
        });
    }
};

const deleteOu = (id) => {
    if (confirm('Are you sure you want to delete this Organizational Unit?')) {
        router.delete(route('admin.ou.destroy', id), { preserveScroll: true });
    }
};

const deleteType = (id) => {
    if (confirm('Are you sure you want to delete this OU Type?')) {
        router.delete(route('admin.ou-types.destroy', id), { preserveScroll: true });
    }
};

// Filter potential parents based on selected OU type hierarchy
const potentialParents = computed(() => {
    if (!ouForm.ou_type_id) return props.organizationalUnits;
    
    const selectedType = props.ouTypes.find(t => t.id === ouForm.ou_type_id);
    if (!selectedType) return props.organizationalUnits;
    
    // If it's a root type or has no parent type specified, only root OUs (or none) should be parents? 
    // Actually, if it has a parent type, only OUs of that parent type are valid.
    if (!selectedType.parent_ou_type_id) {
        return props.organizationalUnits.filter(ou => !ou.parent_ou_id);
    }
    
    return props.organizationalUnits.filter(ou => ou.ou_type_id === selectedType.parent_ou_type_id);
});
</script>

<template>
    <Head title="Org Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Organizational Units</h2>
                    <p class="text-sm text-slate-500 font-medium">Managing departments, divisions, and corporate structure.</p>
                </div>
                
                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    <!-- Tab Switcher -->
                    <div class="bg-slate-100 p-1 rounded-xl flex items-center shadow-inner border border-slate-200/50 w-full md:w-auto">
                        <button 
                            @click="activeTab = 'units'"
                            :class="[
                                activeTab === 'units' ? 'bg-white text-csidark shadow-sm' : 'text-slate-500 hover:text-slate-700',
                                'flex-1 md:flex-none px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all'
                            ]"
                        >
                            Units
                        </button>
                        <button 
                            @click="activeTab = 'types'"
                            :class="[
                                activeTab === 'types' ? 'bg-white text-csidark shadow-sm' : 'text-slate-500 hover:text-slate-700',
                                'flex-1 md:flex-none px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all'
                            ]"
                        >
                            Types
                        </button>
                    </div>

                    <div v-if="activeTab === 'units'" class="relative w-full md:w-64">
                        <MagnifyingGlassIcon class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                        <input 
                            v-model="search"
                            type="text" 
                            placeholder="Search units..." 
                            class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all shadow-sm"
                        />
                    </div>

                    <button 
                        @click="activeTab === 'units' ? openOuModal() : openTypeModal()"
                        class="w-full md:w-auto px-6 py-2 bg-csidark text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg shadow-slate-900/10 shrink-0 flex items-center justify-center gap-2"
                    >
                        <PlusIcon class="h-4 w-4 stroke-[3]" />
                        New {{ activeTab === 'units' ? 'Unit' : 'Type' }}
                    </button>
                </div>
            </div>
        </template>

        <!-- Units Table -->
        <div v-if="activeTab === 'units'" class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Identity</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Classification</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Organization</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="ou in organizationalUnits" :key="ou.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white border-2 border-white shadow-sm shrink-0"
                                        :style="{ backgroundColor: ou.color || '#B91C1C' }"
                                    >
                                        <BuildingOfficeIcon class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-slate-900 truncate leading-none mb-1 uppercase tracking-tight">{{ ou.name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest truncate">{{ ou.description || 'No description provided' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-slate-200 inline-flex items-center gap-1.5">
                                        {{ ou.type?.name || 'Unset' }}
                                    </span>
                                    <div v-if="ou.parent" class="flex items-center gap-1 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                                        <span class="truncate">{{ ou.parent.name }}</span>
                                        <ChevronRightIcon class="h-2.5 w-2.5" />
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                    <BuildingOfficeIcon class="h-3.5 w-3.5 text-slate-400" />
                                    {{ ou.company?.name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="openOuModal(ou)"
                                        class="p-2 text-slate-400 hover:text-csidark hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm"
                                    >
                                        <PencilSquareIcon class="h-4 w-4" />
                                    </button>
                                    <button 
                                        @click="deleteOu(ou.id)"
                                        class="p-2 text-slate-400 hover:text-csired hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm"
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="organizationalUnits.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <InboxIcon class="h-12 w-12 text-slate-200 mb-4" />
                                    <p class="text-slate-500 font-medium">No units found matching your search.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- OU Types Table -->
        <div v-else class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Type Architecture</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Hierarchy Logic</th>
                            <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <tr v-for="type in ouTypes" :key="type.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-xl flex items-center justify-center text-white border-2 border-white shadow-sm shrink-0"
                                        :style="{ backgroundColor: type.color || '#475569' }"
                                    >
                                        <TagIcon class="h-5 w-5" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-black text-slate-900 truncate leading-none mb-1 uppercase tracking-tight">{{ type.name }}</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest truncate">{{ type.description || 'Global classification type' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1.5">
                                    <div class="flex items-center gap-2">
                                        <span v-if="type.is_root_ou" class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                                            Root Layer
                                        </span>
                                        <span v-else class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                            Structural Layer
                                        </span>
                                    </div>
                                    <div v-if="type.parent_type" class="flex items-center gap-1.5 text-[9px] text-slate-400 font-bold uppercase tracking-widest">
                                        <span>Parent Type:</span>
                                        <span class="text-slate-900 font-black">{{ type.parent_type.name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button 
                                        @click="openTypeModal(type)"
                                        class="p-2 text-slate-400 hover:text-csidark hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm"
                                    >
                                        <PencilSquareIcon class="h-4 w-4" />
                                    </button>
                                    <button 
                                        @click="deleteType(type.id)"
                                        class="p-2 text-slate-400 hover:text-csired hover:bg-white rounded-xl transition-all border border-transparent hover:border-slate-200 shadow-sm"
                                    >
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- OU Unit Modal -->
        <div v-if="showOuModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4 py-6">
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all w-full max-w-2xl border border-white">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ editingOu ? 'Refine Unit' : 'Initialize Unit' }}</h3>
                            <p class="text-xs text-slate-500 font-medium">Defining organizational structure and hierarchy.</p>
                        </div>
                        <div class="h-12 w-12 rounded-2xl bg-red-50 flex items-center justify-center text-csired border border-red-100">
                            <Square2StackIcon class="h-6 w-6" />
                        </div>
                    </div>
                    
                    <form @submit.prevent="submitOu" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Unit Name</label>
                                <input v-model="ouForm.name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all shadow-inner" required placeholder="e.g. Sales Division">
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Unit Type</label>
                                <select v-model="ouForm.ou_type_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all" required>
                                    <option value="" disabled>Select Type</option>
                                    <option v-for="type in ouTypes" :key="type.id" :value="type.id">{{ type.name }}</option>
                                </select>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Primary Company</label>
                                <select v-model="ouForm.company_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all" required>
                                    <option value="" disabled>Select Company</option>
                                    <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Parent Entity</label>
                                <select v-model="ouForm.parent_ou_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all">
                                    <option :value="null">Independent (Root)</option>
                                    <option v-for="ou in potentialParents" :key="ou.id" :value="ou.id" v-show="!editingOu || ou.id !== editingOu.id">
                                        {{ ou.name }}
                                    </option>
                                </select>
                                <p v-if="ouForm.ou_type_id" class="text-[9px] text-slate-400 font-bold uppercase tracking-widest px-1 mt-1">
                                    * Filtered based on {{ ouTypes.find(t => t.id === ouForm.ou_type_id)?.name }} hierarchy rules
                                </p>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Brief Description</label>
                                <textarea v-model="ouForm.description" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-medium text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all" rows="2"></textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Visual ID (Color)</label>
                                <div class="flex items-center gap-3">
                                    <input v-model="ouForm.color" type="color" class="h-10 w-10 p-0 border-none rounded-lg bg-transparent cursor-pointer">
                                    <input v-model="ouForm.color" type="text" class="flex-1 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-mono font-bold text-slate-600 shadow-inner">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                            <button @click="showOuModal = false" type="button" class="text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors">Cancel</button>
                            <button type="submit" class="px-8 py-3 bg-csidark text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/10" :disabled="ouForm.processing">
                                {{ editingOu ? 'Sync Changes' : 'Initialize Unit' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- OU Type Modal -->
        <div v-if="showTypeModal" class="fixed inset-0 overflow-y-auto z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm px-4 py-6">
            <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl transform transition-all w-full max-w-lg border border-white">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">{{ editingType ? 'Edit Type' : 'Create Type' }}</h3>
                            <p class="text-xs text-slate-500 font-medium">Classifying organizational hierarchy levels.</p>
                        </div>
                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 border border-slate-200">
                            <TagIcon class="h-6 w-6" />
                        </div>
                    </div>
                    
                    <form @submit.prevent="submitType" class="space-y-6">
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Type Name</label>
                                <input v-model="typeForm.name" type="text" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all shadow-inner" required placeholder="e.g. Division, Department">
                            </div>

                            <div class="flex items-center gap-6">
                                <div class="flex items-center gap-2">
                                    <input v-model="typeForm.is_root_ou" type="checkbox" id="is_root" class="rounded text-csired focus:ring-csired/20">
                                    <label for="is_root" class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Root Level Type</label>
                                </div>
                            </div>

                            <div v-if="!typeForm.is_root_ou" class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Required Parent Type</label>
                                <select v-model="typeForm.parent_ou_type_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-bold text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all">
                                    <option value="">Any (No restriction)</option>
                                    <option v-for="type in ouTypes" :key="type.id" :value="type.id" v-show="!editingType || type.id !== editingType.id">{{ type.name }}</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Description</label>
                                <textarea v-model="typeForm.description" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-2xl text-slate-900 font-medium text-sm focus:ring-2 focus:ring-csired/20 focus:border-csired transition-all" rows="2"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Visual Theme (Color)</label>
                                <div class="flex items-center gap-3">
                                    <input v-model="typeForm.color" type="color" class="h-10 w-10 p-0 border-none rounded-lg bg-transparent cursor-pointer">
                                    <input v-model="typeForm.color" type="text" class="flex-1 px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs font-mono font-bold text-slate-600 shadow-inner">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-4">
                            <button @click="showTypeModal = false" type="button" class="text-xs font-black text-slate-400 hover:text-slate-900 uppercase tracking-widest transition-colors">Cancel</button>
                            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-xl shadow-slate-900/10" :disabled="typeForm.processing">
                                {{ editingType ? 'Update Type' : 'Create Type' }}
                            </button>
                        </div>
                    </form>
                </div>
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
