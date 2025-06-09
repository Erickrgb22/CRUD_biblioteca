<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserType;

class LoginController extends Controller
{
    /**
     * Constructor para aplicar middleware a ciertas acciones.
     * El middleware 'guest' redirige a los usuarios autenticados del login.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Muestra el formulario de login simplificado.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Maneja la solicitud de login usando solo la contraseña para el bibliotecario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Valida que el campo 'password' esté presente
        $request->validate([
            'password' => 'required|string',
        ]);

        // Busca el ID del tipo de usuario 'Bibliotecario'
        $bibliotecarioType = UserType::where('type', 'Bibliotecario')->first();

        // Si el tipo de usuario 'Bibliotecario' no existe, es un error de configuración
        if (!$bibliotecarioType) {
            return back()->withErrors(['password' => 'Error de configuración: Tipo de usuario "Bibliotecario" no encontrado. Asegúrate de que los seeders se ejecutaron correctamente.']);
        }

        // Busca al usuario que es de tipo 'Bibliotecario'.
        // Asumimos que solo habrá UN bibliotecario para este sistema.
        $bibliotecario = User::where('user_type_id', $bibliotecarioType->id)->first();

        // Si no se encuentra un usuario bibliotecario, es un error
        if (!$bibliotecario) {
            return back()->withErrors(['password' => 'No se encontró el usuario bibliotecario. Asegúrate de ejecutar los seeders.']);
        }

        // Intenta autenticar usando el email del bibliotecario y la contraseña proporcionada.
        // Laravel usa el campo 'email' por defecto para el 'username' en Auth::attempt.
        if (Auth::attempt(['email' => $bibliotecario->email, 'password' => $request->password])) {
            $request->session()->regenerate(); // Regenera la sesión para evitar ataques de fijación de sesión

            return redirect()->intended('/dashboard'); // Redirige al dashboard después del login
        }

        // Si la autenticación falla, regresa con un error
        return back()->withErrors([
            'password' => 'La contraseña proporcionada es incorrecta.',
        ])->onlyInput('password'); // Mantiene solo el campo de contraseña llenado si hubo error
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Cierra la sesión de Laravel

        $request->session()->invalidate(); // Invalida la sesión actual

        $request->session()->regenerateToken(); // Regenera el token CSRF para seguridad

        return redirect('/'); // Redirige a la página de login
    }
}