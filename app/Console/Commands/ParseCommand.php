<?php

namespace App\Console\Commands;

use App\Models\Parse;
use App\Services\ParserLogService;
use Illuminate\Console\Command;
use Symfony\Component\DomCrawler\Crawler;
use function Sodium\add;

class ParseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:start {page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse start';

    private $service;

    /**
     * ParseCommand constructor.
     *
     * @param ParserLogService $parserLogService
     */
    public function __construct(ParserLogService $parserLogService)
    {
        parent::__construct();
        $this->service = $parserLogService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->service->insertInDb($this->argument('page'));

        return print_r('Data copied from the site');
    }

}
