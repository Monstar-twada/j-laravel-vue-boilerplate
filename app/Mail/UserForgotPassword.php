<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Entities\User;

class UserForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user;
        $user->password_reset = 1;
        $user->password_reset_at = Carbon::now();
        $user->save();

        $admin_email = \Config::get('mail.from');

        $subject = "Change {$app_name} password";

        $url = \Config::get('app.url');
        $app_name = \Config::get('app.name');
        $token = encrypt("id={$user->id}");
        $token = preg_split("/=/", $token)[0]; //temporary fix

        $data = new \stdClass();

        $data->link = $url.'/change-password/'.$token;
        $data->user = $user->email;
        $data->admin = $admin_email['address'];

        return $this->view('mails.user.forgot_password',[ 'data' => $data ])
                    ->from($admin_email['address'],$admin_email['name'])
                    ->to($user->email,$user->full_name)
                    ->subject($subject);
    }
}
