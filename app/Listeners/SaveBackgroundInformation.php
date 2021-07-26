<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserServiceInterface;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveBackgroundInformation
{
    protected $userService;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserServiceInterface $userServiceInterface)
    {
        $this->userService = $userServiceInterface;
    }

    /**
     * Handle the event.
     *
     * @param  UserSaved  $event
     * @return void
     */
    public function handle(UserSaved $event)
    {
        $this->userService->saveDetails($event->user);
    }
}
