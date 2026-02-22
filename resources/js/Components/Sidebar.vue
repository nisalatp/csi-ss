<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { 
    HomeIcon,
    ClipboardDocumentCheckIcon
} from '@heroicons/vue/24/outline';
const props = defineProps({
    auth: Object,
    collapsed: {
        type: Boolean,
        default: false
    }
});

const page = usePage();
const permissions = computed(() => page.props.auth.user.permissions || []);
const roles = computed(() => page.props.auth.user.roles || []);

const hasPermission = (permission) => permissions.value.includes(permission);
const isSuperAdmin = computed(() => roles.value.includes('Super Admin'));

const navigation = [
    { name: 'Dashboard', href: route('dashboard'), icon: HomeIcon, current: route().current('dashboard') },
    { name: 'Evaluations', href: route('evaluation.index'), icon: ClipboardDocumentCheckIcon, current: route().current('evaluation.*') },
];
</script>

<template>
    <div class="flex h-full flex-col bg-white border-r border-slate-200 transition-all duration-300 overflow-hidden" :class="[collapsed ? 'w-20' : 'w-64']">
        <!-- Logo Area -->
        <div class="flex h-16 shrink-0 items-center border-b border-slate-100 transition-all duration-300" :class="[collapsed ? 'px-0 justify-center' : 'px-6']">
            <Link :href="route('dashboard')" class="flex items-center gap-2">
                <div class="w-10 h-10 bg-csired rounded-xl flex items-center justify-center shadow-lg shadow-red-500/20 transform transition-transform hover:scale-105">
                    <span class="text-white font-black text-xl">S</span>
                </div>
                <span v-if="!collapsed" class="font-black text-slate-800 tracking-tighter text-lg">SharedServices</span>
            </Link>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-8 no-scrollbar">
            <!-- User Section -->
            <div>
                <h3 v-if="!collapsed" class="px-2 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Portal</h3>
                <div class="space-y-1.5" :class="{ 'flex flex-col items-center': collapsed }">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :title="collapsed ? item.name : ''"
                        :class="[
                            item.current 
                                ? 'bg-red-50 text-csired shadow-sm' 
                                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900',
                            'group flex items-center transition-all duration-200 rounded-xl',
                            collapsed ? 'p-3 justify-center' : 'p-3 text-sm font-bold'
                        ]"
                    >
                        <component :is="item.icon" :class="[collapsed ? 'h-6 w-6' : 'mr-3 h-5 w-5', 'shrink-0 transition-colors']" />
                        <span v-if="!collapsed" class="truncate">{{ item.name }}</span>
                    </Link>
                </div>
            </div>

        </nav>

        <div class="border-t border-slate-100 p-4 transition-all duration-300" :class="[collapsed ? 'px-0' : 'p-4']">
            <div class="flex items-center gap-3" :class="{ 'justify-center': collapsed }">
                <div v-if="auth.user.profile_photo_path" class="h-9 w-9 rounded-xl overflow-hidden border-2 border-slate-100 shadow-sm shrink-0">
                    <img :src="'/storage/' + auth.user.profile_photo_path" class="w-full h-full object-cover" />
                </div>
                <div v-else class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-black border-2 border-slate-200 shrink-0 shadow-sm">
                    {{ auth.user.name.charAt(0) }}
                </div>
                <div v-if="!collapsed" class="flex-1 min-w-0">
                    <p class="text-xs font-black text-slate-900 truncate uppercase tracking-wider">{{ auth.user.name }}</p>
                    <p class="text-[10px] text-slate-500 truncate font-bold uppercase tracking-widest">{{ auth.user.job_title || 'Employee' }}</p>
                </div>
            </div>
        </div>
    </div>
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
