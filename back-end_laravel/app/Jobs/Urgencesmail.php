<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\Urgence;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class Urgencesmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $lieu;
    public $heure;
    public $description;
    public $photo; 

    public function __construct($lieu,$heure,$description,$photo)
    {
        $this->lieu = $lieu;
        $this->heure = $heure;
        $this->description = $description;
        $this->photo = $photo;
    }

    /**
     * Execute the job.
    **/
   
    public function handle(): void
    {
        $receive = User::where("role", 'receptionniste')->get();
        foreach ($receive as $value) {
            // Mail::to($value->email)->send(new Urgence());
            Mail::to($value->email)->queue(new Urgence($value->email, $this->lieu, $this->heure, $this->description, $this->photo));
        }
    }
    
}