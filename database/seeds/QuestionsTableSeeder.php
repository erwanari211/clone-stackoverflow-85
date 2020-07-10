<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Question;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $users = User::get();

        for ($q=1; $q <= 15; $q++) {
            $question = Question::create([
                'user_id' => $faker->randomElement($users)->id,
                'title' => $faker->sentence,
                'content' => $faker->paragraph,
                'tag' => implode(',', $faker->words),
            ]);

            $countComments = $faker->numberBetween(2, 5);
            for ($qc=0; $qc < $countComments; $qc++) {
                $question->addComment([
                    'user_id' => $faker->randomElement($users)->id,
                    'content' => $faker->paragraph,
                ]);
            }

            $countVotes = $faker->numberBetween(2, 15);
            $selectedUsers = $faker->randomElements($users, $countVotes);
            foreach ($selectedUsers as $selectedUser) {
                $question->votes()->create([
                    'user_id' => $selectedUser->id,
                    'vote_type' => $faker->boolean(80),
                ]);
            }

            $question->countVote();
        }
    }
}
