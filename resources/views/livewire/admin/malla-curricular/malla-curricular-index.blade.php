<div class="min-h-screen bg-slate-100 dark:bg-slate-900 py-8 px-4 sm:px-6 lg:px-8" x-data="mallaCurricularList()">
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
                    Malla Curricular
                </h1>
                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Gestión de mallas curriculares por programa de estudio</p>
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
            </div>
        </div>

        <!-- Lista de Programas (Pestañas) -->
        <div class="p-6" x-data="{ activeTab: '{{ $programas->first()->id ?? '' }}' }">
            @if($programas->isEmpty())
                <div class="py-12 text-center border bg-slate-50 dark:bg-slate-800 rounded-xl border-slate-200 dark:border-slate-700">
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
            @else
                <!-- Selector de Pestañas (Tabs) centradas -->
                <div class="flex justify-center border-b border-slate-200 dark:border-slate-700 mb-8 overflow-x-auto">
                    <nav class="flex space-x-2 sm:space-x-6 px-4" aria-label="Tabs">
                        @foreach ($programas as $programa)
                            <button class="whitespace-nowrap pb-4 px-2 border-b-2 font-semibold text-sm sm:text-base transition-colors duration-200"
                                @click="activeTab = '{{ $programa->id }}'"
                                :class="activeTab === '{{ $programa->id }}' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-300'">
                                {{ $programa->nombre }}
                            </button>
                        @endforeach
                    </nav>
                </div>

                <!-- Contenido de las Pestañas -->
                <div>
                    @foreach ($programas as $programa)
                        @php
                            $mallasDelPrograma = $mallasPorPrograma[$programa->id] ?? collect();
                            // Agrupar por modulo_id
                            $mallasPorModulo = $mallasDelPrograma->groupBy(function($malla) {
                                return $malla->modulo_id;
                            });
                            // Modulos del programa ordenados
                            $modulosPrograma = $programa->modulos->sortBy('numero_modulo');
                        @endphp
                        
                        <div x-show="activeTab === '{{ $programa->id }}'" style="display: none;" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            :class="{ 'block': activeTab === '{{ $programa->id }}', 'hidden': activeTab !== '{{ $programa->id }}' }">
                            
                            <!-- Header del Programa: Titulo y Boton -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-xl border border-slate-200 dark:border-slate-700">
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-slate-800 dark:text-slate-200">
                                        Malla Curricular - {{ $programa->nombre }}
                                    </h2>
                                    @if($programa->abreviatura)
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="text-xs text-slate-600 dark:text-slate-300 font-medium bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-sm px-2.5 py-1 rounded-md">
                                                <i class="fas fa-tag mr-1 text-slate-400"></i> {{ $programa->abreviatura }}
                                            </span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400">
                                                {{ $mallasDelPrograma->count() }} unidades asignadas en total
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Módulos Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse ($modulosPrograma as $modulo)
                                    @php
                                        $mallasModulo = $mallasPorModulo[$modulo->id] ?? collect();
                                    @endphp
                                    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full">
                                        <!-- Cabecera del Módulo -->
                                        <div class="p-5 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700/50 border-b border-slate-200 dark:border-slate-600 flex flex-col gap-2">
                                            <div class="flex justify-between items-start mb-1">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-sm">
                                                        {{ $modulo->numero_modulo }}
                                                    </div>
                                                    <h3 class="font-bold text-slate-800 dark:text-slate-200 text-lg">
                                                        Módulo {{ $modulo->numero_modulo }}
                                                    </h3>
                                                </div>
                                                <button @click="openEditMallaModal({{ $programa->id }},
                                                    '{{ addslashes($programa->nombre) }}',
                                                    {{ json_encode($modulosPrograma->map(fn($m) => ['id' => $m->id, 'nombre' => $m->nombre, 'numero_modulo' => $m->numero_modulo])->values()) }},
                                                    {{ json_encode($programa->unidadesDidacticas->map(fn($u) => ['id' => $u->id, 'nombre' => $u->nombre, 'creditos' => $u->creditos, 'horas_semanales' => $u->horas_semanales])->values()) }},
                                                    {{ json_encode($mallasDelPrograma->map(fn($m) => ['modulo_id' => $m->modulo_id, 'semestre_id' => $m->semestre_id, 'unidad_didactica_id' => $m->unidad_didactica_id])->values()) }},
                                                    {{ $modulo->id }})"
                                                    class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-sm shrink-0">
                                                    <i class="fas fa-plus"></i> Agregar Unidad
                                                </button>
                                            </div>
                                            <div class="flex justify-between items-center mt-1">
                                                <p class="text-sm text-slate-600 dark:text-slate-300 font-medium leading-snug line-clamp-1" title="{{ $modulo->nombre ?? 'Sin nombre' }}">
                                                    {{ $modulo->nombre ?? 'Sin nombre' }}
                                                </p>
                                                <span class="px-2.5 py-1 bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 text-xs rounded-full font-bold border border-indigo-100 dark:border-indigo-900/50 shadow-sm shrink-0">
                                                    {{ $mallasModulo->count() }} U.D.
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Lista de Unidades Didácticas (Mallas) -->
                                        <div class="p-4 flex-1 flex flex-col gap-3 bg-slate-50/50 dark:bg-transparent">
                                            @forelse($mallasModulo as $malla)
                                                <div class="group relative flex flex-col p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800/80 hover:border-blue-300 dark:hover:border-blue-700 shadow-sm transition-all duration-200">
                                                    
                                                    <div class="flex justify-between items-start gap-3 mb-3">
                                                        <h4 class="font-semibold text-sm text-slate-800 dark:text-slate-200 leading-tight">
                                                            {{ $malla->unidadDidactica->nombre ?? 'N/A' }}
                                                        </h4>
                                                    </div>

                                                    <div class="flex items-center gap-3 mt-auto">
                                                        <span class="inline-flex flex-shrink-0 items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-900/50">
                                                            <i class="fas fa-calendar-check text-blue-500 dark:text-blue-400"></i>
                                                            {{ $malla->semestre->numero ?? '?' }}° Semestre
                                                        </span>
                                                        <div class="flex gap-2 text-xs font-medium text-slate-500 dark:text-slate-400">
                                                            <span class="flex items-center gap-1 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md">
                                                                <i class="fas fa-star text-amber-500"></i> {{ $malla->unidadDidactica->creditos ?? 0 }}
                                                            </span>
                                                            <span class="flex items-center gap-1 bg-slate-100 dark:bg-slate-700 px-2 py-1 rounded-md">
                                                                <i class="fas fa-clock text-slate-400"></i> {{ $malla->unidadDidactica->horas_semanales ?? 0 }}h
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="flex flex-col items-center justify-center p-8 h-full text-center border-2 border-dashed border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800/50">
                                                    <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-3">
                                                        <i class="fas fa-book-open text-slate-400 dark:text-slate-500 text-lg"></i>
                                                    </div>
                                                    <p class="text-slate-600 dark:text-slate-300 text-sm font-semibold mb-1">
                                                        Sin unidades asignadas
                                                    </p>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-[200px]">
                                                        Aún no se han asignado unidades didácticas a este módulo.
                                                    </p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-span-full py-10 flex flex-col items-center justify-center text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm text-slate-500 dark:text-slate-400 text-sm font-medium">
                                        <i class="fas fa-exclamation-circle text-3xl mb-3 text-slate-300 dark:text-slate-600"></i>
                                        <p>Este programa no tiene módulos registrados.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Paginación -->
        @if ($programas->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700">
                {{ $programas->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Modificar Malla -->
    @include('livewire.admin.malla-curricular.malla-curricular-new-malla')
</div>

<script>
    function mallaCurricularList() {
        return {
            isEditMallaOpen: false,

            currentProgramaId    : null,
            currentProgramaNombre: '',
            currentModulos       : [],
            currentUnidades      : [],
            currentAsignaciones  : [],
            allSemestres: @json($semestres->map(fn($s) => ['id' => $s->id, 'numero' => $s->numero])),

            // Campos del formulario
            selectedModuloId  : '',
            selectedSemestreId: '',
            selectedUnits     : [],

            // Autocomplete
            unitSearchQuery : '',
            highlightedIndex: -1,

            init() {
                this.$watch('selectedModuloId', value => {
                    if (this.selectedSemestreId) {
                        const isValid = this.filteredSemestres.some(s => String(s.id) === String(this.selectedSemestreId));
                        if (!isValid) this.selectedSemestreId = '';
                    }
                    this.updateSelectedUnits();
                });
                this.$watch('selectedSemestreId', value => this.updateSelectedUnits());
                this.$watch('unitSearchQuery', () => { this.highlightedIndex = -1; });
            },

            get selectedModuloNumero() {
                if (!this.selectedModuloId) return null;
                const mod = this.currentModulos.find(m => String(m.id) === String(this.selectedModuloId));
                return mod ? Number(mod.numero_modulo) : null;
            },

            get filteredSemestres() {
                if (!this.selectedModuloNumero) return this.allSemestres;
                const modNum = this.selectedModuloNumero;
                const allowedNumOptions = [modNum * 2 - 1, modNum * 2];
                return this.allSemestres.filter(s => allowedNumOptions.includes(Number(s.numero)));
            },

            get filteredUnidades() {
                let units = [...this.currentUnidades].sort((a, b) => {
                    const aAssigned = this.isUnitAssignedElsewhere(a.id);
                    const bAssigned = this.isUnitAssignedElsewhere(b.id);
                    if (aAssigned === bAssigned) return 0;
                    return aAssigned ? 1 : -1;
                });

                if (this.unitSearchQuery.trim()) {
                    const q = this.unitSearchQuery.trim().toLowerCase();
                    units = units.filter(u => u.nombre.toLowerCase().includes(q));
                }

                return units;
            },
            
            // Para mostrar las unidades seleccionadas para guardar
            get selectedCount() {
                return this.selectedUnits.length;
            },

            updateSelectedUnits() {
                if (!this.selectedModuloId || !this.selectedSemestreId) {
                    this.selectedUnits = [];
                    return;
                }

                this.selectedUnits = this.currentAsignaciones
                    .filter(a => String(a.modulo_id) === String(this.selectedModuloId) && String(a.semestre_id) === String(this.selectedSemestreId))
                    .map(a => String(a.unidad_didactica_id));
            },

            isUnitAssignedElsewhere(unitId) {
                if (!this.selectedModuloId || !this.selectedSemestreId) return false;

                return this.currentAsignaciones.some(a => 
                    String(a.unidad_didactica_id) === String(unitId) && 
                    (String(a.modulo_id) !== String(this.selectedModuloId) || String(a.semestre_id) !== String(this.selectedSemestreId))
                );
            },

            getAssignedLocation(unitId) {
                if (!this.selectedModuloId || !this.selectedSemestreId) return '';

                const assignment = this.currentAsignaciones.find(a => 
                    String(a.unidad_didactica_id) === String(unitId) && 
                    (String(a.modulo_id) !== String(this.selectedModuloId) || String(a.semestre_id) !== String(this.selectedSemestreId))
                );

                if (assignment) {
                    const mod = this.currentModulos.find(m => String(m.id) === String(assignment.modulo_id));
                    const sem = this.allSemestres.find(s => String(s.id) === String(assignment.semestre_id));
                    const modName = mod ? 'Módulo ' + mod.numero_modulo : '';
                    const semName = sem ? 'Semestre ' + sem.numero : '';
                    if (modName && semName) return modName + ' - ' + semName;
                    if (modName) return modName;
                    if (semName) return semName;
                }

                return '';
            },

            getUnitName(unitId) {
                const u = this.currentUnidades.find(u => String(u.id) === String(unitId));
                return u ? u.nombre : '';
            },

            toggleUnit(unidad) {
                if (this.isUnitAssignedElsewhere(unidad.id)) return;
                const id = String(unidad.id);
                const idx = this.selectedUnits.indexOf(id);
                if (idx > -1) {
                    this.selectedUnits.splice(idx, 1);
                } else {
                    this.selectedUnits.push(id);
                }
            },

            removeUnit(unitId) {
                const idx = this.selectedUnits.indexOf(String(unitId));
                if (idx > -1) this.selectedUnits.splice(idx, 1);
            },

            navigateDropdown(direction) {
                const items = this.filteredUnidades;
                if (!items.length) return;
                if (direction === 'down') {
                    this.highlightedIndex = (this.highlightedIndex + 1) % items.length;
                } else {
                    this.highlightedIndex = this.highlightedIndex <= 0 ? items.length - 1 : this.highlightedIndex - 1;
                }
                // Scroll into view
                this.$nextTick(() => {
                    const list = this.$refs.dropdownList;
                    if (list) {
                        const item = list.children[this.highlightedIndex];
                        if (item) item.scrollIntoView({ block: 'nearest' });
                    }
                });
            },

            selectHighlighted() {
                const items = this.filteredUnidades;
                if (this.highlightedIndex >= 0 && this.highlightedIndex < items.length) {
                    this.toggleUnit(items[this.highlightedIndex]);
                }
            },

            openEditMallaModal(programaId, programaNombre, modulos, unidades, asignaciones = [], targetModuloId = null) {
                this.currentProgramaId     = programaId;
                this.currentProgramaNombre = programaNombre;
                this.currentModulos        = modulos;
                this.currentUnidades       = unidades;
                this.currentAsignaciones   = asignaciones;
                this.selectedModuloId      = targetModuloId;
                this.selectedSemestreId    = '';
                this.selectedUnits         = [];
                this.unitSearchQuery       = '';
                this.highlightedIndex      = -1;
                this.isEditMallaOpen       = true;

                document.body.classList.add('overflow-hidden');
            },

            closeEditMallaModal() {
                this.isEditMallaOpen = false;
                document.body.classList.remove('overflow-hidden');

                setTimeout(() => {
                    this.selectedModuloId    = '';
                    this.selectedSemestreId   = '';
                    this.selectedUnits       = [];
                    this.currentUnidades     = [];
                    this.currentModulos      = [];
                    this.currentAsignaciones = [];
                    this.unitSearchQuery     = '';
                    this.highlightedIndex    = -1;
                }, 300);
            },
        }
    }
</script>

