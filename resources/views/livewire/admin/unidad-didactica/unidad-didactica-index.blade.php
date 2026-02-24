<div class="min-h-screen bg-slate-100 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="unidadDidacticaList()">
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
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
            <div class="w-full sm:w-auto">
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                    Unidades Didácticas
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de unidades por programa de estudio</p>
            </div>
            
            <!-- Filtros -->
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <!-- Filtro de Programas -->
                <div class="flex items-center gap-2">
                    <label for="programa-filter" class="text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">
                        Programas:
                    </label>
                    <select id="programa-filter" wire:model.live="programaEstadoFilter"
                        class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="todos">Todos</option>
                    </select>
                </div>
                
                <!-- Filtro de Unidades -->
                <div class="flex items-center gap-2">
                    <label for="unidad-filter" class="text-sm font-medium text-slate-600 dark:text-slate-400 whitespace-nowrap">
                        Unidades:
                    </label>
                    <select id="unidad-filter" wire:model.live="unidadEstadoFilter"
                        class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="activo">Activos</option>
                        <option value="inactivo">Inactivos</option>
                        <option value="todos">Todos</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Lista de Programas -->
        <div class="p-6 space-y-6">
            @forelse ($programas as $programa)
                <div class="bg-slate-50 dark:bg-slate-700/30 rounded-xl border border-slate-200 dark:border-slate-600 overflow-hidden"
                    x-data="{ expanded: false }">
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
                            <button @click.stop="openCreateUnidadModal({{ $programa->id }}, '{{ addslashes($programa->nombre) }}')"
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
                                                        <button @click="openEditUnidadModal({{ $unidad->id }}, {{ $programa->id }}, '{{ addslashes($unidad->nombre) }}', {{ $unidad->creditos }}, {{ $unidad->horas_semanales }})"
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors"
                                                            title="Editar unidad">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                        <button onclick="confirmDelete({{ $unidad->id }})"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors"
                                                            title="Eliminar unidad">
                                                            <i class="fa-solid fa-trash-can"></i>
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
                                            <td colspan="5" class="py-12 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="mx-auto w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3">
                                                        <i class="fa-solid fa-book-open text-xl text-slate-400 dark:text-slate-500"></i>
                                                    </div>
                                                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                                                        No hay unidades didácticas registradas en este programa.
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center">
                    <div class="flex flex-col items-center justify-center p-6">
                        <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-graduation-cap text-2xl text-slate-400 dark:text-slate-500"></i>
                        </div>
                        <h4 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">
                            No se encontraron programas de estudio
                        </h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                            Asegúrate de tener programas activos o ajusta el filtro de estado para ver los resultados.
                        </p>
                    </div>
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
    @include('livewire.admin.unidad-didactica.unidad-didactica-new-unidad')

    <!-- Modal Editar Unidad -->
    @include('livewire.admin.unidad-didactica.unidad-didactica-edit-unidad')
</div>
<script>
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar unidad didáctica?',
            'Podrás restaurar este registro desde el filtro de inactivos.'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function unidadDidacticaList() {
        return {
            isCreateUnidadOpen: false,
            isEditUnidadOpen  : false,

            currentProgramaId    : '',
            currentProgramaNombre: '',
            
            // Edit fields
            editUnidadId      : null,
            editNombre        : '',
            editCreditos      : '',
            editHorasSemanales: '',

            openCreateUnidadModal(programaId, programaNombre) {
                this.currentProgramaId     = programaId;
                this.currentProgramaNombre = programaNombre;
                this.isCreateUnidadOpen    = true;

                document.body.classList.add('overflow-hidden');
            },

            closeCreateUnidadModal() {
                this.isCreateUnidadOpen = false;

                document.body.classList.remove('overflow-hidden');
                // Reiniciar formulario de creación
                setTimeout(() => {
                    document.querySelector('form[action="{{ route('admin.unidad-didactica.store') }}"]').reset();
                }, 300);
            },

            openEditUnidadModal(unidadId, programaId, nombre, creditos, horas) {
                this.editUnidadId       = unidadId;
                this.currentProgramaId  = programaId;
                this.editNombre         = nombre;
                this.editCreditos       = creditos;
                this.editHorasSemanales = horas;
                this.isEditUnidadOpen   = true;

                document.body.classList.add('overflow-hidden');
            },

            closeEditUnidadModal() {
                this.isEditUnidadOpen = false;

                document.body.classList.remove('overflow-hidden');
            },
        }
    }
</script>
