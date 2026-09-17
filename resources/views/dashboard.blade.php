@extends('layouts.app')

@section('title', 'Dashboard - EcoFinder')

@section('content')
    <div style="padding: 40px;">
        {{-- Header del Dashboard --}}
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 32px; color: #2d5016; margin-bottom: 8px;">
                Bienvenido, {{ auth()->user()->name }}!
            </h1>
            <p style="font-size: 16px; color: #666;">
                @if(auth()->user()->isSuperAdmin())
                    Super Admin Dashboard
                @elseif(auth()->user()->isAdmin())
                    Administrador Dashboard
                @else
                    Usuario Dashboard
                @endif
            </p>
        </div>

        {{-- Estadísticas generales --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
            {{-- Card común para todos --}}
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h3 style="font-size: 14px; color: #666; margin-bottom: 8px;">Mi Perfil</h3>
                <p style="font-size: 24px; font-weight: bold; color: #2d5016;">Activo</p>
            </div>

            @if(auth()->user()->isSuperAdmin())
                {{-- Card solo para super admins --}}
                <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 14px; color: #666; margin-bottom: 8px;">Total de Municipalidades</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #2d5016;">
                        {{ App\Models\User::where('role', 'admin')->count() }}
                    </p>
                </div>
            @endif

            @if(auth()->user()->isAdmin())
                {{-- Card solo para admins y super admins --}}
                <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Users</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #2d5016;">{{ App\Models\User::count() }}</p>
                </div>

                <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <h3 style="font-size: 14px; color: #666; margin-bottom: 8px;">Total Products</h3>
                    <p style="font-size: 24px; font-weight: bold; color: #2d5016;">0</p>
                </div>
            @endif
        </div>

        {{-- Acciones rápidas según el rol --}}
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin-bottom: 32px;">
            <h2 style="font-size: 20px; color: #2d5016; margin-bottom: 16px;">Acciones rapidas</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px;">
                {{-- Acciones para todos --}}
                @if(auth()->user()->isAdmin() || auth()->user()->isUser())
                    <a href="{{ route('profile.edit') }}" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                    ⚙️ Editar Perfil
                    </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    {{-- Acciones solo para super admin --}}
                    <a href="/super-admin/admins" style="display: block; padding: 16px; background: #e8f5e9; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                        Administrar Municipalidades
                    </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                    {{-- Acciones para admins y super admins --}}
                    <a href="/super-admin/users" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                        Administrar Usuarios
                    </a>
                    <a href="/super-admin/puntos-verdes" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                        Administrar Puntos Verdes
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    {{-- Acciones para admins y super admins --}}
                    <a href="/admin/users" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                        Administrar Usuarios
                    </a>
                    <a href="/admin/puntos-verdes" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                        Administrar Puntos Verdes
                    </a>
                @endif

                @if(auth()->user()->isUser())
                    @if(auth()->user()->user_type === 'emprendedor')
                        <a href="{{ route('user.solicitudes.index') }}" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                            💼 Mis Solicitudes
                        </a>
                        <a href="{{ route('user.solicitudes.create') }}" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                            ➕ Nueva Solicitud
                        </a>
                    @else
                        <a href="{{ route('user.solicitudes.index') }}" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                            🔍 Ver Solicitudes
                        </a>
                        <a href="{{ route('user.mis-transacciones') }}" style="display: block; padding: 16px; background: #f0f7ed; border-radius: 6px; text-decoration: none; color: #2d5016; text-align: center;">
                            📋 Mis Transacciones
                        </a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Contenido específico por rol --}}
        @if(auth()->user()->isSuperAdmin())
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; color: #2d5016; margin-bottom: 16px;">Panel del Super Admin</h2>
                <p style="color: #666;">Tiene acceso completo a todas las funciones del sistema, gestión de municipalidades y usuarios.</p>
            </div>
            
        @elseif(auth()->user()->isAdmin())
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; color: #2d5016; margin-bottom: 16px;">Panel del Admin</h2>
                <p style="color: #666;">Administre usuarios y puntos verdes desde este panel.</p>
            </div>
        @else
            <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <h2 style="font-size: 20px; color: #2d5016; margin-bottom: 16px;">Panel del Usuario</h2>
                <p style="color: #666;">¡Bienvenido a EcoFinder! Empieza a explorar.</p>
            </div>
        @endif

    </div>
@endsection