<?php

namespace App\Console\Commands;

use App\DTO\Crawling\CrawlingCurrencyDTO;
use App\DTO\Currency\CurrencyDTO;
use App\Repositories\Currency\CurrencyRepository;
use App\Services\v2\Crawling\CurrencyCrawlingService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateAllCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-currency:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('start');

        $records = (new CurrencyCrawlingService)->crawlCurrency(null, null);

        $dtos = collect([]);
        $records->each(function (CrawlingCurrencyDTO $item) use (&$dtos) {
            $dtos->push(CurrencyDTO::fromCrawlingCurrencyDTO($item));
        });

        DB::transaction(function () use ($dtos) {
            (new CurrencyRepository)->insert($dtos);
        });

        $this->info('end');
    }
}
