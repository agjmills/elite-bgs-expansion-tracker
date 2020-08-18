<?php

namespace App\Console\Commands;

use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class GetSystemsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'systems:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets data about systems';

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
        $data = file_get_contents('https://eddb.io/archive/v6/systems_populated.json');

        $decodedData = json_decode($data);

        foreach($decodedData as $system) {
            $data = [
                'index' => 'systems',
                'id' => $system->id,
                'type' => 'system',
                'body' => [
                    'id' => $system->id,
                    'name' => $system->name,
                    'edsm_id' => $system->edsm_id,
                    'x' => $system->x,
                    'y' => $system->y,
                    'z' => $system->z,
                    'population' => $system->population,
                    'government_id' => $system->government_id,
                    'government' => $system->government,
                    'allegiance' => $system->allegiance,
                    'allegiance_id' => $system->allegiance_id,
                    'security' => $system->security,
                    'primary_economy_id' => $system->primary_economy_id,
                    'primary_economy' => $system->primary_economy,
                    'needs_permit' => $system->needs_permit,
                    'controlling_minor_faction_id' => $system->controlling_minor_faction_id,
                    'controlling_minor_faction' => $system->controlling_minor_faction,
                    'minor_faction_presences' => $system->minor_faction_presences,
                    'ed_system_address' => $system->ed_system_address,
                ],
            ];

            $client->index($data);
        }
    }
}
