<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;

class VerificationController extends Controller
{
    public function verify($user_id, Request $request)
    {
        if (! $request->hasValidSignature()) {
            return response()->json(['msg' => 'Invalid/Expired url provided.'], 401);
        }

        $user = User::findOrFail($user_id);

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to(config('app.url').'/confirm-email');
    }

    public function resend()
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['msg' => 'Email already verified.'], 400);
        }

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

        return response()->json(['msg' => 'Email verification link sent on your email id']);
    }
}
