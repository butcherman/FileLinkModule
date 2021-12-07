<?php
namespace Modules\FileLinkModule\Database\factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FileLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\FileLinkModule\Entities\FileLink::class;

    /**
     * Define the model's default state.
     */
    public function definition()
    {
        return [
            'user_id'      => User::factory()->create()->user_id,
            'link_hash'    => Str::uuid(),
            'link_name'    => $this->faker->name(),
            'expire'       => Carbon::now()->addDays(30),
            'instructions' => $this->faker->sentence(),
            'allow_upload' => $this->faker->boolean(),
        ];
    }
}

