<?php

namespace App\Console;

use Illuminate\Console\Command;
use App\Institution;
use App\DuckDuckGo;

class DuckDuckGoFirstUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duckduckgo:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Queries DuckDuckGo for institutions' missing URL";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $duckduckgo;
    public function __construct(DuckDuckGo $duckduckgo)
    {
        $this->duckduckgo = $duckduckgo;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $institutions = Institution::whereNull('url')->get();

        $quotaCount = 50;

        $bar = $this->output->createProgressBar(count($institutions));

        foreach ($institutions as $institution) {
            if (!$quotaCount--) {
                $quotaCount = 50;
                sleep(1);
            }

            $url = $this->duckduckgo->queryFirstUrl($institution->name);

            $bar->advance();

            if (empty($url)) {
                continue;
            }

            $institution->update(['url' => $url]);
        }

        $bar->finish();
    }
}
