<template x-teleport="body">
    <div x-show="isEditDocenteOpen" 
        x-cloak 
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" 
            aria-hidden="true"
            @click="closeEditDocenteModal"></div>

        <!-- Contenido del Modal -->
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form :action="'/admin/docente/' + currentId" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Editar Docente
                        </h3>

                        <!-- Campo Nombre -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                            <input type="text" 
                                x-model="currentNombres" 
                                name="nombres" 
                                id="edit-nombres"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Campo Apellidos -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Apellidos</label>
                            <input type="text" 
                                x-model="currentApellidos" 
                                name="apellidos" 
                                id="edit-apellidos"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Campo Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                            <input type="email" 
                                x-model="currentEmail" 
                                name="email"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Campo Nivel Académico -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nivel Académico</label>
                            <select x-model="currentNivelAcademico" 
                                name="nivel_academico"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccione Nivel Académico</option>
                                <option value="Bachiller">Bachiller</option>
                                <option value="Técnico">Técnico</option>
                                <option value="Licenciado">Licenciado</option>
                                <option value="Ingeniero">Ingeniero</option>
                                <option value="Magister">Magister</option>
                                <option value="Doctor">Doctor</option>
                            </select>
                        </div>

                        <!-- Campo DNI -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">DNI</label>
                            <div class="flex gap-2">
                                <input type="text" 
                                    x-model="currentDni" 
                                    name="numero_documento" 
                                    id="edit-dni"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required pattern="\d{8}" 
                                    maxlength="8"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <button type="button" 
                                    id="consultar-dni-edit"
                                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeEditDocenteModal"
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