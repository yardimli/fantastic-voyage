<?php

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Mail\Mailable;
	use Illuminate\Mail\Mailables\Content;
	use Illuminate\Mail\Mailables\Envelope;
	use Illuminate\Queue\SerializesModels;

	class WelcomeMail extends Mailable
	{
		use Queueable, SerializesModels;

		public $name;
		public $email;

		public function __construct($name, $email)
		{
			$this->name = $name;
			$this->email = $email;
		}

		public function build()
		{
				$subject = 'Welcome to Coolxue! Your exciting journey to fun learning begins here.';
				$email_view = 'emails.welcome';

			return $this->from(env('MAIL_FROM_ADDRESS','support@fantastic-voyage.com'), env('MAIL_FROM_NAME', 'CoolXue Support'))
			            ->subject($subject)
			            ->view($email_view)
			            ->with(['name' => $this->name, 'email' => $this->email]);
		}
	}
