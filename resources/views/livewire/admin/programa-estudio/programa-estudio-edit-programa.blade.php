<template x-teleport="body">
    <div x-show="isEditProgramaOpen" 
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeEditProgramaModal">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
            @click="closeEditProgramaModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form :action="'/admin/programa-estudio/' + currentId" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">
                            Editar Programa de Estudio
                        </h3>

                        <!-- Campo Nombre -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nombre Completo
                            </label>
                            <input type="text" x-model="currentNombre" name="nombre"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Campo Abreviatura -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Abreviatura
                            </label>
                            <input type="text" x-model="currentAbreviatura" name="abreviatura"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeEditProgramaModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="flex items-center gap-3 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>