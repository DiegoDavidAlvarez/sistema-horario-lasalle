<form action="{{ route('admin.docente.store') }}" method="POST" class="space-y-6">
    @csrf
    <div class="p-6">
        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Agregar Docente</h3>

        <div class="grid grid-cols-1 gap-6">
            <!-- Campo Tipo de Documento -->
            <div data-flux-field>
                <label for="tipo_documento" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Tipo de Documento <span class="text-red-500">*</span>
                </label>
                <select id="tipo_documento" name="tipo_documento"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                    required data-flux-control>
                    <option value="DNI">DNI</option>
                    <option value="CE">Carné de Extranjería</option>
                </select>
                @error('tipo_documento')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Número de Documento (DNI) -->
            <div data-flux-field>
                <label for="numero_documento" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Número de Documento <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2">
                    <input type="text" id="numero_documento" name="numero_documento"
                        class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                        placeholder="Ej: 12345678" required pattern="\d{8}" maxlength="8"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" data-flux-control>
                    <button type="button" id="consultar-dni"
                        class="px-4 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center justify-center">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                @error('numero_documento')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Nombres -->
            <div data-flux-field>
                <label for="nombres" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Nombres <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nombres" name="nombres"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                    placeholder="Ej: Juan Carlos" required maxlength="100" data-flux-control>
                @error('nombres')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Apellidos -->
            <div data-flux-field>
                <label for="apellidos" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Apellidos <span class="text-red-500">*</span>
                </label>
                <input type="text" id="apellidos" name="apellidos"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                    placeholder="Ej: Pérez Gómez" required maxlength="100" data-flux-control>
                @error('apellidos')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Email -->
            <div data-flux-field>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" id="email" name="email"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                    placeholder="Ej: juan.perez@email.com" required data-flux-control>
                @error('email')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campo Nivel Académico -->
            <div data-flux-field>
                <label for="nivel_academico" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1"
                    data-flux-label>
                    Nivel Académico
                </label>
                <select id="nivel_academico" name="nivel_academico"
                    class="w-full px-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200 text-slate-900 dark:text-slate-100 placeholder-slate-500 dark:placeholder-slate-400"
                    data-flux-control>
                    <option value="">Seleccione un nivel</option>
                    <option value="Bachiller">Bachiller</option>
                    <option value="Técnico">Técnico</option>
                    <option value="Licenciado">Licenciado</option>
                    <option value="Ingeniero">Ingeniero</option>
                    <option value="Magister">Magister</option>
                    <option value="Doctor">Doctor</option>
                </select>
                @error('nivel_academico')
                    <p class="mt-1 text-sm text-red-500 font-medium" data-flux-component="error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Campos ocultos para tipo_documento_api y digito_verificador -->
            <input type="hidden" id="tipo_documento_api" name="tipo_documento_api">
            <input type="hidden" id="digito_verificador" name="digito_verificador">
        </div>

        <!-- Nota de campos obligatorios -->
        <div class="mt-6 text-sm text-slate-500 dark:text-slate-400">
            Campos marcados con <span class="text-red-500 font-bold">*</span> son obligatorios
        </div>
    </div>

    <div
        class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
        <button type="button" @click="closeModalCreate"
            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
            Cancelar
        </button>
        <button type="submit"
            class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
            Registrar Docente
        </button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#consultar-dni').on('click', function () {
            const dni = $('#numero_documento').val();
            const tipoDocumento = $('#tipo_documento').val();
            if (!dni.match(/^\d{8}$/)) {
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
                        $('#nombres').val(data.nombres || '');
                        $('#apellidos').val(data.apellidos || '');
                        $('#tipo_documento_api').val(data.tipo_documento_api || '');
                        $('#digito_verificador').val(data.digito_verificador || '');
                        if (!data.apellidos) {
                            console.warn(
                                'Advertencia: El campo apellidos está vacío o no se recibió correctamente'
                            );
                        }
                        SwalThemed.success('¡Éxito!', 'Datos del documento obtenidos correctamente');
                    }
                },
                error: function (xhr) {
                    const errorMessage = xhr.responseJSON?.error || 'No se pudo conectar con la API';
                    SwalThemed.error('Error', errorMessage);
                }
            });
        });
    });
</script>