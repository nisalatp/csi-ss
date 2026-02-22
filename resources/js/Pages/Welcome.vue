<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    error: {
        type: String,
        default: null,
    }
});
</script>

<template>
    <Head title="Welcome to CSI Shared Services" />
    
    <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-6 relative overflow-hidden font-sans">
        <!-- Subtle Background Decorations -->
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-red-50 rounded-full blur-[120px] opacity-40"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-slate-100 rounded-full blur-[120px] opacity-40"></div>
        </div>

        <div class="max-w-2xl w-full text-center space-y-12">
            <!-- Logo Section -->
            <div class="flex justify-center animate-in fade-in duration-700">
                <img src="https://cloudsolutions.lk/wp-content/themes/csi/assets/img/csi-logo.svg" alt="Cloud Solutions International" class="h-20 w-auto" />
            </div>

            <!-- Branding Section -->
            <div class="space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-700 delay-100">
                <h2 class="text-csired font-bold tracking-[0.2em] uppercase text-xs">Innovation in Healthcare</h2>
                <h1 class="text-4xl md:text-5xl font-bold text-csidark tracking-tight">
                    CSI Shared Services <span class="text-csired">Platform</span>
                </h1>
                <p class="text-base text-slate-500 max-w-md mx-auto leading-relaxed">
                    Driving operational excellence through streamlined internal support and unified service delivery.
                </p>

                <div v-if="error" class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-xl text-sm font-medium max-w-md mx-auto mt-4 animate-in fade-in zoom-in">
                    {{ error }}
                </div>
            </div>

            <!-- Action Section -->
            <div class="bg-white p-1 rounded-[2.5rem] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.1)] border border-slate-100 animate-in fade-in zoom-in duration-1000 delay-200 overflow-hidden">
                <div class="bg-white rounded-[2.2rem] p-10 space-y-8">
                    <div class="flex justify-center">
                        <div class="p-5 flex items-center justify-center">
                             <div class="w-16 h-16 bg-csired rounded-2xl flex items-center justify-center shadow-lg shadow-red-200">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 003.5 11c0-5.523 4.477-10 10-10s10 4.477 10 10a10.003 10.003 0 01-6.73 9.424M6.701 7.961a4.105 4.105 0 00-1.87 3.39c0 2.26.11 4.544.331 6.81m0 0a2 2 0 012 2h7.339a2 2 0 012-2m-11.339-2c.008.058.012.115.012.173a5.614 5.614 0 005.613 5.614 5.614 5.614 0 005.614-5.614c0-.058-.004-.115-.012-.173"></path>
                                </svg>
                             </div>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <a
                            v-if="!$page.props.auth.user"
                            :href="route('auth.signin')"
                            class="inline-flex items-center justify-center w-full px-8 py-5 text-white font-semibold rounded-2xl shadow-xl transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] group bg-csired hover:bg-[#D31920] shadow-red-100"
                        >
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-3.5 18l1.5-6h-3l6-7.5-1.5 6h3l-6 7.5z"/>
                            </svg>
                            Sign in with Office 365
                        </a>

                        <Link
                            v-else
                            :href="route('dashboard')"
                            class="inline-flex items-center justify-center w-full px-8 py-5 text-white font-semibold rounded-2xl shadow-xl transition-all duration-300 transform hover:-translate-y-1 active:scale-[0.98] group bg-csidark hover:bg-slate-800 shadow-slate-100"
                        >
                            Enter Dashboard
                        </Link>
                        
                        <p class="text-[0.65rem] text-slate-400 font-bold uppercase tracking-widest">
                            Authorized Personnel Only &bull; Secure Enterprise Access
                        </p>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="pt-8 text-slate-400 text-[0.7rem] font-bold uppercase tracking-widest animate-in fade-in duration-1000 delay-500">
                &copy; {{ new Date().getFullYear() }} Cloud Solutions International.
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-in {
    animation-fill-mode: both;
}
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slide-in-from-bottom-4 {
    from { transform: translateY(1rem); }
    to { transform: translateY(0); }
}
@keyframes zoom-in {
    from { transform: scale(0.95); }
    to { transform: scale(1); }
}
.fade-in { animation-name: fade-in; }
.slide-in-from-bottom-4 { animation-name: slide-in-from-bottom-4; }
.zoom-in { animation-name: zoom-in; }
</style>
