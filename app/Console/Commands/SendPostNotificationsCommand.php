<?php

namespace App\Console\Commands;

use App\Jobs\SendPostNotificationJob;
use App\Models\Post;
use Illuminate\Console\Command;

class SendPostNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:send-notifications {post_id? : The ID of the specific post}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $postId = $this->argument('post_id');
        if ($postId) {
            $post = Post::find($postId);

            if (!$post) {
                $this->error("Post with ID {$postId} not found.");
                return 1;
            }

            SendPostNotificationJob::dispatch($post);
            $this->info("Notification job queued for post: {$post->title}");
        } else {
            $allPosts = Post::all();

            if ($allPosts->isEmpty()) {
                $this->info('No recent posts found.');
                return 0;
            }

            foreach ($allPosts as $post) {
                SendPostNotificationJob::dispatch($post);
            }

            $this->info("Notification jobs queued for {$allPosts->count()} posts.");
        }
    }
}
