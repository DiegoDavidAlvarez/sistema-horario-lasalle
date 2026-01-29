<div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-6">
    <h2 class="text-xl font-bold text-slate-800 dark:text-slate-200 mb-4">
        <i class="fa-solid fa-calendar-plus mr-2 text-blue-600"></i>
        Nueva Asignación al Plan de Estudios
    </h2>
    
    <form wire:submit.prevent="$dispatch('save-plan-estudio')" action="{{ route('admin.plan-estudio.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Select Programa de Estudio -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Programa de Estudios <span class="text-red-500">*</span>
                </label>
                <select 
                    wire:model.live="programa_estudio_id" 
                    name="programa_estudio_id_hidden"
                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required>
                    <option value="">Seleccione un programa de estudios</option>
                    @foreach($programasEstudio as $programa)
                        <option value="{{ $programa->id }}">{{ $programa->nombre }}</option>
                    @endforeach
                </select>
                @if(!$programa_estudio_id)
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Selecciona un programa para cargar sus módulos y unidades
                    </p>
                @endif
            </div>

            <!-- Select Módulo (se habilita después de elegir programa) -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Módulo <span class="text-red-500">*</span>
                </label>
                <select 
                    wire:model="modulo_id" 
                    name="modulo_id"
                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ !$programa_estudio_id ? 'disabled' : '' }}
                    required>
                    <option value="">Seleccione un módulo</option>
                    @foreach($modulos as $modulo)
                        <option value="{{ $modulo->id }}">
                            Módulo {{ $modulo->numero_modulo }} - {{ $modulo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Select Unidad Didáctica (se habilita después de elegir programa) -->
            <div>
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Unidad Didáctica <span class="text-red-500">*</span>
                </label>
                <select 
                    wire:model="unidad_didactica_id" 
                    name="unidad_didactica_id"
                    class="w-full px-4 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    {{ !$programa_estudio_id ? 'disabled' : '' }}
                    required>
                    <option value="">Seleccione una unidad didáctica</option>
                    @foreach($unidadesDidacticas as $unidad)
                        <option value="{{ $unidad->id }}">
                            {{ $unidad->nombre }} ({{ $unidad->creditos }} créd., {{ $unidad->horas_semanales }}h/sem)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Radio Buttons de Semestre -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">
                    Semestre <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
                    @foreach($semestres as $semestre)
                        <label class="relative cursor-pointer">
                            <input 
                                type="radio" 
                                wire:model="semestre_id" 
                                name="semestre_id"
                                value="{{ $semestre->id }}"
                                class="peer sr-only"
                                required>
                            <div class="h-16 flex flex-col items-center justify-center rounded-lg border-2 border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 transition-all duration-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30 peer-checked:shadow-md hover:border-slate-400 dark:hover:border-slate-500">
                                <span class="text-xs text-slate-500 dark:text-slate-400 peer-checked:text-blue-600 dark:peer-checked:text-blue-400 font-medium">
                                    Semestre
                                </span>
                                <span class="text-2xl font-bold text-slate-700 dark:text-slate-300 peer-checked:text-blue-600 dark:peer-checked:text-blue-400">
                                    {{ $semestre->numero }}
                                </span>
                            </div>
                            <div class="absolute top-1 right-1 hidden peer-checked:block">
                                <i class="fa-solid fa-circle-check text-blue-500 text-lg"></i>
                            </div>
                        </label>
                    @endforeach
                </div>
                @if(!$semestre_id)
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                        <i class="fa-solid fa-info-circle mr-1"></i>
                        Selecciona el semestre donde se impartirá esta unidad
                    </p>
                @endif
            </div>
        </div>

        <!-- Botones -->
        <div class="mt-6 flex justify-end gap-3">
            <button 
                type="reset"
                wire:click="$set('programa_estudio_id', '')"
                class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700 transition">
                <i class="fa-solid fa-eraser mr-2"></i>
                Limpiar
            </button>
            <button 
                type="submit"
                :disabled="!programa_estudio_id || !modulo_id || !unidad_didactica_id || !semestre_id"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium disabled:opacity-50 disabled:cursor-not-allowed transition">
                <i class="fa-solid fa-save mr-2"></i>
                Guardar Asignación
            </button>
        </div>

        <!-- Indicador de carga -->
        <div wire:loading class="mt-4">
            <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400">
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span class="text-sm">Cargando opciones...</span>
            </div>
        </div>
    </form>
</div>
