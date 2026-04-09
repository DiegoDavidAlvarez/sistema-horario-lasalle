<div class="min-h-screen bg-slate-100 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="espacioFisicoList()">
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
        <!-- Filtros y Botones -->
        <div class="flex flex-col sm:flex-row justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700 gap-4">
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <div>
                    <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                        Espacios Físicos
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de aulas y ambientes del sistema</p>
                </div>
                <!-- Filtro de Estado -->
                <select wire:model.live="estadoFilter"
                    class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="todos">Todos</option>
                </select>
            </div>
            <button @click="openCreateModal()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-sm w-full sm:w-auto justify-center">
                <i class="fas fa-plus"></i>
                Agregar espacio
            </button>
        </div>

        <!-- Grid de Tarjetas -->
        <div class="p-6">
            @forelse ($espacios as $espacio)
                @if ($loop->first)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @endif

                <div class="bg-white dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 group flex flex-col">
                    <!-- Cabecera de la tarjeta -->
                    <div class="p-5 flex-1">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $espacio->estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                {{ ucfirst($espacio->estado) }}
                            </span>
                        </div>
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-200 leading-snug line-clamp-2">
                            {{ $espacio->nombre }}
                        </h3>
                    </div>

                    <!-- Acciones de la tarjeta -->
                    <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-600 flex justify-end gap-2">
                        @if ($espacio->estado === 'inactivo')
                            <!-- Botón Restaurar -->
                            <form action="{{ route('admin.espacio-fisico.update', $espacio->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="nombre" value="{{ $espacio->nombre }}">
                                <input type="hidden" name="_restore" value="1">
                                <button type="button"
                                    onclick="confirmRestore(this)"
                                    class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/50 transition-colors"
                                    title="Restaurar espacio">
                                    <i class="fas fa-trash-restore-alt"></i>
                                </button>
                            </form>
                        @else
                            <!-- Botón Editar -->
                            <button class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors"
                                title="Editar espacio"
                                @click="openEditModal({{ $espacio->id }}, '{{ addslashes($espacio->nombre) }}')">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <!-- Botón Eliminar -->
                            <button onclick="confirmDelete({{ $espacio->id }})"
                                class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors"
                                title="Eliminar espacio">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>

                            <!-- Formulario Eliminar (oculto) -->
                            <form id="delete-form-{{ $espacio->id }}"
                                action="{{ route('admin.espacio-fisico.destroy', $espacio->id) }}" method="POST"
                                class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @endif
                    </div>
                </div>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <div class="flex flex-col items-center justify-center py-16">
                    <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-building text-2xl text-slate-400 dark:text-slate-500"></i>
                    </div>
                    <h4 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">
                        No se encontraron espacios físicos
                    </h4>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto text-center">
                        Prueba ajustando los filtros o agrega un nuevo espacio físico al sistema.
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if ($espacios->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $espacios->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Crear Espacio Físico -->
    @include('livewire.admin.espacio-fisico.espacio-fisicon-new-espacio')

    <!-- Modal Editar Espacio Físico -->
    @include('livewire.admin.espacio-fisico.espacio-fisicon-edit-espacio')
</div>

<script>
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar espacio?',
            'Podrás restaurar este registro desde el filtro de inactivos.'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    function confirmRestore(button) {
        SwalThemed.confirm(
            '¿Restaurar espacio?',
            'Este espacio volverá a estar activo.',
            'Sí, restaurar',
            'Cancelar'
        ).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }

    // Componente Alpine.js
    function espacioFisicoList() {
        return {
            isEditEspacioOpen  : false,
            isCreateEspacioOpen: false,

            currentId    : null,
            currentNombre: '',

            openCreateModal() {
                this.isCreateEspacioOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeCreateModal() {
                this.isCreateEspacioOpen = false;
                document.body.classList.remove('overflow-hidden');
                setTimeout(() => {
                    document.querySelector('form[action="{{ route('admin.espacio-fisico.store') }}"]').reset();
                }, 300);
            },

            openEditModal(id, nombre) {
                this.currentId     = id;
                this.currentNombre = nombre;
                this.isEditEspacioOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeEditModal() {
                this.isEditEspacioOpen = false;
                document.body.classList.remove('overflow-hidden');
            },
        }
    }
</script>
