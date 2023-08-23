<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * A class list
     *
     * @var string[]
     */
    private $classList = [
        'Horti',
        'JP',
        'BTP',
        'ELEC',
        'SI',
        'SVT',
    ];

    /**
     * Class lvl
     *
     * @var string[]
     */
    private $classLvls = [
        'CAPA',
        '2nd',
        '1ere',
        'Terminale'
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $class = \collect($this->classList)->random();
        return [
            'class' => \collect($this->classLvls)->random() . " {$class}",
            'name' => "{$class} {$this->faker->randomLetter}"
        ];
    }
}
