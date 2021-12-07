<?php

use App\Models\User;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;
use App\Models\UserRoles;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModualUserSettings extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        //  User permission types
        $perm1 = UserRolePermissionTypes::create([
            'description'   => 'Use File Links',
            'is_admin_link' => false,
        ]);
        $perm2 = UserRolePermissionTypes::create([
            'description'   => 'Manage File Links',
            'is_admin_link' => true,
        ]);

        //  Add the module to all user roles, add management to only Role ID's 1 & 2
        $userRoles = UserRoles::all();
        foreach($userRoles as $role)
        {
            UserRolePermissions::create([
                'role_id'      => $role->role_id,
                'perm_type_id' => $perm1->perm_type_id,
                'allow'        => true,
            ]);

            UserRolePermissions::create([
                'role_id'      => $role->role_id,
                'perm_type_id' => $perm2->perm_type_id,
                'allow'        => in_array($role->role_id, [1, 2]) ? true : false,
            ]);
        }

        //  Add the settings for the individual users
        $userSetting = UserSettingType::create([
            'name'         => 'Auto Delete Expired Links (after 30 days)',
            'perm_type_id' => $perm1->perm_type_id,
        ]);

        $userList = User::withTrashed()->get();
        foreach($userList as $user)
        {
            UserSetting::create([
                'user_id'         => $user->user_id,
                'setting_type_id' => $userSetting->setting_type_id,
                'value'           => true,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        UserSettingType::where('name', 'Auto Delete Expired Links (after 30 days)')->delete();
        UserRolePermissionTypes::where('description', 'Manage File Links')->delete();
        UserRolePermissionTypes::where('description', 'Use File Links')->delete();
    }
}
