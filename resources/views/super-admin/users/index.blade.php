@extends('layouts.app')

@section('title', 'Gestión de Usuarios - EcoFinder')
@section('page-title', 'Usuarios')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">👥 Usuarios</h1>
            <p style="color: #64748b;">Gestiona los vecinos y emprendedores del sistema</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration:none;">
                Dashboard
            </a>
            <button onclick="openCreateModal()" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                + Crear Usuario
            </button>
        </div>
    </div>

    {{-- Mensajes de éxito/error --}}
    @if(session('success'))
        <div style="background: #d1fae5; border-left: 4px solid #10b981; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #065f46;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #991b1b;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if($errors->any())
        <div style="background: #fee2e2; border-left: 4px solid #ef4444; padding: 16px; border-radius: 8px; margin-bottom: 24px; color: #991b1b;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filtros --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); padding: 20px; margin-bottom: 24px;">
        <form method="GET" style="display: flex; gap: 12px; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Tipo de Usuario</label>
                <select name="user_type" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">Todos</option>
                    <option value="vecino" {{ request('user_type') == 'vecino' ? 'selected' : '' }}>Vecinos</option>
                    <option value="emprendedor" {{ request('user_type') == 'emprendedor' ? 'selected' : '' }}>Emprendedores</option>
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Municipalidad</label>
                <select name="municipality_id" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">Todas</option>
                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}" {{ request('municipality_id') == $municipality->id ? 'selected' : '' }}>
                            {{ $municipality->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
            <a href="{{ route('super-admin.users.index') }}" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                Limpiar
            </a>
        </form>
    </div>

    {{-- Tabla de Usuarios --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Nombre</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Email</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Teléfono</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Tipo</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Municipalidad</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Estado</th>
                    <th style="padding: 16px; text-align: center; font-size: 14px; color: #64748b; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 16px; font-size: 14px; color: #1e293b;">{{ $user->name }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $user->email }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $user->phone ?? 'N/A' }}</td>
                        <td style="padding: 16px;">
                            @if($user->user_type === 'vecino')
                                <span style="display: inline-block; padding: 4px 12px; background: #dbeafe; color: #1e40af; border-radius: 12px; font-size: 12px; font-weight: 600;">🏠 Vecino</span>
                            @else
                                <span style="display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 600;">💼 Emprendedor</span>
                            @endif
                        </td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">
                            {{ $user->municipality ? $user->municipality->name : 'Sin asignar' }}
                        </td>
                        <td style="padding: 16px;">
                            @if($user->is_active)
                                <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 12px; font-weight: 600;">Activo</span>
                            @else
                                <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 12px; font-weight: 600;">Inactivo</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <button onclick='editUser(@json($user))' style="padding: 8px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 8px; font-size: 12px;">
                                ✏️ Editar
                            </button>
                            <form action="{{ route('super-admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 8px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px;">
                                    🗑️ Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 48px; text-align: center; color: #64748b;">
                            <div style="font-size: 48px; margin-bottom: 16px;">👤</div>
                            <p style="font-size: 16px; margin-bottom: 8px;">No hay usuarios registrados</p>
                            <p style="font-size: 14px;">Crea el primer usuario haciendo clic en el botón de arriba</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Crear/Editar Usuario --}}
    <div id="userModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 32px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <h2 id="modalTitle" style="font-size: 24px; color: #1e293b; margin-bottom: 24px;">➕ Crear Usuario</h2>
            
            <form id="userForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Nombre</label>
                    <input type="text" name="name" id="userName" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" id="userEmail" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Teléfono</label>
                    <input type="tel" name="phone" id="userPhone" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Tipo de Usuario</label>
                    <select name="user_type" id="userType" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Seleccionar...</option>
                        <option value="vecino">🏠 Vecino</option>
                        <option value="emprendedor">💼 Emprendedor</option>
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Municipalidad</label>
                    <select name="municipality_id" id="userMunicipality" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Seleccionar...</option>
                        @foreach($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Contraseña</label>
                    <input type="password" name="password" id="userPassword" minlength="8" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <p id="passwordHelp" style="font-size: 12px; color: #64748b; margin-top: 4px;">Mínimo 8 caracteres</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="userPasswordConfirm" minlength="8" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="submitButton" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Crear Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = '➕ Crear Usuario';
            document.getElementById('userForm').action = '{{ route("super-admin.users.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('submitButton').textContent = 'Crear Usuario';
            
            // Limpiar campos
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userPhone').value = '';
            document.getElementById('userType').value = '';
            document.getElementById('userMunicipality').value = '';
            document.getElementById('userPassword').value = '';
            document.getElementById('userPasswordConfirm').value = '';
            
            // Hacer campos de contraseña requeridos
            document.getElementById('userPassword').required = true;
            document.getElementById('userPasswordConfirm').required = true;
            document.getElementById('passwordHelp').textContent = 'Mínimo 8 caracteres';
            
            document.getElementById('userModal').style.display = 'flex';
        }

        function editUser(user) {
            document.getElementById('modalTitle').textContent = '✏️ Editar Usuario';
            document.getElementById('userForm').action = `/super-admin/users/${user.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('submitButton').textContent = 'Guardar Cambios';
            
            // Rellenar campos
            document.getElementById('userName').value = user.name;
            document.getElementById('userEmail').value = user.email;
            document.getElementById('userPhone').value = user.phone || '';
            document.getElementById('userType').value = user.user_type;
            document.getElementById('userMunicipality').value = user.municipality_id;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPasswordConfirm').value = '';
            
            // Hacer campos de contraseña opcionales
            document.getElementById('userPassword').required = false;
            document.getElementById('userPasswordConfirm').required = false;
            document.getElementById('passwordHelp').textContent = 'Dejar en blanco para mantener la contraseña actual';
            
            document.getElementById('userModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('userModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('userModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection