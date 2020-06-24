<?php

namespace Modules\Tickets\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Tickets\Entities\Ticket;

class TicketOpenedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $ticket;
    public $recipient;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, $recipient)
    {
        $this->ticket = $ticket;
        $this->recipient = $recipient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(supportEmail()['email'], supportEmail()['name'])
            ->subject(langmail('tickets.opened.subject', ['code' => $this->ticket->code, 'subject' => $this->ticket->subject]))
            ->markdown('emails.tickets.opened');
    }
}
