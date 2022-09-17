<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * roles y permisos
     *
     * @return void
     */
    public function run()
    {
        // --- CREAR ROLES ---

        // Administrador
        $roleAdmin = Role::create(['name' => 'admin']);

        // UACI
        $roleUaci = Role::create(['name' => 'uaci']);

        // Presupuesto
        $rolePresupuesto = Role::create(['name' => 'presupuesto']);

        // Ingenieria
        $roleIng = Role::create(['name' => 'formulador']);

        // Jefe UACI
        $roleJefeUaci = Role::create(['name' => 'jefeuaci']);

        // Administrador
        $roleAdministrador = Role::create(['name' => 'administrador']);

        // Secretaria
        $roleSecretaria = Role::create(['name' => 'secretaria']);

        // Uaci Unidad
        $roleUaciUnidad = Role::create(['name' => 'uaciunidad']);

        // Unidad
        $roleUnidad = Role::create(['name' => 'unidad']);

        // Recursos Humanos
        //$roleRRHH = Role::create(['name' => 'rrhh']);

        // ROLES Y PERMISOS
        Permission::create(['name' => 'sidebar.roles.y.permisos', 'description' => 'sidebar seccion roles y permisos'])->syncRoles($roleAdmin);

        // ESTADISTICAS
        Permission::create(['name' => 'sidebar.estadisticas', 'description' => 'sidebar seccion estadisticas'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleJefeUaci, $roleAdministrador, $roleSecretaria);

        // SECCION PROYECTO
        Permission::create(['name' => 'sidebar.seccion.proyecto', 'description' => 'sidebar seccion proyecto'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleAdministrador);

            Permission::create(['name' => 'sidebar.nuevo.proyecto', 'description' => 'sidebar seccion proyecto - nuevo proyecto'])->syncRoles($roleUaci);
            Permission::create(['name' => 'sidebar.lista.proyecto', 'description' => 'sidebar seccion proyecto - lista proyectos'])->syncRoles($roleUaci, $roleIng, $rolePresupuesto, $roleAdministrador);

            // botones
            Permission::create(['name' => 'boton.ver.proyecto', 'description' => 'sidebar seccion proyecto - boton ver proyecto'])->syncRoles($roleIng, $roleUaci, $roleAdministrador);
            Permission::create(['name' => 'boton.editar.proyecto', 'description' => 'sidebar seccion proyecto - boton editar proyecto'])->syncRoles($rolePresupuesto, $roleUaci, $roleIng);
            Permission::create(['name' => 'boton.pdf.generar.presupuesto', 'description' => 'sidebar seccion proyecto - boton generar presupuesto para proyecto pdf'])->syncRoles($roleIng);

            Permission::create(['name' => 'boton.agregar.requisicion', 'description' => 'sidebar seccion proyecto - boton agregar nueva requisicion'])->syncRoles($roleAdministrador);
            Permission::create(['name' => 'boton.editar.requisicion', 'description' => 'sidebar seccion proyecto - boton editar requisicion'])->syncRoles($roleAdministrador);
            Permission::create(['name' => 'boton.ver.presupuesto.modal', 'description' => 'sidebar seccion proyecto - boton ver presupuesto modal para aprobar'])->syncRoles($roleUaci);
            Permission::create(['name' => 'boton.cotizar.requisicion', 'description' => 'sidebar seccion proyecto - boton cotizar requisicion'])->syncRoles($roleUaci);
            Permission::create(['name' => 'boton.ver.planilla', 'description' => 'sidebar seccion proyecto - boton ver planilla'])->syncRoles($roleUaci);
            Permission::create(['name' => 'boton.ver.presupuesto.administrador', 'description' => 'sidebar seccion proyecto - boton ver presupuesto por administrador, solo ver'])->syncRoles($roleAdministrador);



            Permission::create(['name' => 'modulo.agregar.requisicion.proyecto', 'description' => 'sidebar seccion proyecto - modulo para agregar requisicion'])->syncRoles($roleAdministrador);

            Permission::create(['name' => 'modulo.agregar.bitacoras.proyecto', 'description' => 'sidebar seccion proyecto - modulo para ver bitacoras'])->syncRoles($roleIng);
            Permission::create(['name' => 'modulo.agregar.partida.proyecto', 'description' => 'sidebar seccion proyecto - modulo para agregar partidas'])->syncRoles($roleIng);


        //Permission::create(['name' => 'sidebar.cuenta.proyecto', 'description' => 'sidebar seccion proyecto - cuenta de proyecto'])->syncRoles($roleUaci, $rolePresupuesto);
        Permission::create(['name' => 'sidebar.movimiento.cuenta', 'description' => 'sidebar seccion proyecto - movimiento de cuenta'])->syncRoles($roleUaci, $rolePresupuesto);

        // COTIZACIONES
        Permission::create(['name' => 'sidebar.seccion.cotizaciones', 'description' => 'sidebar seccion cotizaciones'])->syncRoles($roleUaci, $roleJefeUaci);

            Permission::create(['name' => 'sidebar.cotizacion.pendiente', 'description' => 'sidebar seccion cotizaciones - cotizacion pendiente'])->syncRoles($roleUaci, $roleJefeUaci);
            Permission::create(['name' => 'sidebar.cotizacion.procesada', 'description' => 'sidebar seccion cotizaciones - cotizacion procesada'])->syncRoles($roleUaci, $roleJefeUaci);
            Permission::create(['name' => 'sidebar.cotizacion.denegada', 'description' => 'sidebar seccion cotizaciones - cotizacion denegada'])->syncRoles($roleUaci, $roleJefeUaci);

            Permission::create(['name' => 'boton.cotizacion.generar.orden', 'description' => 'sidebar seccion cotizacion - boton para generar orden de compra'])->syncRoles($roleUaci);




        // ORDENES DE COMPRAS
        Permission::create(['name' => 'sidebar.seccion.ordenescompra', 'description' => 'sidebar seccion ordenes de compra'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleJefeUaci);

        // SECCION CUENTA BOLSON
        Permission::create(['name' => 'sidebar.seccion.cuentabolson', 'description' => 'sidebar seccion cuenta bolson'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleJefeUaci);

            Permission::create(['name' => 'sidebar.cuentabolson.cuenta', 'description' => 'sidebar seccion cuenta bolson - cuenta'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleJefeUaci);
            Permission::create(['name' => 'sidebar.cuentabolson.movimiento', 'description' => 'sidebar seccion cuenta bolson - movimiento'])->syncRoles($roleUaci, $rolePresupuesto, $roleIng, $roleJefeUaci);

        // CONFIGURACIONES
        Permission::create(['name' => 'sidebar.seccion.configuraciones', 'description' => 'sidebar seccion configuraciones'])->syncRoles($roleUaci, $roleIng, $rolePresupuesto, $roleUaciUnidad);

            Permission::create(['name' => 'sidebar.fuente.financiamiento', 'description' => 'sidebar seccion fuente de financiamiento'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.area.gestion', 'description' => 'sidebar seccion area de gestion'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.fuente.recursos', 'description' => 'sidebar seccion fuente de recursos'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.linea.trabajo', 'description' => 'sidebar seccion linea de trabajo'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.catalogo.materiales', 'description' => 'sidebar seccion catalogo de materiales'])->syncRoles($roleUaci, $rolePresupuesto);
            Permission::create(['name' => 'sidebar.proveedores', 'description' => 'sidebar seccion proveedores'])->syncRoles($roleUaci);
            Permission::create(['name' => 'sidebar.unidad.medida', 'description' => 'sidebar seccion unidad de medida'])->syncRoles($roleUaci);
            Permission::create(['name' => 'sidebar.calificaciones', 'description' => 'sidebar seccion calificaciones'])->syncRoles($roleUaci);
            Permission::create(['name' => 'sidebar.administradores', 'description' => 'sidebar seccion administradores'])->syncRoles($roleUaci);
            Permission::create(['name' => 'sidebar.rubro', 'description' => 'sidebar seccion rubro'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.cuenta', 'description' => 'sidebar seccion cuenta'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.obj.especifico', 'description' => 'sidebar objeto específico'])->syncRoles($rolePresupuesto);
            Permission::create(['name' => 'sidebar.solicitud.material.uaci', 'description' => 'sidebar solicitud de materiales presupuesto ingenieria'])->syncRoles($roleUaci, $roleIng);
            Permission::create(['name' => 'sidebar.vista.catalogo.materiales.ing', 'description' => 'sidebar vista catalogo de materiales para ver lista por parte de ing'])->syncRoles($roleIng);


            Permission::create(['name' => 'boton.solicitar.material.ing', 'description' => 'boton para solicitar nuevo material por parte de ing'])->syncRoles($roleIng);
            Permission::create(['name' => 'boton.agregar.material.solicitado.ing', 'description' => 'boton para agregar el material solicitado por ing'])->syncRoles($roleUaci);


            //nuevos
            Permission::create(['name' => 'sidebar.adescos', 'description' => 'sidebar seccion adescos'])->syncRoles($roleUaciUnidad);
            Permission::create(['name' => 'sidebar.equipos', 'description' => 'sidebar seccion equipos'])->syncRoles($roleUaciUnidad);
            Permission::create(['name' => 'sidebar.asociaciones', 'description' => 'sidebar seccion asociaciones'])->syncRoles($roleUaciUnidad);

        // REQUERIMIENTOS PROYECTO
        Permission::create(['name' => 'sidebar.seccion.requerimientos', 'description' => 'sidebar seccion requerimientos'])->syncRoles($roleUaci);
        Permission::create(['name' => 'sidebar.requerimientos.listar', 'description' => 'sidebar seccion requerimientos - listar requerimientos'])->syncRoles($roleUaci);

        // REQUERIMIENTOS UNIDAD
        Permission::create(['name' => 'sidebar.seccion.requerimientosunidad', 'description' => 'sidebar seccion requerimientos de unidad'])->syncRoles($roleUaciUnidad);
        Permission::create(['name' => 'sidebar.requerimientosunidad.listar', 'description' => 'sidebar seccion requerimientos - listar requerimientos de unidad'])->syncRoles($roleUaciUnidad);


        Permission::create(['name' => 'boton.aprobar.presupuesto', 'description' => 'boton para aprobar presupuesto, mostrado en modal'])->syncRoles($roleUaci);
        Permission::create(['name' => 'boton.dinero.presupuesto', 'description' => 'boton para mostrar cuanto dinero le queda a un proyecto del presupuesto'])->syncRoles($roleAdministrador);




    }
}
