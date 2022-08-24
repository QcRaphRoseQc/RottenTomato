<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class criticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        //make a random comment of average  based on the score of the critic in french
        $unStar = [
            'Tres mauvais',
            'Mauvais',
            'Ne recommande pas',
            'Bas budget',
            'Peu interessant',
            'Acteurs pas bon',
        ];
        $deuxStars = [
            'Pas mal',
            'Assez bien',
            'J\'ai vu mieux',
            'Budget laisse a desirer',
            'Interessant',
            'Ne recommande pas',
        ];
        $troisStars = [
            'Assez bien',
            'J\'aime',
            'Bon budget',
            'Tres bon mais vas pas gagner d\'oscar',
            'L\'histoire est tres interessante mais les acteurs laisse a desirer',
            'J\'aime bien',
        ];
        $quatreStars = [
            'Excellent film',
            'SUPER bien',
            'J\'adore',
            'Excellent budget',
            'Tres bon mais vas surement etre nominer aux oscar',
            'L\'histoire est tres interessante et les acteurs incroyable'
        ];
        $cinqStars = [
            'Super film',
            'SUPER bien',
            'Excellent budget',
            'Tres bon mais vas surement gagner des oscar',
            'L\'histoire est tres interessante et les acteurs vont gagner des oscar',
            'Mon film prefere'
        ];


        $films = \App\Models\Film::all();
        foreach ($films as $film) {
            $users = \App\Models\User::all();
            for ($i = 0; $i < 5; $i++) {
                $user = $users->random();
                $score = rand(1, 5);
                if ($score == 1) {
                    $comment = $unStar[rand(0, 5)];
                } elseif ($score == 2) {
                    $comment = $deuxStars[rand(0, 5)];
                } elseif ($score == 3) {
                    $comment = $troisStars[rand(0, 5)];
                } elseif ($score == 4) {
                    $comment = $quatreStars[rand(0, 5)];
                } elseif ($score == 5) {
                    $comment = $cinqStars[rand(0, 5)];
                };
                $critic = \App\Models\Critic::create([
                    'user_id' => $user->id,
                    'film_id' => $film->id,
                    'score' => $score,
                    'comment' => $comment,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'login' => $user->login,
                ]);
            }
        }
    }

}
    

