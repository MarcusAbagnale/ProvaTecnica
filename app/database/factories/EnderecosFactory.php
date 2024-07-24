<?php

namespace Database\Factories;

use App\Models\Enderecos;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnderecosFactory extends Factory
{
    protected $model = Enderecos::class;

    public function definition()
    {
        return [
            'tipo' => $this->faker->word,
            'cep' => $this->faker->word,
            'logradouro' => $this->faker->word,
            'numero' => $this->faker->word,
            'complemento' => $this->faker->word,            
            'bairro' => $this->faker->word,
            'estado' => $this->faker->word,
            'cidade' => $this->faker->word,
        ];
    }
}
