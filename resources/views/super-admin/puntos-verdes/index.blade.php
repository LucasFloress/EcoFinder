@extends('layouts.app')

@section('title', 'Gestión de Puntos Verdes - EcoFinder')
@section('page-title', 'Puntos Verdes')

@section('content')
    <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 28px; color: #1e293b; margin-bottom: 8px;">♻️ Puntos Verdes</h1>
            <p style="color: #64748b;">Gestiona todos los puntos verdes del sistema</p>
        </div>
        <div style="display:flex; column-gap:20px;">
            <a href="/dashboard" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; text-decoration:none;">
                Dashboard
            </a>
            <button onclick="openCreateModal()" style="padding: 12px 24px; background: #4a7c2c; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px;">
                + Crear Punto Verde
            </button>
        </div>
    </div>

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
            <div style="flex: 1;">
                <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Estado</label>
                <select name="status" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    <option value="">Todos</option>
                    <option value="operativo" {{ request('status') == 'operativo' ? 'selected' : '' }}>Operativo</option>
                    <option value="mantenimiento" {{ request('status') == 'mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                    <option value="cerrado" {{ request('status') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>
            <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                Filtrar
            </button>
            <a href="{{ route('super-admin.puntos-verdes.index') }}" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block;">
                Limpiar
            </a>
        </form>
    </div>

    {{-- Tabla --}}
    <div style="background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Nombre</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Dirección</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Ciudad</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Municipalidad</th>
                    <th style="padding: 16px; text-align: left; font-size: 14px; color: #64748b; font-weight: 600;">Estado</th>
                    <th style="padding: 16px; text-align: center; font-size: 14px; color: #64748b; font-weight: 600;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($puntosVerdes as $point)
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 16px; font-size: 14px; color: #1e293b;">{{ $point->name }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $point->address }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">{{ $point->city }}</td>
                        <td style="padding: 16px; font-size: 14px; color: #64748b;">
                            {{ $point->municipality ? $point->municipality->name : 'N/A' }}
                        </td>
                        <td style="padding: 16px;">
                            @if($point->status === 'operativo')
                                <span style="display: inline-block; padding: 4px 12px; background: #d1fae5; color: #065f46; border-radius: 12px; font-size: 12px; font-weight: 600;">✅ Operativo</span>
                            @elseif($point->status === 'mantenimiento')
                                <span style="display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; border-radius: 12px; font-size: 12px; font-weight: 600;">🔧 Mantenimiento</span>
                            @else
                                <span style="display: inline-block; padding: 4px 12px; background: #fee2e2; color: #991b1b; border-radius: 12px; font-size: 12px; font-weight: 600;">❌ Cerrado</span>
                            @endif
                        </td>
                        <td style="padding: 16px; text-align: center;">
                            <button onclick='editGreenPoint(@json($point))' style="padding: 8px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; margin-right: 8px; font-size: 12px;">
                                ✏️ Editar
                            </button>
                            <form action="{{ route('super-admin.puntos-verdes.destroy', $point->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este punto verde?')">
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
                        <td colspan="6" style="padding: 48px; text-align: center; color: #64748b;">
                            <div style="font-size: 48px; margin-bottom: 16px;">♻️</div>
                            <p style="font-size: 16px; margin-bottom: 8px;">No hay puntos verdes registrados</p>
                            <p style="font-size: 14px;">Crea el primer punto verde haciendo clic en el botón de arriba</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal --}}
    <div id="greenPointModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; overflow-y: auto;">
        <div style="background: white; border-radius: 12px; padding: 32px; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; margin: 20px;">
            <h2 id="modalTitle" style="font-size: 24px; color: #1e293b; margin-bottom: 24px;">➕ Crear Punto Verde</h2>
            
            <form id="greenPointForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Nombre *</label>
                    <input type="text" name="name" id="pointName" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Descripción</label>
                    <textarea name="description" id="pointDescription" rows="3" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Dirección *</label>
                    <input type="text" name="address" id="pointAddress" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Ciudad *</label>
                        <input type="text" name="city" id="pointCity" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Teléfono</label>
                        <input type="tel" name="phone" id="pointPhone" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" id="pointEmail" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Municipalidad *</label>
                    <select name="municipality_id" id="pointMunicipality" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="">Seleccionar...</option>
                        @foreach($municipalities as $municipality)
                            <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Materiales Aceptados</label>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="plastico" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Plástico</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="papel" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Papel/Cartón</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="vidrio" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Vidrio</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="metal" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Metal</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="electronico" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Electrónico</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" name="accepted_materials[]" value="organico" style="width: 16px; height: 16px;">
                            <span style="font-size: 14px;">Orgánico</span>
                        </label>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 8px;">Estado *</label>
                    <select name="status" id="pointStatus" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px;">
                        <option value="operativo">✅ Operativo</option>
                        <option value="mantenimiento">🔧 Mantenimiento</option>
                        <option value="cerrado">❌ Cerrado</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" style="padding: 12px 24px; background: #e2e8f0; color: #64748b; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Cancelar
                    </button>
                    <button type="submit" id="submitButton" style="padding: 12px 24px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                        Crear Punto Verde
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = '➕ Crear Punto Verde';
            document.getElementById('greenPointForm').action = '{{ route("super-admin.puntos-verdes.store") }}';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('submitButton').textContent = 'Crear Punto Verde';
            
            document.getElementById('pointName').value = '';
            document.getElementById('pointDescription').value = '';
            document.getElementById('pointAddress').value = '';
            document.getElementById('pointCity').value = '';
            document.getElementById('pointPhone').value = '';
            document.getElementById('pointEmail').value = '';
            document.getElementById('pointMunicipality').value = '';
            document.getElementById('pointStatus').value = 'operativo';
            
            document.querySelectorAll('input[name="accepted_materials[]"]').forEach(cb => cb.checked = false);
            
            document.getElementById('greenPointModal').style.display = 'flex';
        }

        function editGreenPoint(point) {
            document.getElementById('modalTitle').textContent = '✏️ Editar Punto Verde';
            document.getElementById('greenPointForm').action = `/super-admin/puntos-verdes/${point.id}`;
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('submitButton').textContent = 'Guardar Cambios';
            
            document.getElementById('pointName').value = point.name;
            document.getElementById('pointDescription').value = point.description || '';
            document.getElementById('pointAddress').value = point.address;
            document.getElementById('pointCity').value = point.city;
            document.getElementById('pointPhone').value = point.phone || '';
            document.getElementById('pointEmail').value = point.email || '';
            document.getElementById('pointMunicipality').value = point.municipality_id;
            document.getElementById('pointStatus').value = point.status;
            
            document.querySelectorAll('input[name="accepted_materials[]"]').forEach(cb => {
                cb.checked = point.accepted_materials && point.accepted_materials.includes(cb.value);
            });
            
            document.getElementById('greenPointModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('greenPointModal').style.display = 'none';
        }

        document.getElementById('greenPointModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection