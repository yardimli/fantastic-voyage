<?php

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Mail\Mailable;
	use Illuminate\Mail\Mailables\Content;
	use Illuminate\Mail\Mailables\Envelope;
	use Illuminate\Queue\SerializesModels;

	class ResetPasswordMail extends Mailable
	{
		use Queueable, SerializesModels;

		public $token;
		public $email;

		public function __construct($token, $email)
		{
			$this->token = $token;
			$this->email = $email;
		}

		public function build()
		{
				$subject = 'Password Reset Request';
				$email_view = 'emails.reset_password';

			return $this->from(env('MAIL_FROM_ADDRESS','support@fantastic-voyage.com'), env('MAIL_FROM_NAME', 'Fantastic Voyage Support'))
			            ->subject($subject)
			            ->view($email_view)
			            ->with(['token' => $this->token, 'email' => $this->email]);
		}
	}
