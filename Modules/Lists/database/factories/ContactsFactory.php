<?php

namespace Modules\Contacts\Database\Factories;

use Modules\Contacts\Models\Contacts;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactsFactory extends Factory
{
    protected $model = Contacts::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            //'workspace_id' => \App\Models\Workspace::factory(), // Ajustar según tu configuración
            'custom_attributes' => [],
        ];
    }
}
