<?php

namespace Modules\FileLinkModule\Http\Controllers;

use Inertia\Inertia;

use App\Http\Controllers\Controller;

use Modules\FileLinkModule\Entities\User;
use Modules\FileLinkModule\Entities\FileLink;

class AdminController extends Controller
{
    /**
     * Display a listing of the users along with a count of file links
     */
    public function index()
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinkModule::Admin/Index', [
            'file_links' => User::get()->makeHidden(['email', 'first_name', 'last_name', 'initials', 'FileLink']),
        ]);
    }

    /**
     * Show the list of file links for the selected user
     */
    public function show($id)
    {
        $this->authorize('manage', FileLink::class);

        $user = User::where('username', $id)->firstOrFail();

        return Inertia::render('FileLinkModule::Index', [
            'link_list' => FileLink::where('user_id', $user->user_id)->orderBy('expire', 'DESC')->get(),
            'is_admin'  => true,
            'user'      => $user->full_name,
        ]);
    }
}
