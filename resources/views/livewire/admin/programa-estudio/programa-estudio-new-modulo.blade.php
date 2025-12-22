<template x-teleport="body">
    <div x-show="isModuleModalOpen" x-cloak x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeModuleModal">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
            @click="closeModuleModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form action="{{ route('admin.modulos.store') }}" method="POST">
                    @csrf
                    <!-- Campo oculto para el número de módulo -->
                    <input type="hidden" name="numero_modulo" x-model="nextModuleNumber">
                    <!-- Campo oculto para el ID del programa -->
                    <input type="hidden" name="programa_estudio_id" x-model="currentProgramaId">

                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">
                                Agregar Módulo <span x-text="nextModuleNumber"
                                    class="text-blue-600 dark:text-blue-400 font-bold"></span>
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Se agregará automáticamente como el módulo número <span x-text="nextModuleNumber"
                                    class="font-semibold"></span>
                            </p>
                        </div>

                        <!-- Campo Nombre del Módulo -->
                        <div class="mb-4">
                            <label for="module_nombre"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nombre del Módulo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="module_nombre" name="nombre"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej: Desarrollo de Software Empresarial" required maxlength="200">
                        </div>

                        <!-- Nota de campos obligatorios -->
                        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">
                            Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeModuleModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                            Guardar Módulo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>