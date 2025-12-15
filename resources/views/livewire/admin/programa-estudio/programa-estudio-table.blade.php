<div class="w-full py-8 px-4 sm:px-6 lg:px-8" x-data="programaList()">
    <!-- Notificaciones -->
    @if (session('success'))
        <script>
            SwalThemed.success("¡Éxito!", "{{ session('success') }}");
        </script>
    @endif

    @if ($errors->any())
        <script>
            SwalThemed.fire({
                icon: 'error',
                title: 'Error',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                iconColor: '#ef4444'
            });
        </script>
    @endif

    <!-- Lista de Programas -->
    <div
        class="w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
        <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">Programas de Estudio</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Gestiona los programas y sus módulos</p>
                </div>
                <!-- Filtro de Estado -->
                <select wire:model.live="estadoFilter"
                    class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="todos">Todos</option>
                </select>
            </div>
        </div>

        <div class="p-6 space-y-4">
            @forelse ($programas as $programa)
                @php
                    $modulosCount = $programa->modulos->count();
                    $siguienteNumero = $modulosCount + 1;
                @endphp

                <div
                    class="bg-slate-50 dark:bg-slate-700/50 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-600 transition-all duration-200 hover:shadow-lg hover:border-slate-300 dark:hover:border-slate-500">
                    <!-- Encabezado del Programa -->
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h2 class="text-xl font-semibold text-slate-800 dark:text-slate-200">
                                        {{ $programa->nombre }}
                                    </h2>
                                    @if($programa->abreviatura)
                                        <span
                                            class="px-3 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium rounded-full">
                                            {{ $programa->abreviatura }}
                                        </span>
                                    @endif
                                    <span
                                        class="px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $programa->estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                        {{ ucfirst($programa->estado) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Acciones del Programa -->
                            <div class="flex gap-2">
                                @if ($programa->estado === 'inactivo')
                                    <form action="{{ route('admin.programa-estudio.restore', $programa->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/30 rounded-lg transition-colors"
                                            title="Restaurar programa">
                                            <i class="fas fa-trash-restore-alt h-5 w-5"></i>
                                        </button>
                                    </form>
                                @else
                                    <button
                                        @click="openEditModal('{{ $programa->id }}', '{{ addslashes($programa->nombre) }}', '{{ addslashes($programa->abreviatura ?? '') }}')"
                                        class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                                        title="Editar programa">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button onclick="confirmDelete('{{ $programa->id }}')"
                                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                                        title="Eliminar programa">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @endif
                                <button @click="toggleProgram({{ $programa->id }})"
                                    class="p-2 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
                                    title="Ver módulos">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transition-transform duration-200"
                                        :class="{ 'rotate-180': expandedPrograms.includes({{ $programa->id }}) }"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Formulario Eliminar (oculto) -->
                            <form id="delete-form-{{ $programa->id }}"
                                action="{{ route('admin.programa-estudio.destroy', $programa->id) }}" method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>

                        <!-- Sección de Módulos (Expandible) -->
                        <div x-show="expandedPrograms.includes({{ $programa->id }})" x-collapse class="mt-6">
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                                        Módulos ({{ $modulosCount }})
                                    </h3>
                                    <!-- Botón dinámico que muestra el siguiente número -->
                                    <button @click="openModuleModal({{ $programa->id }}, {{ $siguienteNumero }})"
                                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Agregar Módulo {{ $siguienteNumero }}
                                    </button>
                                </div>

                                <!-- Lista de módulos -->
                                <div class="space-y-2">
                                    @forelse($programa->modulos as $modulo)
                                        <div
                                            class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600 flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                <div
                                                    class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                    <span class="text-blue-600 dark:text-blue-400 font-bold">
                                                        {{ $modulo->numero_modulo }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h4 class="font-medium text-slate-800 dark:text-slate-200">
                                                        {{ $modulo->nombre }}
                                                    </h4>
                                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                                        Módulo #{{ $modulo->numero_modulo }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex gap-2">
                                                <button
                                                    @click="openEditModuleModal('{{ $modulo->id }}', '{{ addslashes($modulo->nombre) }}', {{ $modulo->numero_modulo }})"
                                                    class="p-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 rounded transition-colors"
                                                    title="Editar módulo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </button>
                                                <button onclick="confirmDeleteModulo('{{ $modulo->id }}')"
                                                    class="p-1 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded transition-colors"
                                                    title="Eliminar módulo">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Formulario Eliminar Módulo (oculto) -->
                                            <form id="delete-modulo-form-{{ $modulo->id }}"
                                                action="{{ route('admin.modulos.destroy', $modulo->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    @empty
                                        <div
                                            class="p-4 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600">
                                            <p class="text-sm text-slate-600 dark:text-slate-400 text-center">
                                                No hay módulos registrados para este programa
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Estado vacío -->
                <div class="py-12 text-center">
                    <div
                        class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-graduation-cap text-2xl text-slate-400 dark:text-slate-500"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">No hay programas registrados
                    </h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Comienza agregando un nuevo programa de
                        estudios usando el formulario superior.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if ($programas->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $programas->links() }}
            </div>
        @endif
    </div>

    <!-- Modal para Editar Programa -->
    <template x-teleport="body">
        <div x-show="isEditModalOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeEditModal">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="closeEditModal"></div>

            <!-- Contenido del Modal -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
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

                        <div
                            class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                            <button type="button" @click="closeEditModal"
                                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>

    <!-- Modal para Agregar Módulo -->
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

    <!-- Modal para Editar Módulo -->
    <template x-teleport="body">
        <div x-show="isEditModuleModalOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto"
            @keydown.escape.window="closeEditModuleModal">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="closeEditModuleModal"></div>

            <!-- Contenido del Modal -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                    <form :action="'/admin/modulos/' + editModuleId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-6">
                            <div class="mb-6">
                                <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200">
                                    Editar Módulo <span x-text="editModuleNumero"
                                        class="text-blue-600 dark:text-blue-400 font-bold"></span>
                                </h3>
                            </div>

                            <!-- Campo Nombre del Módulo -->
                            <div class="mb-4">
                                <label for="edit_module_nombre"
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Nombre del Módulo <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="edit_module_nombre" name="nombre" x-model="editModuleNombre"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Ej: Desarrollo de Software Empresarial" required maxlength="200">
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                            <button type="button" @click="closeEditModuleModal"
                                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    // Función para confirmar eliminación de programa
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar programa de estudio?',
            '¡Esta acción no se puede deshacer!'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Función para confirmar eliminación de módulo
    function confirmDeleteModulo(id) {
        SwalThemed.confirm(
            '¿Eliminar módulo?',
            '¡Esta acción reordenará los módulos restantes!'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-modulo-form-' + id).submit();
            }
        });
    }

    // Componente Alpine.js para la lista de programas
    function programaList() {
        return {
            expandedPrograms: [],
            isEditModalOpen: false,
            isModuleModalOpen: false,
            isEditModuleModalOpen: false,
            currentId: null,
            currentNombre: '',
            currentAbreviatura: '',
            currentProgramaId: null,
            nextModuleNumber: 1,
            editModuleId: null,
            editModuleNombre: '',
            editModuleNumero: null,

            toggleProgram(programaId) {
                const index = this.expandedPrograms.indexOf(programaId);
                if (index > -1) {
                    this.expandedPrograms.splice(index, 1);
                } else {
                    this.expandedPrograms.push(programaId);
                }
            },

            openEditModal(id, nombre, abreviatura) {
                this.currentId = id;
                this.currentNombre = nombre;
                this.currentAbreviatura = abreviatura;
                this.isEditModalOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeEditModal() {
                this.isEditModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            },

            openModuleModal(programaId, numeroModulo) {
                this.currentProgramaId = programaId;
                this.nextModuleNumber = numeroModulo;
                this.isModuleModalOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModuleModal() {
                this.isModuleModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            },

            openEditModuleModal(id, nombre, numero) {
                this.editModuleId = id;
                this.editModuleNombre = nombre;
                this.editModuleNumero = numero;
                this.isEditModuleModalOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeEditModuleModal() {
                this.isEditModuleModalOpen = false;
                document.body.classList.remove('overflow-hidden');
            }
        }
    }
</script>