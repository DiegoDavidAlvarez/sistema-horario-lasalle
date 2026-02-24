<template x-teleport="body">
    <div x-show="isCreateProgramaOpen" 
        x-cloak 
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-50 overflow-y-auto"
        @keydown.escape.window="closeCreateProgramaModal">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
            @click="closeCreateProgramaModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form action="{{ route('admin.programa-estudio.store') }}" method="POST">
                    <div class="p-6">
                    <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Agregar Programa de Estudios</h3>

                        @csrf
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Campo Nombre -->
                            <div data-flux-field>
                                <label for="nombre" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                                    data-flux-label>
                                    Nombre Completo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nombre" name="nombre"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                                    placeholder="Ej: Desarrollo de Sistemas de Información" value="{{ old('nombre') }}" required
                                    maxlength="255" data-flux-control>
                                @error('nombre')
                                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Campo Abreviatura -->
                            <div data-flux-field>
                                <label for="abreviatura"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1" data-flux-label>
                                    Abreviatura
                                </label>
                                <input type="text" id="abreviatura" name="abreviatura"
                                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                                    placeholder="Ej: DSI" maxlength="50" data-flux-control value="{{ old('abreviatura') }}">
                                @error('abreviatura')
                                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Nota de campos obligatorios -->
                        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">
                            Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
                        </div>
                </div>

                <div
                    class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                    <button type="button" @click="closeCreateProgramaModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium transition-colors">
                            Cancelar
                        </button>
                    <button type="submit"
                        class="flex items-center gap-3 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Guardar Programa
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</template>