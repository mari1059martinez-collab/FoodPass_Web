<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Métodos de Pago - FoodPass</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0ffd8; color: #121f05; }
        .sidebar-bg { background-color: #273517; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased text-gray-800" x-data="{
    nequiVinculado: true,
    saldoNequi: 450200,
    modalRecargar: false,
    modalDesvincularNequi: false,
    modalVincularTarjeta: false,
    modalActividadReciente: false,
    modalEliminarTarjeta: false,
    tarjetaAEliminar: '',
    montoRecarga: '',
    tarjetas: [
        { id: 1, banco: 'Visa', nombre: 'Visa Infinite Gold', predeterminada: true, vence: '12/28', ultimos: '4242' },
        { id: 2, banco: 'Mastercard', nombre: 'Mastercard Corporate', predeterminada: false, vence: '08/26', ultimos: '8812' }
    ],
    nuevaTarjeta: {
        banco: 'Bancolombia',
        nombreTitular: '',
        numero: '',
        expiracion: '',
        cvc: ''
    },
    formatCop(val) {
        return '$' + Number(val).toLocaleString('es-CO') + ' COP';
    },
    recargarSaldo() {
        const val = Number(this.montoRecarga);
        if (val > 0) {
            this.saldoNequi += val;
            this.modalRecargar = false;
            this.montoRecarga = '';
        }
    },
    desvincularNequi() {
        this.nequiVinculado = false;
        this.modalDesvincularNequi = false;
    },
    vincularNequi() {
        this.nequiVinculado = true;
    },
    agregarTarjeta() {
        if (!this.nuevaTarjeta.nombreTitular || !this.nuevaTarjeta.numero) return;
        const ultimos = this.nuevaTarjeta.numero.slice(-4) || '9999';
        this.tarjetas.push({
            id: Date.now(),
            banco: this.nuevaTarjeta.banco,
            nombre: this.nuevaTarjeta.banco + ' (' + this.nuevaTarjeta.nombreTitular + ')',
            predeterminada: false,
            vence: this.nuevaTarjeta.expiracion || '12/29',
            ultimos: ultimos
        });
        this.modalVincularTarjeta = false;
        this.nuevaTarjeta = { banco: 'Bancolombia', nombreTitular: '', numero: '', expiracion: '', cvc: '' };
    },
    confirmarEliminar(nombre) {
        this.tarjetaAEliminar = nombre;
        this.modalEliminarTarjeta = true;
    },
    eliminarTarjeta() {
        this.tarjetas = this.tarjetas.filter(t => t.nombre !== this.tarjetaAEliminar);
        this.modalEliminarTarjeta = false;
        this.tarjetaAEliminar = '';
    }
}">
    @include('partials.sidebar')

    <!-- Main Content -->
    <main class="ml-64 min-h-screen">
        <!-- Page Content -->
        <div class="p-8 max-w-7xl mx-auto space-y-8">
            <!-- Page Header -->
            <div class="flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Métodos de Pago</h1>
                    <p class="text-gray-500 text-sm mt-1 max-w-lg">Gestiona tus tarjetas, billeteras digitales y saldo FoodPass.<br>Toda tu información está protegida con encriptación de grado bancario.</p>
                </div>
                <!-- Requerimiento 1: Botón "Nueva tarjeta" eliminado -->
            </div>

            <!-- Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Left Column (2 cols wide) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Requerimiento 5: Cuadro Violeta NEQUI con Estados y Acciones Dinámicas -->
                    <div class="bg-gradient-to-r from-[#6200ea] to-[#8e24aa] rounded-2xl p-7 text-white shadow-xl relative overflow-hidden transition-all duration-300">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>
                        <div class="relative z-10 flex justify-between items-start">
                            <div>
                                <div class="flex items-center space-x-3 mb-8">
                                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                        <i class="fas fa-wallet text-xl"></i>
                                    </div>
                                    <h2 class="text-2xl font-bold tracking-wider">NEQUI</h2>
                                    <template x-if="nequiVinculado">
                                        <span class="px-2.5 py-1 text-xs font-medium bg-white/20 rounded-lg backdrop-blur-sm">VINCULADO</span>
                                    </template>
                                    <template x-if="!nequiVinculado">
                                        <span class="px-2.5 py-1 text-xs font-medium bg-red-500/40 text-white rounded-lg backdrop-blur-sm">DESVINCULADO</span>
                                    </template>
                                </div>
                                
                                <template x-if="nequiVinculado">
                                    <div>
                                        <p class="text-sm text-white/80 font-medium uppercase tracking-wider mb-1">Saldo Disponible</p>
                                        <h3 class="text-4xl font-bold tracking-tight" x-text="formatCop(saldoNequi)"></h3>
                                    </div>
                                </template>
                                <template x-if="!nequiVinculado">
                                    <div>
                                        <p class="text-sm text-white/80 font-medium tracking-wider mb-1">Cuenta no conectada actualmente.</p>
                                        <p class="text-xs text-white/60">Vincúla tu cuenta de Nequi para realizar recargas y pagos inmediatos.</p>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="flex flex-col space-y-2">
                                <template x-if="nequiVinculado">
                                    <div class="flex flex-col space-y-2">
                                        <button @click="modalRecargar = true" class="bg-white text-purple-700 hover:bg-gray-50 px-4 py-2 rounded-lg text-sm font-semibold transition-all hover:scale-105 shadow-md">
                                            Recargar
                                        </button>
                                        <button type="button" @click="modalDesvincularNequi = true" class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                            Desvincular
                                        </button>
                                    </div>
                                </template>
                                <template x-if="!nequiVinculado">
                                    <button @click="vincularNequi()" class="bg-white text-purple-700 hover:bg-gray-50 px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg transition-all hover:scale-105">
                                        Vincular Nequi
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Saved Cards -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Tarjetas Guardadas</h3>
                        
                        <div class="space-y-4">
                            <template x-for="(tarjeta, idx) in tarjetas" :key="tarjeta.id">
                                <div>
                                    <div class="flex items-center justify-between p-4 border border-gray-100 rounded-xl hover:border-gray-200 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold italic text-lg shadow-inner"
                                                 :class="tarjeta.banco.toLowerCase().includes('visa') ? 'bg-[#1a1f71]' : (tarjeta.banco.toLowerCase().includes('mastercard') ? 'bg-amber-600' : 'bg-[#273517]')">
                                                <span x-text="tarjeta.banco.slice(0, 4).toUpperCase()"></span>
                                            </div>
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <p class="font-bold text-gray-900" x-text="tarjeta.nombre"></p>
                                                    <template x-if="tarjeta.predeterminada">
                                                        <span class="px-2 py-0.5 text-[10px] font-bold text-green-700 bg-green-100 rounded">PREDETERMINADA</span>
                                                    </template>
                                                </div>
                                                <p class="text-sm text-gray-500 mt-0.5" x-text="'Vence: ' + tarjeta.vence + ' •••• ' + tarjeta.ultimos"></p>
                                            </div>
                                        </div>
                                        <button type="button" @click="confirmarEliminar(tarjeta.nombre)" class="text-gray-400 hover:text-red-500 p-2 transition-colors" title="Desvincular">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <div class="h-px w-full bg-gray-100 mt-4" x-show="idx < tarjetas.length - 1"></div>
                                </div>
                            </template>

                            <!-- Requerimiento 4: Botón "+ Vincular nuevo medio de pago" abre el Pop-up -->
                            <button @click="modalVincularTarjeta = true" class="w-full flex items-center justify-center py-4 border-2 border-dashed border-gray-200 rounded-xl text-gray-500 hover:text-orange-500 hover:border-orange-500 hover:bg-orange-50 transition-all group">
                                <div class="flex items-center space-x-2 font-medium">
                                    <i class="fas fa-plus group-hover:scale-110 transition-transform"></i>
                                    <span>Vincular nuevo medio de pago</span>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Column (1 col wide) -->
                <div class="space-y-6">
                    
                    <!-- Security Card -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mb-4">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Seguridad FoodPass</h3>
                        <p class="text-sm text-gray-500 mb-4 leading-relaxed">Toda tu información bancaria está tokenizada. Cumplimos con los estándares PCI-DSS para garantizar que tus compras artesanales sean 100% seguras.</p>
                        <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-full text-xs font-bold tracking-wide">
                            <i class="fas fa-lock"></i>
                            <span>ENCRIPTACIÓN 256-BIT</span>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Actividad Reciente</h3>
                            <!-- Requerimiento 2: Botón "Ver todo" abre el Pop-up de Compras Realizadas -->
                            <button @click="modalActividadReciente = true" class="text-sm font-medium text-orange-500 hover:text-orange-600 hover:underline">
                                Ver todo
                            </button>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($pedidos->take(3) as $ped)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-green-50 text-green-600 rounded-full flex items-center justify-center shrink-0">
                                        <i class="fas fa-shopping-bag text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900 truncate max-w-[140px]">Pedido #FP-{{ str_pad($ped->id, 4, '0', STR_PAD_LEFT) }}</p>
                                        <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($ped->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-gray-900">-${{ number_format($ped->total, 0, ',', '.') }}</span>
                            </div>
                            @empty
                            <div class="text-center text-xs text-gray-400 py-3">No hay compras registradas en el historial.</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Requerimiento 3: Cuadro verde "Plan Artisan" eliminado -->

                </div>

            </div>
        </div>
    </main>

    <!-- ── POP-UP MODAL 1: RECARGAR SALDO NEQUI (Requerimiento 5) ── -->
    <div x-show="modalRecargar" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="modalRecargar = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">Recargar Saldo Nequi</h3>
                </div>
                <button @click="modalRecargar = false" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <p class="text-xs text-gray-500 mb-4">Ingresa el valor en COP que deseas agregar a tu saldo Nequi.</p>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Monto en COP ($)</label>
                    <input type="number" min="1000" step="1000" x-model="montoRecarga" placeholder="Ej: 50000" 
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-lg font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div class="flex gap-2">
                    <button type="button" @click="montoRecarga = 10000" class="flex-1 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg text-xs">+ $10.000</button>
                    <button type="button" @click="montoRecarga = 20000" class="flex-1 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg text-xs">+ $20.000</button>
                    <button type="button" @click="montoRecarga = 50000" class="flex-1 py-1.5 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold rounded-lg text-xs">+ $50.000</button>
                </div>

                <div class="pt-3 flex gap-3">
                    <button type="button" @click="modalRecargar = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="button" @click="recargarSaldo()" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-bold text-sm shadow-md transition-all">
                        Confirmar Recarga
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── POP-UP MODAL 2: CONFIRMACIÓN DESVINCULAR NEQUI (Requerimiento 5 - Estilo Propio) ── -->
    <div x-show="modalDesvincularNequi" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="modalDesvincularNequi = false">
            <div class="text-center py-2">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">¿Desvincular Nequi?</h3>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                    Al desvincular tu cuenta no podrás recargar saldo ni realizar pagos rápidos desde Nequi hasta volverla a conectar.
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="modalDesvincularNequi = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="button" @click="desvincularNequi()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold text-sm shadow-md">
                        Sí, Desvincular
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── POP-UP MODAL 3: VINCULAR NUEVO MEDIO DE PAGO (Requerimiento 4 - Bancos Colombia) ── -->
    <div x-show="modalVincularTarjeta" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto" @click.away="modalVincularTarjeta = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900">Vincular Nuevo Medio de Pago</h3>
                </div>
                <button @click="modalVincularTarjeta = false" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form @submit.prevent="agregarTarjeta()" class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Banco / Entidad (Colombia)</label>
                    <select x-model="nuevaTarjeta.banco" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="Bancolombia">Bancolombia</option>
                        <option value="Banco de Bogotá">Banco de Bogotá</option>
                        <option value="Davivienda">Davivienda</option>
                        <option value="BBVA Colombia">BBVA Colombia</option>
                        <option value="Nequi">Nequi</option>
                        <option value="Daviplata">Daviplata</option>
                        <option value="Scotiabank Colpatria">Scotiabank Colpatria</option>
                        <option value="Banco de Occidente">Banco de Occidente</option>
                        <option value="Banco Popular">Banco Popular</option>
                        <option value="Banco AV Villas">Banco AV Villas</option>
                        <option value="Lulo Bank">Lulo Bank</option>
                        <option value="Nu Bank (Nubank)">Nu Bank (Nubank)</option>
                        <option value="RappiPay">RappiPay</option>
                        <option value="Banco Itaú">Banco Itaú</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Nombre del Titular</label>
                    <input type="text" x-model="nuevaTarjeta.nombreTitular" required placeholder="Ej: Camilo Valencia" 
                           @input="nuevaTarjeta.nombreTitular = nuevaTarjeta.nombreTitular.replace(/[^A-Za-záéíóúÁÉÍÓÚñÑ\s]/g, '').trimStart()"
                           pattern=".*[A-Za-záéíóúÁÉÍÓÚñÑ].*"
                           title="Ingresa solo el nombre del titular en letras"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Número de Tarjeta</label>
                    <input type="text" x-model="nuevaTarjeta.numero" required placeholder="4000 0000 0000 0000" maxlength="19"
                           @input="nuevaTarjeta.numero = nuevaTarjeta.numero.replace(/[^0-9\s]/g, '').trimStart()"
                           pattern="^[\d\s]{13,19}$"
                           title="Ingresa un número de tarjeta válido (solo números y espacios)"
                           class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">Expiración (MM/YY)</label>
                        <input type="text" x-model="nuevaTarjeta.expiracion" required placeholder="12/28" maxlength="5"
                               @input="nuevaTarjeta.expiracion = nuevaTarjeta.expiracion.replace(/[^0-9/]/g, '').trimStart()"
                               pattern="^(0[1-9]|1[0-2])\/?([0-9]{2})$"
                               title="El formato de expiración debe ser MM/YY (ej: 12/28)"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-600 mb-1">CVC / CVV</label>
                        <input type="password" x-model="nuevaTarjeta.cvc" required placeholder="123" maxlength="4"
                               @input="nuevaTarjeta.cvc = nuevaTarjeta.cvc.replace(/[^0-9]/g, '').trimStart()"
                               pattern="^[0-9]{3,4}$"
                               title="El código de seguridad debe tener 3 o 4 dígitos"
                               class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="modalVincularTarjeta = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-xl font-bold text-sm shadow-md transition-all">
                        Guardar Tarjeta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ── POP-UP MODAL 4: ACTIVIDAD RECIENTE COMPRAS REALIZADAS (Requerimiento 2) ── -->
    <div x-show="modalActividadReciente" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-2xl w-full p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto" @click.away="modalActividadReciente = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Historial de Compras Realizadas</h3>
                        <p class="text-xs text-gray-500">Compras registradas desde el carrito de compra en el Menú Digital</p>
                    </div>
                </div>
                <button @click="modalActividadReciente = false" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($pedidos as $pedido)
                <div class="py-4 flex items-center justify-between hover:bg-gray-50/60 px-2 rounded-xl transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-xs">
                            #{{ str_pad($pedido->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            <p class="font-bold text-sm text-gray-900">Pedido #FP-{{ str_pad($pedido->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-gray-500">Método: <span class="capitalize font-semibold text-gray-700">{{ $pedido->metodo_pago }}</span> • {{ \Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-extrabold text-sm text-gray-900">-${{ number_format($pedido->total, 0, ',', '.') }}</p>
                        <span class="inline-block px-2 py-0.5 text-[10px] font-bold rounded-md uppercase mt-0.5
                            {{ $pedido->estado == 'entregado' ? 'bg-green-100 text-green-700' : ($pedido->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                            {{ $pedido->estado }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 text-sm">Aún no se registran compras realizadas desde el carrito.</div>
                @endforelse
            </div>

            <div class="pt-4 border-t border-gray-100 text-right">
                <button @click="modalActividadReciente = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- ── POP-UP MODAL 5: ELIMINAR TARJEAT CONFIRMACIÓN ── -->
    <div x-show="modalEliminarTarjeta" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative" @click.away="modalEliminarTarjeta = false">
            <div class="text-center py-2">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash-alt text-2xl"></i>
                </div>
                <h3 class="text-xl font-extrabold text-gray-900 mb-2">¿Eliminar Tarjeta?</h3>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                    ¿Estás seguro de eliminar <span class="font-bold text-gray-800" x-text="tarjetaAEliminar"></span>? Esta acción no se puede deshacer.
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="modalEliminarTarjeta = false" class="flex-1 border border-gray-300 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="button" @click="eliminarTarjeta()" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-bold text-sm shadow-md">
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    </div>

</body>
</html>