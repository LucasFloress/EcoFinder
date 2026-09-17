@extends('layouts.app')

@section('title', 'Gestión de Administradores - EcoFinder')
@section('page-title', 'Administradores')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">🏛️ Municipalidades</h1>
            <p style="color: #64748b;">Gestiona las municipalidades del sistema</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration:none;">
                Dashboard
            </a>
            <button onclick="openCreateModal()" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                + Crear Municipalidad
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

    {{-- Tabla de Administradores --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Nombre</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Email</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Estado</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Fecha Creación</th>
                    <th style="padding: 16px; text-align: center; font-size: 14px; color: #64748b; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 16px; font-size: 14px; color: #1e293b;">{{ $admin->name }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $admin->email }}</td>
                        <td style="padding: 16px;">
                            @if($admin->is_active)
                                <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 12px; font-weight: 600;">Activo</span>
                            @else
                                <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 12px; font-weight: 600;">Inactivo</span>
                            @endif
                        </td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $admin->created_at->format('d/m/Y') }}</td>
                        <td style="padding: 16px; text-align: center;">
                            <button onclick='editAdmin(@json($admin))' style="padding: 8px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 8px; font-size: 12px;">
                                ✏️ Editar
                            </button>
                            <form action="{{ route('super-admin.admins.destroy', $admin->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este administrador?')">
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
                        <td colspan="5" style="padding: 48px; text-align: center; color: #64748b;">
                            <div style="font-size: 48px; margin-bottom: 16px;">🛡️</div>
                            <p style="font-size: 16px; margin-bottom: 8px;">No hay administradores registrados</p>
                            <p style="font-size: 14px;">Crea el primer administrador haciendo clic en el botón de arriba</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal Crear/Editar Administrador --}}
    <div id="adminModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; border-radius: 12px; padding: 32px; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
            <h2 id="modalTitle" style="font-size: 24px; color: #1e293b; margin-bottom: 24px;">➕ Crear Administrador</h2>
            
            <form id="adminForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Nombre</label>
                    <input type="text" name="name" id="adminName" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" id="adminEmail" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Contraseña</label>
                    <input type="password" name="password" id="adminPassword" minlength="8" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <p id="passwordHelp" style="font-size: 12px; color: #64748b; margin-top: 4px;">Mínimo 8 caracteres</p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="adminPasswordConfirm" minlength="8" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="submitButton" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Crear Administrador
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = '➕ Crear Administrador';
            document.getElementById('adminForm').action = '{{ route("super-admin.admins.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('submitButton').textContent = 'Crear Administrador';
            
            // Limpiar campos
            document.getElementById('adminName').value = '';
            document.getElementById('adminEmail').value = '';
            document.getElementById('adminPassword').value = '';
            document.getElementById('adminPasswordConfirm').value = '';
            
            // Hacer campos de contraseña requeridos
            document.getElementById('adminPassword').required = true;
            document.getElementById('adminPasswordConfirm').required = true;
            document.getElementById('passwordHelp').textContent = 'Mínimo 8 caracteres';
            
            document.getElementById('adminModal').style.display = 'flex';
        }

        function editAdmin(admin) {
            document.getElementById('modalTitle').textContent = '✏️ Editar Administrador';
            document.getElementById('adminForm').action = `/super-admin/admins/${admin.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('submitButton').textContent = 'Guardar Cambios';
            
            // Rellenar campos
            document.getElementById('adminName').value = admin.name;
            document.getElementById('adminEmail').value = admin.email;
            document.getElementById('adminPassword').value = '';
            document.getElementById('adminPasswordConfirm').value = '';
            
            // Hacer campos de contraseña opcionales
            document.getElementById('adminPassword').required = false;
            document.getElementById('adminPasswordConfirm').required = false;
            document.getElementById('passwordHelp').textContent = 'Dejar en blanco para mantener la contraseña actual';
            
            document.getElementById('adminModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('adminModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        document.getElementById('adminModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection