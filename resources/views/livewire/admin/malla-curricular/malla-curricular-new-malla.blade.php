<template x-teleport="body">
    <div x-show="isEditMallaOpen" x-cloak
        x-transition:enter="ease-out duration-300" 
        x-transition:enter-start="opacity-0" 
        x-transition:enter-end="opacity-100" 
        x-transition:leave="ease-in duration-200" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto">
        
        {{-- Fondo oscuro --}}
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" @click="closeEditMallaModal"></div>

        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-lg text-left overflow-visible shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-slate-200 dark:border-slate-700 relative z-10">
                <form action="{{ route('admin.malla-curricular.store') }}" method="POST">
                    @csrf
                    {{-- Campos ocultos --}}
                    <input type="hidden" name="programa_id" x-model="currentProgramaId">
                    <input type="hidden" name="modulo_id" x-model="selectedModuloId">
                    
                    {{-- Hidden inputs para enviar las unidades seleccionadas --}}
                    <template x-for="unitId in selectedUnits" :key="'hidden-' + unitId">
                        <input type="hidden" name="unidad_didactica_id[]" :value="unitId">
                    </template>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-slate-800 dark:text-slate-200 mb-2 text-center">
                            Añadir unidades
                        </h3>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400 text-center mb-6">
                            <span x-text="currentProgramaNombre" class="text-blue-600 dark:text-blue-400"></span> &bull; 
                            Módulo <span x-text="selectedModuloNumero"></span>
                        </p>

                        <div class="grid grid-cols-1 md:w-2/3 mx-auto gap-4 mb-6">
                            <!-- Selector de Semestre -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    <i class="fas fa-calendar-alt mr-1 text-emerald-500"></i>
                                    Semestre a configurar <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <template x-for="semestre in filteredSemestres" :key="semestre.id">
                                        <label class="relative flex items-center justify-center p-3 border rounded-xl cursor-pointer transition-all duration-200"
                                            :class="String(selectedSemestreId) === String(semestre.id) 
                                                ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 shadow ring-1 ring-blue-500' 
                                                : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/50'">
                                            <input type="radio" name="semestre_id" :value="semestre.id" x-model="selectedSemestreId" required
                                                class="sr-only" :disabled="!selectedModuloId">
                                            <span class="font-semibold text-sm flex items-center gap-2">
                                                <i class="fas transition-colors" :class="String(selectedSemestreId) === String(semestre.id) ? 'fa-circle-dot text-blue-500' : 'fa-circle text-slate-200 dark:text-slate-600'"></i>
                                                <span x-text="semestre.numero + '° Semestre'"></span>
                                            </span>
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Autocomplete de Unidades Didácticas -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                                    <i class="fas fa-book-open mr-1 text-amber-500"></i>
                                    Unidades Didácticas
                                    <span class="text-xs text-slate-400 ml-1">(escribe para buscar)</span>
                                </label>
                                <div class="flex items-center gap-3">
                                    <span class="text-xs text-slate-500 dark:text-slate-400"
                                        x-text="selectedCount + ' seleccionada' + (selectedCount !== 1 ? 's' : '')"></span>
                                </div>
                            </div>

                            <template x-if="currentUnidades.length > 0">
                                <div>
                                    <!-- Chips de unidades seleccionadas -->
                                    <div class="flex flex-wrap gap-2 mb-3" x-show="selectedUnits.length > 0">
                                        <template x-for="unitId in selectedUnits" :key="'chip-' + unitId">
                                            <span class="inline-flex items-center gap-1.5 pl-3 pr-1.5 py-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-medium rounded-lg border border-blue-200 dark:border-blue-800 transition-all duration-200 hover:bg-blue-100 dark:hover:bg-blue-900/50 group animate-chip-in">
                                                <i class="fas fa-book text-blue-400 dark:text-blue-500 text-xs"></i>
                                                <span class="max-w-[200px] truncate" x-text="getUnitName(unitId)"></span>
                                                <button type="button" @click="removeUnit(unitId)"
                                                    class="ml-0.5 w-5 h-5 rounded-full flex items-center justify-center text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800 hover:text-blue-600 dark:hover:text-blue-200 transition-colors">
                                                    <i class="fas fa-times text-[10px]"></i>
                                                </button>
                                            </span>
                                        </template>
                                    </div>

                                    <!-- Input de búsqueda con dropdown -->
                                    <div class="relative" x-data="{ showDropdown: false }" @click.outside="showDropdown = false">
                                        <div class="relative">
                                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-slate-500 text-sm pointer-events-none"></i>
                                            <input type="text"
                                                x-model="unitSearchQuery"
                                                @focus="showDropdown = true"
                                                @input="showDropdown = true"
                                                @keydown.escape="showDropdown = false; unitSearchQuery = ''"
                                                @keydown.arrow-down.prevent="navigateDropdown('down')"
                                                @keydown.arrow-up.prevent="navigateDropdown('up')"
                                                @keydown.enter.prevent="selectHighlighted()"
                                                :disabled="!selectedSemestreId"
                                                :placeholder="selectedSemestreId ? 'Buscar unidad didáctica por nombre...' : 'Seleccione un semestre primero...'"
                                                autocomplete="off"
                                                class="w-full pl-10 pr-4 py-2.5 border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700/50 text-slate-800 dark:text-slate-200 rounded-xl text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 disabled:bg-slate-100 dark:disabled:bg-slate-800/50 disabled:cursor-not-allowed disabled:text-slate-400 dark:disabled:text-slate-500"
                                                x-ref="unitSearchInput">
                                            <button type="button"
                                                x-show="unitSearchQuery.length > 0"
                                                @click="unitSearchQuery = ''; $refs.unitSearchInput.focus()"
                                                class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                                <i class="fas fa-times-circle text-sm"></i>
                                            </button>
                                        </div>

                                        <!-- Dropdown de resultados -->
                                        <div x-show="showDropdown && filteredUnidades.length > 0"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 translate-y-1"
                                            class="absolute z-[60] w-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-52 overflow-y-auto custom-scrollbar"
                                            x-ref="dropdownList">
                                            <template x-for="(unidad, index) in filteredUnidades" :key="'dd-' + unidad.id">
                                                <button type="button"
                                                    @click="toggleUnit(unidad)"
                                                    @mouseenter="highlightedIndex = index"
                                                    :disabled="isUnitAssignedElsewhere(unidad.id)"
                                                    class="w-full text-left px-4 py-2.5 flex items-center gap-3 transition-all duration-150 border-b border-slate-100 dark:border-slate-700/50 last:border-0"
                                                    :class="{
                                                        'bg-blue-50 dark:bg-blue-900/20': selectedUnits.includes(String(unidad.id)) && highlightedIndex !== index,
                                                        'bg-slate-100 dark:bg-slate-700': highlightedIndex === index && !isUnitAssignedElsewhere(unidad.id),
                                                        'opacity-50 cursor-not-allowed bg-slate-50 dark:bg-slate-900/30': isUnitAssignedElsewhere(unidad.id),
                                                        'hover:bg-slate-50 dark:hover:bg-slate-700/50': !selectedUnits.includes(String(unidad.id)) && !isUnitAssignedElsewhere(unidad.id) && highlightedIndex !== index,
                                                    }">
                                                    <!-- Checkbox visual -->
                                                    <div class="flex-shrink-0 w-5 h-5 rounded border-2 flex items-center justify-center transition-all"
                                                        :class="selectedUnits.includes(String(unidad.id))
                                                            ? 'bg-blue-500 border-blue-500 text-white'
                                                            : isUnitAssignedElsewhere(unidad.id)
                                                                ? 'border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-800'
                                                                : 'border-slate-300 dark:border-slate-600'">
                                                        <i class="fas fa-check text-[10px]" x-show="selectedUnits.includes(String(unidad.id))"></i>
                                                        <i class="fas fa-ban text-[9px] text-slate-400" x-show="isUnitAssignedElsewhere(unidad.id) && !selectedUnits.includes(String(unidad.id))"></i>
                                                    </div>
                                                    <!-- Info de la unidad -->
                                                    <div class="flex-1 min-w-0">
                                                        <span class="text-sm font-medium block truncate"
                                                            :class="isUnitAssignedElsewhere(unidad.id) ? 'text-slate-400 dark:text-slate-500' : 'text-slate-800 dark:text-slate-200'"
                                                            x-text="unidad.nombre"></span>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <span class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                                                <i class="fas fa-star text-amber-400"></i>
                                                                <span x-text="unidad.creditos"></span> créditos
                                                            </span>
                                                            <span class="text-[10px] text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                                                <i class="fas fa-clock text-emerald-400"></i>
                                                                <span x-text="unidad.horas_semanales + 'h'"></span>
                                                            </span>
                                                            <template x-if="isUnitAssignedElsewhere(unidad.id)">
                                                                <span class="text-[10px] px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                                                                    <i class="fas fa-info-circle"></i>
                                                                    <span x-text="getAssignedLocation(unidad.id)"></span>
                                                                </span>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>

                                        <!-- Sin resultados -->
                                        <div x-show="showDropdown && unitSearchQuery.length > 0 && filteredUnidades.length === 0"
                                            class="absolute z-[60] w-full mt-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl p-4 text-center">
                                            <i class="fas fa-search text-slate-300 dark:text-slate-600 text-lg mb-1"></i>
                                            <p class="text-sm text-slate-500 dark:text-slate-400">
                                                No se encontraron unidades con "<span class="font-medium text-slate-600 dark:text-slate-300" x-text="unitSearchQuery"></span>"
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Resumen de selección -->
                                    <div class="mt-3 text-xs text-slate-400 dark:text-slate-500 flex items-center gap-1.5" x-show="selectedUnits.length > 0">
                                        <i class="fas fa-info-circle"></i>
                                        <span>Haz clic en la <i class="fas fa-times text-[9px]"></i> de cada chip para quitar una unidad, o búscala y haz clic para desmarcarla.</span>
                                    </div>
                                </div>
                            </template>

                            <template x-if="currentUnidades.length === 0">
                                <div class="p-6 bg-slate-50 dark:bg-slate-700/50 rounded-lg border border-slate-200 dark:border-slate-600 text-center">
                                    <i class="fas fa-inbox text-3xl text-slate-300 dark:text-slate-500 mb-2"></i>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">
                                        No hay unidades didácticas activas para este programa
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-slate-50 dark:bg-slate-700 border-t border-slate-200 dark:border-slate-600 flex justify-end space-x-3">
                        <button type="button" @click="closeEditMallaModal"
                            class="px-4 py-2 text-slate-600 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 font-medium">
                            Cancelar
                        </button>
                        <button type="submit"
                            :disabled="!selectedModuloId || !selectedSemestreId"
                            class="flex items-center gap-3 px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 shadow-lg hover:shadow-xl
                                disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span x-text="selectedCount > 0
                                ? 'Guardar ' + selectedCount + ' unidad' + (selectedCount !== 1 ? 'es' : '')
                                : 'Guardar (0 unidades)'">
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
    @keyframes chipIn {
        from { opacity: 0; transform: scale(0.85) translateY(4px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-chip-in {
        animation: chipIn 0.2s ease-out;
    }
</style>