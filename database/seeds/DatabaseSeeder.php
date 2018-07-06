<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $users, $channels = [];
    private $userArraySize, $channelArraySize;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'David van Pelt',
            'email' => 'david@unitas.nl',
            'password' => bcrypt('secret'),
        ]);
        DB::table('users')->insert([
            'name' => 'Michael Maartense',
            'email' => 'michael@unitas.nl',
            'password' => bcrypt('secret'),
        ]);
        // So quickly create 50 users and give them some random posts & threads
        $this->users = factory(App\User::class, 50)->create();
        $this->userArraySize = count($this->users) - 1;
        $this->channels = factory(App\Channel::class, 10)->create();
        $this->channelArraySize = count($this->channels) - 1;

        foreach ($this->users as $user) {
            factory(App\Thread::class, rand(1, 10))->create([
                'user_id' => $user->id,
                'channel_id' => $this->channels[rand(0, $this->channelArraySize)]->id
            ])->each(function ($thread){
                $amountOfReplies = rand(0, 100);
                for ($subscript = 0; $subscript <= $amountOfReplies; $subscript++) {
                    $randomUser = $this->users[rand(0, $this->userArraySize)];
                    factory(App\Reply::class, 1)->create([
                        'user_id' => $randomUser->id,
                        'thread_id' => $thread->id
                    ]);
                }
            });
        }
    }
}
