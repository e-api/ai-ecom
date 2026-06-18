<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegisterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this
        ->subject(
            'Welcome to AI Powered E-commerce'
        )
        ->view(
            'emails.register'
        );
    }

    /**
     * Get the message envelope.
    
    *public function envelope(): Envelope
    *{
    *    return new Envelope(
    *        subject: 'User Register Mail',
    *    );
    *}
    */

    /**
     * Get the message content definition.
     
    *public function content(): Content
    *{
    *    return new Content(
    *        view: 'view.name',
    *    );
    *}
    */

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     
    *public function attachments(): array
    *{
    *    return [];
    *}
    */
}