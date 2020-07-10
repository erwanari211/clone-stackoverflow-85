<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\Question;
use App\Models\Answer;

class AnswersTableSeeder extends Seeder
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
        $questions = Question::get();

        foreach ($questions as $question) {
            $countAnswers = $faker->numberBetween(2, 5);
            for ($a=0; $a < $countAnswers; $a++) {
                $answer = Answer::create([
                    'user_id' => $faker->randomElement($users)->id,
                    'question_id' => $question->id,
                    'content' => $faker->paragraph,
                ]);

                $countComments = $faker->numberBetween(2, 5);
                for ($ac=0; $ac < $countComments; $ac++) {
                    $answer->addComment([
                        'user_id' => $faker->randomElement($users)->id,
                        'content' => $faker->paragraph,
                    ]);
                }
            }

            $hasAnswer = $faker->boolean(60);
            if ($hasAnswer) {
                $bestAnswer = $question->answers()->first();
                $bestAnswer->setAsBestAnswer();
            }

        }
    }
}
