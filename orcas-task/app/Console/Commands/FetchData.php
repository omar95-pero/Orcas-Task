<?php

namespace App\Console\Commands;

use App\Jobs\DataJob;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Hash;
class FetchData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle(){
        $data = Http::get("https://60e1b5fc5a5596001730f1d6.mockapi.io/api/v1/users/users_1")->body();
        foreach (array_chunk(json_decode($data),10) as $element){
            $element = collect($element)->map(function ($var){
                return [
                    'firstName'=>$var->firstName,
                    'lastName'=>$var->lastName,
                    'email'=>$var->email,
                    'avatar'=>$var->avatar,
                    'password'=>Hash::make(123456789),
                    'created_at'=>Carbon::now(),
                    'updated_at'=>Carbon::now(),
                ];
            });
            $element = array_values($element->toArray());
//            dd($element);
            dispatch(new DataJob($element));
        }
    }
}
