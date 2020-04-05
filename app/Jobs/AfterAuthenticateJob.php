<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Shop's instance.
     *
     * @var ShopModel
     */
    protected $shop;

    /**
     * Create a new job instance.
     *
     * @param ShopModel $shop The shop's object
     * @return void
     */
    public function __construct(ShopModel $shop)
    {
        $this->shop = $shop;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->shop->finished_setup) {
            // Setup has finished, this should've ran by now, kill it
            return;
        }

        if (!$this->shop->isGrandfathered()) {
            $planName = $this->shop->api()->rest('GET', '/admin/shop.json')->body->shop->plan_name;
            if ($planName === 'affiliate' || $planName === 'staff_business') {
                $this->shop->shopify_grandfathered = true;
                $this->shop->finished_setup = true;
                $this->shop->save();
            }
        }
        
        // Send welcome email

        // Fetch all products
    }
}
