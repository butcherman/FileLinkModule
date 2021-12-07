<?php

namespace Modules\FileLinkModule\Tests\Feature;

use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\FileLinkModule\Entities\FileLink;

class FileLinkModuleTest extends TestCase
{
    /**
     * Index Method
     */
    public function test_index_guest()
    {
        $response = $this->get(route('FileLinkModule.index'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_index_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.index'));
        $response->assertStatus(403);
    }

    public function test_index()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.index'));
        $response->assertSuccessful();
    }

    /**
     * Create Method
     */
    public function test_create_guest()
    {
        $response = $this->get(route('FileLinkModule.create'));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_create_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.create'));
        $response->assertStatus(403);
    }

    public function test_create()
    {
        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.create'));
        $response->assertSuccessful();
    }

    /**
     * Store Method
     */
    public function test_store_guest()
    {
        $data = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(30),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->post(route('FileLinkModule.store'), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_store_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $data = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(30),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('FileLinkModule.store'), $data);
        $response->assertStatus(403);
    }

    public function test_store()
    {
        $data = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(30),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs(User::factory()->create())->post(route('FileLinkModule.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('file_links', [
            'link_name' => $data['link_name'],
            'allow_upload' => $data['allow_upload'],
            'instructions' => $data['instructions'],
        ]);
    }

    /**
     * Show Method
     */
    public function test_show_guest()
    {
        $fileLink = FileLink::factory()->create();

        $response = $this->get(route('FileLinkModule.show', $fileLink->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_show_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.show', $fileLink->link_id));
        $response->assertStatus(403);
    }

    public function test_show()
    {
        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.show', $fileLink->link_id));
        $response->assertSuccessful();
    }

    /**
     * Edit Method
     */
    public function test_edit_guest()
    {
        $fileLink = FileLink::factory()->create();

        $response = $this->get(route('FileLinkModule.edit', $fileLink->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_edit_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user]);

        $response = $this->actingAs($user)->get(route('FileLinkModule.edit', $fileLink->link_id));
        $response->assertStatus(403);
    }

    public function test_edit_different_user()
    {
        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs(User::factory()->create())->get(route('FileLinkModule.edit', $fileLink->link_id));
        $response->assertStatus(403);
    }

    public function test_edit_as_admin()
    {
        $user     = User::factory()->create(['role_id' => 1]);
        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs($user)->get(route('FileLinkModule.edit', $fileLink->link_id));
        $response->assertSuccessful();
    }

    public function test_edit()
    {
        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user]);

        $response = $this->actingAs($user)->get(route('FileLinkModule.edit', $fileLink->link_id));
        $response->assertSuccessful();
    }

    /**
     * Update Method
     */
    public function test_update_guest()
    {
        $fileLink = FileLink::factory()->create();
        $data     = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(90),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->put(route('FileLinkModule.update', $fileLink->link_id), $data);
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_update_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user]);
        $data     = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(90),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs($user)->put(route('FileLinkModule.update', $fileLink->link_id), $data);
        $response->assertStatus(403);
    }

    public function test_update_different_user()
    {
        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create();
        $data     = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(90),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs($user)->put(route('FileLinkModule.update', $fileLink->link_id), $data);
        $response->assertStatus(403);
    }

    public function test_update_as_admin()
    {
        $user     = User::factory()->create(['role_id' => 1]);
        $fileLink = FileLink::factory()->create();
        $data     = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(90),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs($user)->put(route('FileLinkModule.update', $fileLink->link_id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('file_links', [
            'link_id'      => $fileLink->link_id,
            'link_name'    => $data['link_name'],
            'allow_upload' => $data['allow_upload'],
            'instructions' => $data['instructions'],
        ]);
    }

    public function test_update()
    {
        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user->user_id]);
        $data     = [
            'link_name'        => 'Test Link',
            'expire'           => Carbon::now()->addDays(90),
            'allow_upload'     => false,
            'has_instructions' => true,
            'instructions'     => 'Here is a file.  Do with it as you please',
        ];

        $response = $this->actingAs($user)->put(route('FileLinkModule.update', $fileLink->link_id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('file_links', [
            'link_id'      => $fileLink->link_id,
            'link_name'    => $data['link_name'],
            'allow_upload' => $data['allow_upload'],
            'instructions' => $data['instructions'],
        ]);
    }

    /**
     * Destroy Method
     */
    public function test_destroy_guest()
    {
        $fileLink = FileLink::factory()->create();

        $response = $this->delete(route('FileLinkModule.destroy', $fileLink->link_id));
        $response->assertStatus(302);
        $response->assertRedirect(route('login.index'));
        $this->assertGuest();
    }

    public function test_destroy_no_permission()
    {
        UserRolePermissions::whereRoleId(4)->whereHas('UserRolePermissionTypes', function($q)
        {
            $q->where('description', 'Use File Links');
        })->first()->update(['allow' => false]);

        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user]);

        $response = $this->actingAs($user)->delete(route('FileLinkModule.destroy', $fileLink->link_id));
        $response->assertStatus(403);
    }

    public function test_destroy_different_user()
    {
        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs($user)->delete(route('FileLinkModule.destroy', $fileLink->link_id));
        $response->assertStatus(403);
    }

    public function test_destroy_as_admin()
    {
        $user     = User::factory()->create(['role_id' => 1]);
        $fileLink = FileLink::factory()->create();

        $response = $this->actingAs($user)->delete(route('FileLinkModule.destroy', $fileLink->link_id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('file_links', $fileLink->only(['link_id']));
    }

    public function test_destroy()
    {
        $user     = User::factory()->create();
        $fileLink = FileLink::factory()->create(['user_id' => $user->user_id]);

        $response = $this->actingAs($user)->delete(route('FileLinkModule.destroy', $fileLink->link_id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('file_links', $fileLink->only(['link_id']));
    }

    //  TODO - Make sure attached files are deleted
}
