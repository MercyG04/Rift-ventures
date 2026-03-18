<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
       
            // 1. Check for Authentication
        // Ensure a user is logged in before checking their role. 
        
        if (!Auth::check()) {
            // Redirect unauthenticated users to the login page.
            return redirect()->route('login');
        }

        // 2. Get the authenticated user instance.
        $user = Auth::user();

        // 3. Perform Role Check (using the model's logic for cleanliness)
        
        switch ($role) {
            case 'admin':
                // Use the dedicated helper method from the User model:
                if ($user->isAdmin()) {
                    return $next($request); // Admin access granted.
                }
                break;
            
            // Add other roles here if needed (e.g., 'customer')
            case 'customer':
                // Example check for a customer role if you had a dedicated route for them
                 if ($user->role === \App\Enums\UserRole::CUSTOMER) {
                     return $next($request); 
                 }
                 break;

            default:
                
                break;
        }

        // 4. Access Denied Response
        // If the user is logged in but does not have the required role, 
        // deny access. We return a 403 Forbidden response.
        abort(403, 'Unauthorized action. You do not have the required permissions.');
    }
    
}
