<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
 
class ResetMailable extends Mailable
{
    use Queueable,
        SerializesModels;

    protected $data;

    //build the message.

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     *
     * @return void
     */
    public function build()
    {
        return $this->view('reset')->with('data', $this->data);
    }
}
