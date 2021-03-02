<?php

namespace App\Http\Middleware;

use Closure;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->rol != 'Administrador'){
            if(auth()->user()->rol != 'Mayorista'){
                if(auth()->user()->rol != 'Almacen'){
                    if(auth()->user()->rol != 'Tienda'){
                        if(auth()->user()->rol != 'Marketing'){
                            return redirect('/');
                        }
                    }
                }
            }
        }
        return $next($request);

        /*
        if(auth()->user()->rol != 'Administrador'){
            if(auth()->user()->rol != 'Distribuidor'){
                if(auth()->user()->rol != 'Tienda'){
                    return redirect('/');
                }
            }
        }
        return $next($request);
        */

        // if(auth()->user()->perfil->nombre == 'Administrador'){
        //     return $next($request);  
        // }
        // else{
        //     return redirect('/');
        // }

        // if(auth()->user()->perfil->nombre == 'Almacen'){
        //     return $next($request);  
        // }
        // else{
        //     return redirect('/');
        // }

        // if(auth()->user()->perfil->nombre == 'Mayorista'){
        //     return $next($request);  
        // }
        // else{
        //     return redirect('/');
        // }
        
    }
}
