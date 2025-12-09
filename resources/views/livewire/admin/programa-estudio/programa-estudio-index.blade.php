<div class="w-full py-8 px-4 sm:px-6 lg:px-8">
    @if (session('success'))
        <script>
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#22c55e',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-lg shadow-lg'
                }
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                background: '#18181b',
                color: '#f4f4f5',
                iconColor: '#ef4444',
                confirmButtonColor: '#3b82f6',
                customClass: {
                    popup: 'rounded-lg shadow-lg text-left'
                }
            });
        </script>
    @endif

    <div
        class="max-w-3xl mx-auto bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden border border-slate-200 dark:border-slate-700">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6">Agregar Programa de Estudios</h3>

            <form action="{{ route('admin.programa-estudio.store') }}" method="POST" class="space-y-6">
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
                            placeholder="Ej: Arquitectura de Plataformas TI" required maxlength="255" data-flux-control>
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
                            placeholder="Ej: APSTI" maxlength="50" data-flux-control>
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
            <a href="{{ route('admin.programa-estudio.index') }}"
                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                Cancelar
            </a>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl">
                Guardar Programa
            </button>
        </div>
        </form>
    </div>
</div>