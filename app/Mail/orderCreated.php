<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class orderCreated extends Mailable
{
    use Queueable, SerializesModels;
    private $order;
    private $products;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order=$order;
        $this->products=$this->order->shoppingCart->articles();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('santiagomartinochoaestrada@gmail.com')
                ->view('mailers.order',['order'=>$this->order,'products'=>$this->products]);
    }
}
