<?php
namespace Modules\FileLinkModule\Database\factories;

use Carbon\Carbon;

use App\Models\User;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

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
