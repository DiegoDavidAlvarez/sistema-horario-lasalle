<template x-teleport="body">
    <div x-show="isCreateModuloOpen" 
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeCreateModuloModal">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
            @click="closeCreateModuloModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10"
                x-data="{ selectedModulo: null, existingModulos: [] }">
                <form action="{{ route('admin.modulos.store') }}" method="POST">
                    @csrf
                    <!-- Campo oculto para el número de módulo -->
                    <input type="hidden" name="numero_modulo" x-model="selectedModulo">
                    <!-- Campo oculto para el ID del programa -->
                    <input type="hidden" name="programa_estudio_id" x-model="currentProgramaId">

                    <div class="p-6">
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">
                                Agregar Módulo
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                                Selecciona el número de módulo y completa la información
                            </p>
                        </div>

                        <!-- Selector de Número de Módulo con Radio Buttons Estilizados -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                                Número de Módulo <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-4">
                                <template x-for="num in [1, 2, 3]" :key="num">
                                    <label
                                        :class="{
                                            'bg-blue-600 border-blue-600 text-white shadow-2xl shadow-blue-500/50 scale-110 ring-4 ring-blue-300 dark:ring-blue-400': selectedModulo == num && !existingModulos.includes(num),
                                            'bg-white dark:bg-slate-700 border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:border-blue-400 dark:hover:border-blue-500 hover:shadow-lg hover:scale-105': selectedModulo != num && !existingModulos.includes(num),
                                            'bg-slate-100 dark:bg-slate-600 border-slate-200 dark:border-slate-500 text-slate-400 dark:text-slate-500 cursor-not-allowed': existingModulos.includes(num)
                                        }"
                                        class="relative flex flex-col items-center justify-center p-6 border-4 rounded-2xl cursor-pointer transition-all duration-300 transform">
                                        <input 
                                            type="radio" 
                                            name="modulo_radio" 
                                            :value="num" 
                                            x-model="selectedModulo"
                                            :disabled="existingModulos.includes(num)"
                                            class="sr-only">
                                        
                                        <!-- Contenido de la tarjeta -->
                                        <div class="flex flex-col items-center gap-2">
                                            <div class="mb-1">
                                                <span class="text-5xl font-extrabold" x-text="num"></span>
                                            </div>
                                            <span class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider px-3 py-1 rounded-full"
                                                :class="selectedModulo == num ? 'bg-white/30' : ''"
                                                x-show="selectedModulo == num && !existingModulos.includes(num)">
                                                <i class="fa-solid fa-check"></i>
                                                <span>Seleccionado</span>
                                            </span>
                                            <span class="text-xs font-semibold flex items-center gap-1 whitespace-nowrap" 
                                                x-show="existingModulos.includes(num)">
                                                <i class="fa-solid fa-lock"></i>
                                                No disponible
                                            </span>
                                            <span class="text-sm font-medium" 
                                                x-show="selectedModulo != num && !existingModulos.includes(num)">
                                                Módulo <span x-text="num"></span>
                                            </span>
                                        </div>
                                        
                                        <!-- Checkmark grande y visible cuando está seleccionado -->
                                        <div x-show="selectedModulo == num && !existingModulos.includes(num)" 
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 scale-0 rotate-180"
                                            x-transition:enter-end="opacity-100 scale-100 rotate-0"
                                            class="absolute -top-3 -right-3 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center shadow-2xl border-4 border-white dark:border-slate-800 text-white">
                                            <i class="fa-solid fa-check text-xs"></i>
                                        </div>
                                        
                                        <!-- Icono de candado para módulos no disponibles -->
                                        <div x-show="existingModulos.includes(num)" 
                                            class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-xl border-4 border-white dark:border-slate-800 text-white">
                                            <i class="fa-solid fa-lock text-xs"></i>
                                        </div>
                                    </label>
                                </template>
                            </div>
                            <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                <i class="fa-solid fa-circle-info"></i>
                                Los módulos ya agregados no están disponibles para selección
                            </p>
                        </div>

                        <!-- Campo Nombre del Módulo -->
                        <div class="mb-4">
                            <label for="modulo_nombre"
                                class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Nombre del Módulo <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="modulo_nombre" name="nombre"
                                class="w-full px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Ej: Desarrollo de Software Empresarial" required maxlength="200">
                        </div>

                        <!-- Nota de campos obligatorios -->
                        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400 flex items-center gap-1">
                            <span class="text-red-500 font-bold">*</span>
                            Campos obligatorios
                        </div>
                    </div>

                    <div
                        class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeCreateModuloModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                            :disabled="!selectedModulo"
                            :class="!selectedModulo ? 'opacity-50 cursor-not-allowed bg-slate-400' : 'hover:bg-blue-700 hover:shadow-xl'"
                            class="flex items-center gap-3 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Guardar Módulo</span>
                        </button>                            
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>