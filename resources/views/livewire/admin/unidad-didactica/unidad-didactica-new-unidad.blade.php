<template x-teleport="body">
    <div x-show="isCreateUnidadOpen" x-cloak 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto">
        
        {{-- Fondo oscuro que al hacer click cierra el modal --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeCreateUnidadModal"></div>

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form action="{{ route('admin.unidad-didactica.store') }}" method="POST">
                    @csrf
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6" x-text="'Nueva Unidad Didáctica Para ' + currentProgramaNombre"></h3>

                        <!-- Programa de Estudio (Hidden) -->
                        <input type="hidden" name="programa_estudio_id" x-model="currentProgramaId">

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre de la Unidad <span class="text-red-500">*</span></label>
                            <input type="text" name="nombre" required
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej: Lógica de Programación">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- Créditos -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Créditos <span class="text-red-500">*</span></label>
                                <input type="text" name="creditos" required
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ej: 3"
                                    pattern="\d{1}" maxlength="1"
                                    oninput="this.value = this.value.replace(/[^1-9]/g, '')">
                            </div>
                            <!-- Horas -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Horas Semanales <span class="text-red-500">*</span></label>
                                <input type="text" name="horas_semanales" required
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ej: 5"
                                    pattern="\d{1}" maxlength="1"
                                    oninput="this.value = this.value.replace(/[^1-9]/g, '')">
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeCreateUnidadModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex items-center gap-3 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fa-solid fa-floppy-disk"></i>
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>