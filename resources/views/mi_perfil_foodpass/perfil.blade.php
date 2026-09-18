<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodPass - Mi Perfil</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <style>
        body { background-color: #f0ffd8; font-family: 'Inter', sans-serif; color: #121f05; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased" x-data="{ modalNotifsCuenta: false }">
    @include('partials.sidebar')

    <!-- Main Content (Sin barra superior header) -->
    <main class="ml-64 min-h-screen p-8">
        <div class="max-w-5xl mx-auto">

            <!-- ── HEADER PERFIL (RF06 y RF15) ── -->
            <div class="flex items-start gap-7 mb-10">
                <div class="relative shrink-0">
                    <div class="w-28 h-28 rounded-2xl overflow-hidden shadow-md border-2 border-white bg-[#273517]">
                        @if(auth()->user()->foto_perfil)
                            <img id="avatar-preview" src="{{ asset('storage/' . auth()->user()->foto_perfil) }}" class="w-full h-full object-cover" alt="Avatar"/>
                        @else
                            <img id="avatar-preview" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=F97F2D&color=fff" class="w-full h-full object-cover" alt="Avatar"/>
                        @endif
                    </div>
                    <!-- Botón para subir imagen (RF15) -->
                    <button type="button" onclick="document.getElementById('foto_perfil').click()" class="absolute -bottom-2 -right-2 w-8 h-8 bg-[#F97F2D] rounded-full flex items-center justify-center text-white shadow-md border-2 border-[#f0ffd8] hover:bg-[#e06d20] transition-colors">
                        <span class="material-symbols-outlined text-[15px]">photo_camera</span>
                    </button>
                </div>
                <div class="flex-1 pt-1">
                    <h2 class="text-3xl font-extrabold text-[#121f05] mb-1">{{ auth()->user()->name }}</h2>
                    <p class="text-[#574237] text-sm mb-2">{{ auth()->user()->email }}</p>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-[#e2f4c8] text-[#006e16] text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ auth()->user()->membresia ?? 'Básica' }}
                        </span>
                        <span class="text-xs text-gray-500 font-medium">
                            Miembro desde: {{ auth()->user()->created_at ? ucfirst(auth()->user()->created_at->translatedFormat('F Y')) : 'Reciente' }}
                        </span>
                    </div>
                    @if(auth()->user()->fecha_renovacion_membresia)
                    <p class="text-xs text-gray-500 font-medium">Renovación: {{ \Carbon\Carbon::parse(auth()->user()->fecha_renovacion_membresia)->format('d/m/Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- ── GRID CONTENIDO ── -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- COLUMNA FORMULARIO (RF15) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-7 shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-[#121f05] mb-6">Editar Información Personal</h3>

                        <form action="{{ route('perfil.update') }}" method="POST" id="form-perfil" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Input oculto para la foto -->
                            <input type="file" name="foto_perfil" id="foto_perfil" class="hidden" accept="image/*" onchange="previewImage(event)">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Nombre -->
                                <div class="space-y-1">
                                    <label for="name" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Nombre Completo</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Ej: Juan Pérez" 
                                           class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D] @error('name') border-red-500 @enderror">
                                    <p id="error-name" class="text-red-500 text-[10px] mt-1 hidden">El nombre no puede estar vacío.</p>
                                    @error('name') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Email -->
                                <div class="space-y-1">
                                    <label for="email" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Correo Electrónico</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" placeholder="usuario@foodpass.com" 
                                           class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D] @error('email') border-red-500 @enderror">
                                    <p id="error-email" class="text-red-500 text-[10px] mt-1 hidden">Ingresa un correo válido.</p>
                                    @error('email') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                                </div>

                                <!-- Teléfono -->
                                <div class="space-y-1">
                                    <label for="telefono" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Teléfono</label>
                                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', auth()->user()->telefono) }}" placeholder="+57 300 000 0000" 
                                           class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D]">
                                </div>

                                <!-- Idioma Preferido -->
                                <div class="space-y-1">
                                    <label for="idioma_preferido" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Idioma Preferido</label>
                                    <select name="idioma_preferido" id="idioma_preferido" class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D]">
                                        <option value="es" {{ auth()->user()->idioma_preferido == 'es' ? 'selected' : '' }}>Español</option>
                                        <option value="en" {{ auth()->user()->idioma_preferido == 'en' ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>

                                <!-- Dirección -->
                                <div class="space-y-1 md:col-span-2">
                                    <label for="direccion" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Dirección</label>
                                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', auth()->user()->direccion) }}" placeholder="Ej: Calle 123 #45-67" 
                                           class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D]">
                                </div>

                                <!-- Contraseña -->
                                <div class="space-y-1 md:col-span-2">
                                    <label for="password" class="block text-[11px] font-bold uppercase tracking-widest text-[#574237]">Nueva Contraseña (Opcional)</label>
                                    <input type="password" name="password" id="password" placeholder="Mínimo 8 caracteres para cambiar" 
                                           class="w-full bg-gray-50 border-gray-200 rounded-xl text-sm focus:ring-[#F97F2D] focus:border-[#F97F2D] @error('password') border-red-500 @enderror">
                                    @error('password') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="mt-8">
                                <button type="submit" id="btn-save" class="w-full bg-[#F97F2D] text-white font-bold text-sm py-3.5 rounded-xl flex items-center justify-center gap-2 shadow-lg shadow-[#F97F2D]/20 transition-all hover:bg-[#e06d20]">
                                    <span id="btn-text">Guardar Cambios</span>
                                    <div id="btn-spinner" class="hidden w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- COLUMNA DERECHA (Puntos, Pedidos y Requerimiento 2: Notificaciones de Cuenta) -->
                <div class="space-y-5">
                    <div class="bg-[#273517] rounded-2xl p-6 text-white shadow-md">
                        <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest">PUNTOS ACUMULADOS</span>
                        <p class="text-4xl font-extrabold mt-1">{{ number_format(auth()->user()->puntos_fp, 0) }} <span class="text-sm text-white/40">FP</span></p>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <span class="text-[10px] font-bold text-[#574237] uppercase tracking-widest">PEDIDOS TOTALES</span>
                        <p class="text-4xl font-extrabold text-[#121f05] mt-1">{{ auth()->user()->pedidos()->count() }}</p>
                    </div>
                    
                    <!-- Requerimiento 2: Botón Notificaciones con Pop-up debajo de Pedidos Totales -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <span class="text-[10px] font-bold text-[#574237] uppercase tracking-widest block mb-1">NOTIFICACIONES DE CUENTA</span>
                        <p class="text-xs text-gray-500 mb-4">Revisa alertas de seguridad, cambios de tarjeta y pagos.</p>
                        <button @click="modalNotifsCuenta = true" type="button" class="w-full bg-[#F97F2D] hover:bg-[#e06d20] text-white font-bold py-3 px-4 rounded-xl text-xs flex items-center justify-center gap-2 shadow-md transition-all">
                            <span class="material-symbols-outlined text-[18px]">notifications</span>
                            <span>Ver Notificaciones (3)</span>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- ── POP-UP MODAL: NOTIFICACIONES DE CUENTA (Requerimiento 2) ── -->
    <div x-show="modalNotifsCuenta" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition>
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl relative max-h-[85vh] overflow-y-auto" @click.away="modalNotifsCuenta = false">
            <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 text-[#F97F2D] rounded-xl flex items-center justify-center">
                        <span class="material-symbols-outlined">notifications_active</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Notificaciones de Cuenta</h3>
                        <p class="text-xs text-gray-500">Alertas de seguridad, métodos de pago y estado de compras</p>
                    </div>
                </div>
                <button @click="modalNotifsCuenta = false" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <div class="divide-y divide-gray-100 space-y-1">
                <!-- Notificación 1: Tarjeta de Pago -->
                <div class="py-3.5 flex items-start gap-3 hover:bg-orange-50/30 p-2 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">credit_card</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-900">Método de Pago Vinculado</p>
                        <p class="text-xs text-gray-600 mt-0.5">Se agregó exitosamente una nueva tarjeta <span class="font-bold text-gray-800">Visa (Bancolombia)</span> a tus métodos de pago.</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">Hace 1 hora</span>
                    </div>
                </div>

                <!-- Notificación 2: Contraseña / Seguridad -->
                <div class="py-3.5 flex items-start gap-3 hover:bg-orange-50/30 p-2 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-900">Seguridad de la Cuenta</p>
                        <p class="text-xs text-gray-600 mt-0.5">La contraseña de tu cuenta fue actualizada correctamente.</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">Ayer, 18:30</span>
                    </div>
                </div>

                <!-- Notificación 3: Confirmación de Pago -->
                <div class="py-3.5 flex items-start gap-3 hover:bg-orange-50/30 p-2 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">receipt_long</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-900">Pago Confirmado</p>
                        <p class="text-xs text-gray-600 mt-0.5">Pago de <span class="font-bold text-purple-700">$11.000 COP</span> confirmado por menú digital.</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">Hoy, 12:15 PM</span>
                    </div>
                </div>

                <!-- Notificación 4: Actualización App -->
                <div class="py-3.5 flex items-start gap-3 hover:bg-orange-50/30 p-2 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 text-sm mt-0.5">
                        <span class="material-symbols-outlined text-[18px]">system_update</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-900">Actualización del Sistema</p>
                        <p class="text-xs text-gray-600 mt-0.5">Actualización <span class="font-bold text-amber-700">FoodPass v2.4</span> instalada con mejoras en velocidad y seguridad de pagos.</p>
                        <span class="text-[10px] text-gray-400 mt-1 block">15 Sep 2026</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 text-right mt-2">
                <button @click="modalNotifsCuenta = false" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-xs transition-colors">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <!-- Script de Preview de Imagen y Validación -->
    <script>
        function previewImage(event) {
            const input = event.target;
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatar-preview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('form-perfil').addEventListener('submit', function(e) {
            let hasErrors = false;
            const nameInput = document.getElementById('name');
            const emailInput = document.getElementById('email');
            
            const errorName = document.getElementById('error-name');
            const errorEmail = document.getElementById('error-email');

            nameInput.classList.remove('border-red-500');
            emailInput.classList.remove('border-red-500');
            errorName.classList.add('hidden');
            errorEmail.classList.add('hidden');

            if(nameInput.value.trim() === '') {
                nameInput.classList.add('border-red-500');
                errorName.classList.remove('hidden');
                hasErrors = true;
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if(!emailRegex.test(emailInput.value.trim())) {
                emailInput.classList.add('border-red-500');
                errorEmail.classList.remove('hidden');
                hasErrors = true;
            }

            if(hasErrors) {
                e.preventDefault();
                return;
            }

            const btn = document.getElementById('btn-save');
            const text = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            text.innerText = 'Procesando...';
            spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>