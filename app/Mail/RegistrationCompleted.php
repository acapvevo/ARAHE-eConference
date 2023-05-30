<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The Registration instance.
     *
     * @var \App\Models\Registration
     */
    public $registration;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        switch ($this->registration->status_code) {
            case 'DR':
                $status = 'Approved';
                break;

            case 'RR':
                $status = 'Rejected';
                break;

            case 'UR':
                $status = 'Amendment';
                break;

            case 'NR':
                $status = 'Resubmission';
                break;

            default:
                $status = '';
                break;
        }

        return new Envelope(
            subject: '[ARAHE' . $this->registration->form->session->year . '] Registration ' . $status,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        switch ($this->registration->status_code) {
            case 'DR':
                $mainMessage1 = 'Your Registration';
                $mainMessage2 = 'has been Approved';
                $secondaryMessage = 'Congratulation, Our admins have approved your Registration. You can choose your package for your trip to our conference. You also may submit your paper in the Submission section if interested.';
                break;

            case 'RR':
                $mainMessage1 = 'Your Registration';
                $mainMessage2 = 'has been Rejected';
                $secondaryMessage = 'Sorry, your Registration have been rejected. Please consult with our admins regarding the registration';
                break;

            case 'UR':
                $mainMessage1 = 'Your Registration';
                $mainMessage2 = 'need Admenment';
                $secondaryMessage = 'Your Registration need some admenment regarding the proof given. Please reupload with new file as proof for your chosen category';
                break;

            case 'NR':
                $mainMessage1 = 'Your Registration';
                $mainMessage2 = 'need to be Resubmitted';
                $secondaryMessage = 'Your Registration has been rejected and need to be resubmitted. Please resubmit the registration again.';
                break;

            default:
                $mainMessage1 = '';
                $mainMessage2 = '';
                $secondaryMessage = '';
                break;
        }

        return new Content(
            markdown: 'emails.registration.complete',
            with: [
                'registration' => $this->registration,
                'mainMessage1' => $mainMessage1,
                'mainMessage2' => $mainMessage2,
                'secondaryMessage' => $secondaryMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
