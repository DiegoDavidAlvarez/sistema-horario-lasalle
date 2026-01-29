<template x-teleport="body">
    <div x-show="isEditModuloModalOpen" 
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeEditModuloModal">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
            @click="closeEditModuloModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form :action="'/admin/modulos/' + editModuloId" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">
                                Editar Módulo
                            </h3>
                        </div>

                        <!-- Campo oculto para asegurar el envío del número -->
                        <input type="hidden" name="numero_modulo" x-model="editModuloNumero">

                        <!-- Selector de Número de Módulo con Radio Buttons Estilizados -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                                Número de Módulo <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                <template x-for="num in [1, 2, 3]" :key="num">
                                    <label
                                        :class="{
                                            'bg-blue-600 border-blue-600 text-white shadow-2xl shadow-blue-500/50 scale-110 ring-4 ring-blue-300 dark:ring-blue-400': editModuloNumero == num,
                                            'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-lg hover:scale-105': editModuloNumero != num && !(editModuloExisting.includes(num) && num != editModuloOriginalNumero),
                                            'bg-slate-100 dark:bg-slate-600 border-slate-200 dark:border-slate-500 text-slate-400 dark:text-slate-500 cursor-not-allowed': editModuloExisting.includes(num) && num != editModuloOriginalNumero
                                        }"
                                        class="relative flex flex-col items-center justify-center p-6 border-4 rounded-2xl cursor-pointer transition-all duration-300 transform">
                                        <input type="radio" 
                                            name="modulo_radio_edit" 
                                            :value="num" 
                                            x-model="editModuloNumero"
                                            :disabled="editModuloExisting.includes(num) && num != editModuloOriginalNumero"
                                            class="sr-only">
                                        
                                        <!-- Contenido de la tarjeta -->
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="mb-1">
                                                <span class="text-5xl font-extrabold" x-text="num"></span>
                                            </div>
                                            <span class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full"
                                                :class="editModuloNumero == num ? 'bg-white/30' : ''"
                                                x-show="editModuloNumero == num">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                <span>Seleccionado</span>
                                            </span>
                                            
                                            <!-- Estado No disponible -->
                                            <span class="text-xs font-semibold flex items-center gap-1" 
                                                x-show="editModuloExisting.includes(num) && num != editModuloOriginalNumero">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                </svg>
                                                No disponible
                                            </span>

                                            <!-- Estado Actual -->
                                            <span class="text-xs font-semibold flex items-center gap-1 text-blue-600 dark:text-blue-400" 
                                                x-show="num == editModuloOriginalNumero && editModuloNumero != num">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                </svg>
                                                Actual
                                            </span>

                                            <span class="text-sm font-medium" 
                                                x-show="editModuloNumero != num && !(editModuloExisting.includes(num) && num != editModuloOriginalNumero) && num != editModuloOriginalNumero">
                                                Módulo <span x-text="num"></span>
                                            </span>
                                        </div>
                                        
                                        <!-- Checkmark grande y visible cuando está seleccionado -->
                                        <div x-show="editModuloNumero == num" 
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-0 rotate-180"
                                            x-transition:enter-end="opacity-100 scale-100 rotate-0"
                                            class="absolute -top-3 -right-3 bg-green-500 rounded-full p-2 shadow-2xl border-4 border-white dark:border-slate-800">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        
                                        <!-- Icono de candado para módulos no disponibles -->
                                        <div x-show="editModuloExisting.includes(num) && num != editModuloOriginalNumero" 
                                            class="absolute -top-3 -right-3 bg-red-500 rounded-full p-2 shadow-xl border-4 border-white dark:border-slate-800">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                    </label>
                                </template>
                            </div>
                            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Los módulos ocupados no están disponibles para cambio
                            </p>
                        </div>

                        <!-- Campo Nombre del Módulo -->
                        <div class="mb-4">
                            <label for="edit_modulo_nombre"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nombre del Módulo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="edit_modulo_nombre" name="nombre" x-model="editModuloNombre"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Ej: Desarrollo de Software Empresarial" required maxlength="200">
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeEditModuloModal"
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