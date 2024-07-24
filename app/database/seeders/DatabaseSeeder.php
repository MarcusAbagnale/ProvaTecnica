<?php

namespace Database\Seeders;

use App\Models\Pessoas;
use App\Models\Enderecos;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        Pessoas::factory()->create([
            'nome' => 'Jhon Wayne',
            'nomeSocial' => 'Batman',
            'cpf' => '11122255577',
            'nomeMae' => 'Martha Wayne',
            'nomePai' => 'Thomas Wayne',
            'telefone' => '99999999999',
            'email'=> 'batman@caverna.com'
        ]);

        Enderecos::factory()->create([
            'idPessoa' => 1,
            'tipo' => 'Comercial',
            'cep' => '690008032',
            'logradouro' => 'Rua Martha Wayne',
            'numero' => '99',
            'complemento' => 'Perto do Parque',
            'estado' => 'AK',
            'cidade' => 'Gothan',
        ]);
    }
}
