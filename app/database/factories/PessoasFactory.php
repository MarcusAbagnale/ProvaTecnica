<?php

namespace Database\Factories;

use App\Models\Pessoas;
use Illuminate\Database\Eloquent\Factories\Factory;

class PessoasFactory extends Factory
{
    protected $model = Pessoas::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word,
            'cpf' => $this->faker->word,
            'nomeSocial' => $this->faker->word,
            'nomeMae' => $this->faker->word,
            'nomePai' => $this->faker->word,            
            'telefone' => $this->faker->word,
            'email' => $this->faker->word,
        ];
    }
}
