<form action="{{ route('admin.unidad-didactica.store') }}" method="POST">
    @csrf
    
    <div class="p-6">
        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-6" x-text="'Nueva Unidad Didáctica Para ' + currentProgramaNombre"></h3>

        <!-- Programa de Estudio (Hidden) -->
        <input type="hidden" name="programa_estudio_id" x-model="currentProgramaId">

        <!-- Nombre -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Nombre de la Unidad <span class="text-red-500">*</span></label>
            <input type="text" name="nombre" required
                class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- Créditos -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Créditos <span class="text-red-500">*</span></label>
                <input type="number" name="creditos" min="1" required
                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <!-- Horas -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Horas Semanales <span class="text-red-500">*</span></label>
                <input type="number" name="horas_semanales" min="1" required
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
            Guardar
        </button>
    </div>
</form>