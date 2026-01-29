<div class="min-h-screen bg-slate-100 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="docenteTable()">
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
                        Lista de Docentes
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de información docente del sistema</p>
                </div>
                <!-- Filtro de Estado -->
                <select wire:model.live="estadoFilter" {{-- Variable para interaccion en vivo --}}
                    class="bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-sm px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="todos">Todos</option>
                </select>
            </div>
            <button @click="openModalCreate()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-sm w-full sm:w-auto justify-center">
                <i class="fas fa-plus"></i>
                Agregar docente
            </button>
        </div>

        <!-- Tabla de Docentes -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800 dark:bg-slate-700 text-white text-xs uppercase tracking-wider">
                        <th class="p-4 w-16 text-center border-r border-slate-700 dark:border-slate-600">#</th>
                        <th class="p-4 border-r border-slate-700 dark:border-slate-600">Nombres</th>
                        <th class="p-4 border-r border-slate-700 dark:border-slate-600">Apellidos</th>
                        <th class="p-4 border-r border-slate-700 dark:border-slate-600">Nivel Académico</th>
                        <th class="p-4 border-r border-slate-700 dark:border-slate-600">Estado</th>
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                @forelse ($docentes as $docente) {{-- forelse es igual que foreach pero con excepcion @empty --}}
                    <tbody x-data="{ expanded: false }"
                        class="border-b border-slate-200 dark:border-slate-700 group">
                        {{-- Fila principal de la tabla --}}
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer"
                            @click="expanded = !expanded">
                            {{-- Columna para el numero de fila --}}
                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    {{-- Icono de flecha para expandir/contraer fila --}}
                                    <i class="fas fa-chevron-right text-xs text-slate-400 transition-transform duration-200"
                                        :class="{ 'rotate-90': expanded }"></i>
                                    {{ $loop->iteration }}
                                </div>
                            </td>
                            {{-- Columna para los nombres del docente --}}
                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300 font-medium">
                                {{ $docente->nombres }}
                            </td>
                            {{-- Columna para los apellidos del docente --}}
                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300">
                                {{ $docente->apellidos }}
                            </td>
                            {{-- Columna para el nivel academico del docente --}}
                            <td class="p-4 text-sm text-slate-700 dark:text-slate-300">
                                {{ $docente->nivel_academico }}
                            </td>
                            <td class="p-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold 
                                {{-- Aplica un estilo si el estado es activo y otro si no lo es --}}
                                    {{ $docente->estado === 'activo' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                    {{ ucfirst($docente->estado) }} {{-- ucfirst convierte la primera letra en mayuscula --}}
                                </span>
                            </td>
                            {{-- Columna para las acciones del docente --}}
                            <td class="p-4 text-sm text-right" @click.stop>
                                <div class="flex justify-end space-x-2">
                                    {{-- Condicion que muestra el boton de restaurar si el docente esta inactivo --}}
                                    @if ($docente->estado === 'inactivo')
                                        <!-- Botón Restaurar -->
                                        <form action="{{ route('admin.docente.restore', $docente->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 p-2 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/50 transition-colors"
                                                title="Restaurar docente">
                                                <i class="fas fa-trash-restore-alt"></i>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Botón Editar -->
                                        <button class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors"
                                            title="Editar docente"
                                            @click="openModalEdit({{ $docente->id }},
                                            '{{ addslashes($docente->nombres) }}',
                                            '{{ addslashes($docente->apellidos) }}',
                                            '{{ addslashes($docente->email) }}',
                                            '{{ addslashes($docente->tipo_documento) }}',
                                            '{{ addslashes($docente->numero_documento) }}',
                                            '{{ addslashes($docente->nivel_academico) }}')">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <button onclick="confirmDelete({{ $docente->id }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors"
                                            title="Eliminar docente">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>

                                        <!-- Formulario Eliminar (oculto) -->
                                        <form id="delete-form-{{ $docente->id }}"
                                            action="{{ route('admin.docente.destroy', $docente->id) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                        <!-- Fila de Detalles Expandible -->
                        <tr x-show="expanded" 
                            x-cloak 
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0" 
                            class="bg-slate-50 dark:bg-slate-800/50">
                            <td colspan="6" class="p-4 pl-12">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <span
                                            class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">
                                            Email
                                        </span>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">
                                            {{ $docente->email }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">
                                            Tipo Documento
                                        </span>
                                        <span class="text-sm text-slate-700 dark:text-slate-300">
                                            {{ $docente->tipo_documento }}
                                        </span>
                                    </div>
                                    <div>
                                        <span
                                            class="text-xs font-semibold text-slate-500 uppercase tracking-wider block mb-1">
                                            Número Documento
                                        </span>
                                        <span class="text-sm text-slate-700 dark:text-slate-300 font-mono">
                                            {{ $docente->numero_documento }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                {{-- Contenido que se muestra si no hay datos --}}
                @empty
                    <tbody class="bg-white dark:bg-slate-800">
                        <tr>
                            <td colspan="6" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center p-6">
                                    <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                        <i class="fa-solid fa-user-tie text-2xl text-slate-400 dark:text-slate-500"></i>
                                    </div>
                                    <h4 class="text-lg font-medium text-slate-700 dark:text-slate-300 mb-2">
                                        No se encontraron docentes
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                                        Prueba ajustando los filtros de búsqueda o agrega un nuevo docente al sistema.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>

        <!-- Paginación -->
        @if ($docentes->hasPages())
            <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $docentes->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Editar Docente -->
    @include('livewire.admin.docente.docente-edit-docente')

    <!-- Modal Crear Docente -->
    @include('livewire.admin.docente.docente-new-docente')
</div>

<script>
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar docente?',
            'Podrás restaurar este registro desde el filtro de inactivos.'
        ).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Componente Alpine.js para la tabla
    function docenteTable() {
        return {
            isEditOpen: false,
            isCreateOpen: false,
            currentId: null,
            currentNombres: '',
            currentApellidos: '',
            currentEmail: '',
            currentTipoDocumento: '',
            currentDni: '',
            currentNivelAcademico: '',

            openModalEdit(id, nombres, apellidos, email, tipo_documento, dni, nivel_academico) {
                this.currentId = id;
                this.currentNombres = nombres;
                this.currentApellidos = apellidos;
                this.currentEmail = email;
                this.currentTipoDocumento = tipo_documento;
                this.currentDni = dni;
                this.currentNivelAcademico = nivel_academico;
                this.isEditOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModalEdit() {
                this.isEditOpen = false;
                document.body.classList.remove('overflow-hidden');
            },

            openModalCreate() {
                this.isCreateOpen = true;
                document.body.classList.add('overflow-hidden');
            },

            closeModalCreate() {
                this.isCreateOpen = false;
                document.body.classList.remove('overflow-hidden');
                // Reiniciar formulario (esperar a que cierre la transición)
                setTimeout(() => {
                    document.querySelector('form[action="{{ route('admin.docente.store') }}"]').reset();
                }, 300);
            },
        }
    }
</script>
<script>
    $(document).ready(function () {
        $('#consultar-dni-edit').on('click', function () {
            const dni = $('#edit-dni').val();
            const tipoDocumento = $('#edit-tipo_documento').val();

            if (!dni || !dni.match(/^\d{8}$/)) {
                SwalThemed.error('Error', 'El número de documento debe tener 8 dígitos');
                return;
            }

            $.ajax({
                url: '{{ route('admin.docente.consultar-dni') }}',
                method: 'GET',
                data: {
                    dni: dni,
                    tipo_documento: tipoDocumento
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (data.error) {
                        SwalThemed.error('Error', data.error);
                    } else {
                        // Actualizar campos y disparar evento input para Alpine
                        $('#edit-nombres').val(data.nombres || '').trigger('input');
                        $('#edit-apellidos').val(data.apellidos || '').trigger('input');

                        SwalThemed.success('¡Éxito!', 'Datos del documento obtenidos correctamente');
                    }
                },
                error: function (xhr) {
                    SwalThemed.error('Error', 'Error al consultar el documento: ' + (xhr.responseJSON?.error || 'No se pudo conectar con la API'));
                }
            });
        });
    });
</script>