<?php

namespace App\Jobs;

use App\Models\Explanation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeletePendingExplanations  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = Carbon::now();

        $overdueExplanations = Explanation::where('state', 'pending')
            ->where('created_at', '<', $now->subHour(48)) // Check for dogs created 24 hours ago
            ->get();

        foreach ($overdueExplanations as $explanation) {
            try {
                DB::transaction(function () use ($explanation) {
                    $explanation->order_explanation_id->approvals--;
                    $explanation->save();
                    $explanation->delete(); // حذف السجل

                });
            } catch (\Exception $e) {
                Log::error("Failed to delete explanation with ID {$explanation->id}: {$e->getMessage()}");
            }
        }
    }
}
