<?php

namespace App\Console;

use Illuminate\Console\Command;
use App\Institution;
use App\GoogleMaps;

class GoogleLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:locations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Queries Google for institutions' missing locations";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $gmaps;
    public function __construct(GoogleMaps $gmaps)
    {
        $this->gmaps = $gmaps;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $institutions = Institution::whereNull('latitude')->get();

        $quotaCount = 50;

        $bar = $this->output->createProgressBar(count($institutions));

        foreach ($institutions as $institution) {
            if (!$quotaCount--) {
                $quotaCount = 50;
                sleep(1);
            }

            $location = $this->gmaps->queryLatLng($institution->name);

            $bar->advance();

            if (empty($location['lat'])) {
                continue;
            }

            $institution->update([
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
            ]);
        }

        $bar->finish();
    }
}
