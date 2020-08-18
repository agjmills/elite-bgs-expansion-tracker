<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GetFactionsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'factions:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets data about factions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = ClientBuilder::create()->setHosts(['elasticsearch'])->build();
        ini_set('memory_limit', -1);
        $data = file_get_contents('https://eddb.io/archive/v6/factions.json');

        $decodedData = json_decode($data);

        foreach($decodedData as $faction) {
            $data = [
                'index' => 'factions',
                'id' => $faction->id,
                'type' => 'faction',
                'body' => [
                    'id' => $faction->id,
                    'name' => $faction->name,
                    'updated_at' => $faction->updated_at,
                    'government_id' => $faction->government_id,
                    'government' => $faction->government,
                    'allegiance_id' => $faction->allegiance_id,
                    'allegiance' => $faction->allegiance,
                    'home_system_id' => $faction->home_system_id,
                    'is_player_faction' => $faction->is_player_faction,
                ],
            ];

            $client->index($data);
        }
    }
}
