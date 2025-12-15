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
    <div
        class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <!-- Encabezado -->
        <div class="flex justify-between items-center p-6 border-b border-slate-200 dark:border-slate-700">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 dark:text-slate-200">
                    Lista de Docentes
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de información docente del sistema
                </p>
            </div>
            <button @click="openModalCreate()"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium text-sm">
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
                        <th class="p-4 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                    @foreach ($docentes as $docente)
                        <tbody x-data="{ expanded: false }" class="border-b border-slate-200 dark:border-slate-700 group">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer"
                                @click="expanded = !expanded">
                                <td class="p-4 text-sm text-slate-700 dark:text-slate-300 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <i class="fas fa-chevron-right text-xs text-slate-400 transition-transform duration-200"
                                            :class="{ 'rotate-90': expanded }"></i>
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <td class="p-4 text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $docente->nombres }}
                                </td>
                                <td class="p-4 text-sm text-slate-700 dark:text-slate-300">{{ $docente->apellidos }}</td>
                                <td class="p-4 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $docente->nivel_academico ?? 'No registrado' }}
                                </td>
                                <td class="p-4 text-sm text-right" @click.stop>
                                    <div class="flex justify-end space-x-2">
                                        <!-- Botón Editar -->
                                        <button
                                            @click="openModalEdit({{ $docente->id }},
                                            '{{ addslashes($docente->nombres) }}',
                                            '{{ addslashes($docente->apellidos) }}',
                                            '{{ addslashes($docente->email) }}',
                                            '{{ addslashes($docente->tipo_documento) }}',
                                            '{{ addslashes($docente->numero_documento) }}',
                                            '{{ addslashes($docente->nivel_academico) }}')"
                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 p-2 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/50 transition-colors"
                                            title="Editar docente">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <!-- Botón Eliminar -->
                                        <button onclick="confirmDelete({{ $docente->id }})"
                                            class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 p-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors"
                                            title="Eliminar docente">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <!-- Formulario Eliminar (oculto) -->
                                        <form id="delete-form-{{ $docente->id }}"
                                            action="{{ route('admin.docente.destroy', $docente->id) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Fila de Detalles Expandible -->
                            <tr x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0" class="bg-slate-50 dark:bg-slate-800/50">
                                <td colspan="5" class="p-4 pl-12">
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
                    @endforeach
                </tbody>
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
    <template x-teleport="body">
        <div x-show="isEditOpen" x-cloak x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="closeModalEdit"></div>

            <!-- Contenido del Modal -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                    <form :action="'/admin/docente/' + currentId" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Editar Docente
                            </h3>

                            <!-- Campo Nombre -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre</label>
                                <input type="text" x-model="currentNombres" name="nombres" id="edit-nombres"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Campo Apellidos -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Apellidos</label>
                                <input type="text" x-model="currentApellidos" name="apellidos" id="edit-apellidos"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Campo Email -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Email</label>
                                <input type="email" x-model="currentEmail" name="email"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <!-- Campo Tipo Documento -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tipo de
                                    Documento</label>
                                <select x-model="currentTipoDocumento" name="tipo_documento" id="edit-tipo_documento"
                                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="" disabled>Seleccione un tipo</option>
                                    <option value="DNI">DNI</option>
                                    <option value="CE">Carnet de Extranjería</option>
                                </select>
                            </div>

                            <!-- Campo Nivel Académico -->
                            <div class="mb-4">
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nivel Académico</label>
                                <select x-model="currentNivelAcademico" name="nivel_academico"
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
                                <label
                                    class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">DNI</label>
                                <div class="flex gap-2">
                                    <input type="text" x-model="currentDni" name="numero_documento" id="edit-dni"
                                        class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required pattern="\d{8}" maxlength="8"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    <button type="button" id="consultar-dni-edit"
                                        class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                            <button type="button" @click="closeModalEdit"
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

    <!-- Modal Crear Docente -->
    <template x-teleport="body">
        <div x-show="isCreateOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                @click="closeModalCreate"></div>

            <!-- Contenido del Modal -->
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div
                    class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                    @include('livewire.admin.docente.docente-form')
                </div>
            </div>
        </div>
    </template>
</div>

<script>
    function confirmDelete(id) {
        SwalThemed.confirm(
            '¿Eliminar docente?',
            '¡No podrás revertir esto!'
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