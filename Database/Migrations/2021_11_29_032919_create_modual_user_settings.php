<?php

use Illuminate\Database\Migrations\Migration;

use App\Models\User;
use App\Models\UserRoles;
use App\Models\UserSetting;
use App\Models\UserSettingType;
use App\Models\UserRolePermissions;
use App\Models\UserRolePermissionTypes;

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
        $settings        = ['Auto Delete Expired Links (after 30 days)'];
        $permissionTypes = ['Manage File Links', 'Use File Links'];

        //  Remove the User Settings options
        foreach($settings as $set)
        {
            //  Get the Setting type so we can use the setting_type_id
            $setting = UserSettingType::where('name', $set)->first();

            $permissions = UserSetting::where('setting_type_id', $setting->setting_type_id)->get();
            foreach($permissions as $perm)
            {
                $perm->delete();
            }

            $setting->delete();
        }

        //  Remove the User Role Permission options
        foreach($permissionTypes as $set)
        {
            //  Get the permission type so we can use the perm_type_id
            $setting = UserRolePermissionTypes::where('description', $set)->first();

            $permissions = UserRolePermissions::where('perm_type_id', $setting->perm_type_id)->get();
            foreach($permissions as $perm)
            {
                $perm->delete();
            }

            $setting->delete();
        }



        // UserSettingType::where('name', 'Auto Delete Expired Links (after 30 days)')->delete();
        // UserRolePermissionTypes::where('description', 'Manage File Links')->delete();
        // UserRolePermissionTypes::where('description', 'Use File Links')->delete();
    }
}
