<?php

namespace App\Console\Commands;

use App\Domain\Services\FingerDeviceService;
use App\Infrastructure\Persistence\Eloquent\EloquentFingerDeviceRepository;
use Illuminate\Console\Command;

class storeAttendances extends Command
{

    protected $signature = 'attendance:store';

    protected $description = 'Command description';

    public function handle()
    {
        // TODO Uncomment this part
//        $fingerDeviceService = new FingerDeviceService(new EloquentFingerDeviceRepository());
//        $fingerDeviceService->storeAttendanceFromFingerDevices();

        echo "All attendance has been taken for all employees , " . now() . "\n";
    }
}
