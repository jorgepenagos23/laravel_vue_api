<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Alumno;
use App\Models\Rol;
use App\Models\Reporte;
use App\Models\AlumnoReporte;
use App\Models\Contenido;
use App\Models\Seccion;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GruposSeeder::class,
        ]);

        
    \App\Models\User::factory(0)->create();
    \App\Models\Alumno::factory(0)->create();
    \App\Models\Permiso::factory()->count(5)->create();
    \App\Models\Publicacion::factory()->count(10)->create();
            

            $user = new User();
            $user->nombre='Citlatli Clemente Parra '   ;
            $user->email='citlali@cobach.mx';
            $user->password = bcrypt('cobach');         
            $user->matricula='Numero de Plaza';
            $user->rol ='administrador';
            $user->save();

            $rol = new Rol();
            $rol->nombre = 'Director';
            $rol->guard = 'web';
            $rol->save();

            $rol = new Rol();
            $rol->nombre = 'Subdirector';
            $rol->guard = 'web';
            $rol->save();

            $rol = new Rol();
            $rol->nombre = 'Administrador';
            $rol->guard = 'web';
            $rol->save();

            $rol = new Rol();
            $rol->nombre = 'Orientadora';
            $rol->guard = 'web';
            $rol->save();

            $rol = new Rol();
            $rol->nombre = 'Alumno';
            $rol->guard = 'web';
            $rol->save();

                     /// Reporte seeder
            $reporte = new Reporte();
            $reporte->nombre ='Ausencia de Clases';
            $reporte->save();


            $reporte = new Reporte();
            $reporte->nombre ='Cita Padre de Familia';
            $reporte->save();


            $reporte = new Reporte();
            $reporte->nombre ='Mal comportamiento';
            $reporte->save();




            \App\Models\Boleta::factory()->count(10)->create(); 



            $reportealumno = new AlumnoReporte();
            $reportealumno->descripcion=' el alumno viene muy tarde a sus clases ';
            $reportealumno->matricula ='COBACH';
            $reportealumno->fecha = now(); // Asigna la fecha y hora actual a la propiedad 'fecha'
            $reportealumno->usuario_id ='1';
            $reportealumno->reporte_id ='1';
            $reportealumno->save();


         // Crear la sección
                 $this->call(SeccionesContenidosSeeder::class);

            
       


            


                    
                $this->call([
                    CalificacionesSeeder::class,
                    // Otros seeders si los tienes
                ]);


    }

}
