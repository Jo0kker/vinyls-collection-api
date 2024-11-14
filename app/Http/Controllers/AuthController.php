<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ]);

        $validatedData['password'] = bcrypt($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Confirmer votre adresse email')
                ->lines([
                    'Merci de vous être inscrit sur notre application !',
                    'Avant de commencer, vous devez confirmer votre adresse e-mail en cliquant sur le lien ci-dessous.',
                ])
                ->view('emails.verify-email')
                ->greeting('Bonjour '.$notifiable->name)
                ->action('Je confirme mon addresse mail', $url);
        });

        $user->sendEmailVerificationNotification();

        return response(['user' => $user, 'access_token' => $accessToken]);
    }

    // send reset mail and return response to frontend
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) use ($request) {
                $url = "?token={$token}&email={$request->email}";

                $user->sendPasswordResetNotification($url);
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)])
            : response()->json(['error' => __($status)], 400);
    }

    // reset password and return response to frontend
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required',
        ]);

        $status = Password::reset(
            $request->only('password', 'password_confirmation', 'token', 'email'),
            function ($user, $password) use ($request) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Mot de passe réinitialisé avec succès'])
            : response()->json(['error' => __($status)], 400);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|different:current_password',
            'password_confirmation' => 'required'
        ]);

        /**
         * @var User $user
         */
        $user = auth()->guard('api')->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Le mot de passe actuel est incorrect'
            ], 422);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json([
            'message' => 'Mot de passe modifié avec succès'
        ]);
    }
}
