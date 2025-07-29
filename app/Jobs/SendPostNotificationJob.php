<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\SentNotification;
use App\Mail\PostNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $subscribers = $this->post->website->subscribers()->whereDoesntHave('sentNotifications', function($query) {
            $query->where('post_id', $this->post->id);
        })->get();


        foreach ($subscribers as $subscriber) {

                try {
                    Mail::to($subscriber->email)->send(new PostNotificationMail($this->post, $subscriber));

                    SentNotification::create([
                        'user_id' => $subscriber->id,
                        'post_id' => $this->post->id,
                        'sent_at' => now(),
                    ]);

                    Log::info("Email sent to {$subscriber->email} for post: {$this->post->title}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to {$subscriber->email}: " . $e->getMessage());
                }
            }



        }

}
