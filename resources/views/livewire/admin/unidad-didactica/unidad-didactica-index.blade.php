<div class="min-h-screen bg-slate-100 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="unidadDidacticaTable()">
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

    <!-- Contenedor Principal -->
    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                        Unidades Didácticas
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de unidades por programa de estudio</p>
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

        <!-- Lista de Programas -->
        <div class="p-6 space-y-6">
            @forelse ($programas as $programa)
                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden"
                     x-data="{ expanded: true }">
                    <!-- Cabecera del Programa -->
                    <div class="p-4 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-600 flex justify-between items-center cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                         @click="expanded = !expanded">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-chevron-right text-slate-400 transition-transform duration-200"
                               :class="{ 'rotate-90': expanded }"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $programa->nombre }}
                                </h3>
                                @if($programa->abreviatura)
                                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded">
                                        {{ $programa->abreviatura }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button @click.stop="openCreateModal({{ $programa->id }}, '{{ addslashes($programa->nombre) }}')"
                                class="flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                <i class="fas fa-plus"></i>
                                Nueva Unidad
                            </button>
                        </div>
                    </div>

                    <!-- Lista de Unidades -->
                    <div x-show="expanded" x-collapse>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-100 dark:bg-slate-700/50 text-slate-600 dark:text-slate-400 text-xs uppercase tracking-wider">
                                    <tr>
                                        <th class="p-4 border-b border-slate-200 dark:border-slate-600 font-semibold">Nombre</th>
                                        <th class="p-4 border-b border-slate-200 dark:border-slate-600 font-semibold w-24 text-center">Créditos</th>
                                        <th class="p-4 border-b border-slate-200 dark:border-slate-600 font-semibold w-24 text-center">Horas Sem.</th>
                                        <th class="p-4 border-b border-slate-200 dark:border-slate-600 font-semibold w-24 text-center">Estado</th>
                                        <th class="p-4 border-b border-slate-200 dark:border-slate-600 font-semibold w-32 text-right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-600 bg-white dark:bg-slate-800">
                                    @forelse ($programa->unidadesDidacticas as $unidad)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300 font-medium">
                                                {{ $unidad->nombre }}
                                            </td>
                                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300 text-center">
                                                {{ $unidad->creditos }}
                                            </td>
                                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300 text-center">
                                                {{ $unidad->horas_semanales }}
                                            </td>
                                            <td class="p-4 text-sm text-center">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                    {{ $unidad->estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    {{ ucfirst($unidad->estado) }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-sm text-right">
                                                <div class="flex justify-end space-x-2">
                                                    @if ($unidad->estado === 'inactivo')
                                                        <form action="{{ route('admin.unidad-didactica.restore', $unidad->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 p-1.5 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/50 transition-colors"
                                                                title="Restaurar unidad">
                                                                <i class="fas fa-trash-restore-alt"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <button @click="openEditModal({{ $unidad->id }}, {{ $programa->id }}, '{{ addslashes($unidad->nombre) }}', {{ $unidad->creditos }}, {{ $unidad->horas_semanales }})"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors"
                                                            title="Editar unidad">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button onclick="confirmDelete({{ $unidad->id }})"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors"
                                                            title="Eliminar unidad">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        
                                                        <form id="delete-form-{{ $unidad->id }}"
                                                            action="{{ route('admin.unidad-didactica.destroy', $unidad->id) }}" method="POST"
                                                            class="hidden">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400 text-sm">
                                                No hay unidades didácticas registradas en este programa.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-slate-500 dark:text-slate-400">No se encontraron programas de estudio.</p>
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

    <!-- Modal Crear Unidad -->
    <template x-teleport="body">
        <div x-show="isCreateOpen" x-cloak 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeModals"></div>

            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                    @include('livewire.admin.unidad-didactica.unidad-didactica-form')
                </div>
            </div>
        </div>
    </template>

    <!-- Modal Editar Unidad -->
    <template x-teleport="body">
        <div x-show="isEditOpen" x-cloak 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeModals"></div>

            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                    <form :action="'/admin/unidad-didactica/' + editUnidadId" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="programa_estudio_id" x-model="currentProgramaId">

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Editar Unidad Didáctica</h3>

                            <!-- Nombre -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre de la Unidad <span class="text-red-500">*</span></label>
                                <input type="text" name="nombre" required x-model="editNombre"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Créditos -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Créditos <span class="text-red-500">*</span></label>
                                    <input type="number" name="creditos" min="1" required x-model="editCreditos"
                                        class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <!-- Horas -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Horas Semanales <span class="text-red-500">*</span></label>
                                    <input type="number" name="horas_semanales" min="1" required x-model="editHorasSemanales"
                                        class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                            <button type="button" @click="closeModals"
                                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                                Actualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
<script>
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar unidad didáctica?',
            '¡No podrás revertir esto!'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function unidadDidacticaTable() {
        return {
            isCreateOpen: false,
            isEditOpen: false,
            currentProgramaId: '',
            currentProgramaNombre: '',
            
            // Edit fields
            editUnidadId: null,
            editNombre: '',
            editCreditos: '',
            editHorasSemanales: '',

            openCreateModal(programaId, programaNombre) {
                this.currentProgramaId = programaId;
                this.currentProgramaNombre = programaNombre;
                this.isCreateOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            openEditModal(unidadId, programaId, nombre, creditos, horas) {
                this.editUnidadId = unidadId;
                this.currentProgramaId = programaId;
                this.editNombre = nombre;
                this.editCreditos = creditos;
                this.editHorasSemanales = horas;
                this.isEditOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModals() {
                this.isCreateOpen = false;
                this.isEditOpen = false;
                this.resetForm();
                document.body.classList.remove('overflow-hidden');
            },

            resetForm() {
                this.currentProgramaId = null;
                this.currentProgramaNombre = '';
                this.editUnidadId = null;
                this.editNombre = '';
                this.editCreditos = '';
                this.editHorasSemanales = '';
            }
        }
    }
</script>
