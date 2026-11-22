<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StatusEnum;
use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\BnCategory;
use App\Models\BnContent;
use App\Models\BnSpecialCategory;
use App\Models\BnSubCategory;
use App\Models\PhotoAlbum;
use App\Models\Tag;
use App\Models\User;
use App\Models\Video;
use App\Models\Writer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function dashboard() {
        $totalContentCount = BnContent::query()->where('show_content', StatusEnum::ACTIVE->value)->count();
        $totalCategoryCount = BnCategory::query()->where('status', StatusEnum::ACTIVE->value)->count();
        $totalSubCategoryCount = BnSubCategory::query()->where('status', StatusEnum::ACTIVE->value)->count();
        $totalSpecialCategoryCount = BnSpecialCategory::query()->where('status', StatusEnum::ACTIVE->value)->count();
        $totalUploadTodayCount = BnContent::whereDate('created_at', Carbon::today())->count();
        $totalTagCount = Tag::query()->where('status', StatusEnum::ACTIVE->value)->count();
        $totalPhotoAlbumCount = PhotoAlbum::query()->where('status', StatusEnum::ACTIVE->value)->count();
        $totalVideoCount = Video::query()->where('status', StatusEnum::ACTIVE->value)->count();

        return view('admin.dashboard.index', [
            'totalContentCount' => $totalContentCount,
            'totalCategoryCount' => $totalCategoryCount,
            'totalSubCategoryCount' => $totalSubCategoryCount,
            'totalSpecialCategoryCount' => $totalSpecialCategoryCount,
            'totalUploadTodayCount' => $totalUploadTodayCount,
            'totalTagCount' => $totalTagCount,
            'totalPhotoAlbumCount' => $totalPhotoAlbumCount,
            'totalVideoCount' => $totalVideoCount,
        ]);
    }

    /**
     * Admin Profile
     */
    public function adminProfile()
    {
        return view('admin.profile.index');
    }


    /**
     * Admin Change Photo view
     */
    public function adminEditProfile()
    {
        return view('admin.profile.edit-profile');
    }

    /**
     * Admin Change Photo functionality
     */
    public function adminUpdateProfile(Request $request)
    {
        $user = Auth::user();

        // Validate all fields
        $request->validate([
            'name'  => 'required',
            'phone' => 'required',
        ]);

        $user->name     = $request->name;
        $user->phone    = $request->phone;

        $user->update();

        return redirect()->route('adminProfile')->with('message', 'You update your profile information successfully.');
    }


    /**
     * Admin Change Photo view
     */
    public function adminChangePhoto()
    {
        return view('admin.profile.change-photo');
    }

    /**
     * Admin Change Photo functionality
     */
    public function adminUpdatePhoto(Request $request)
    {
        $user = Auth::user();

        // Validate all fields
        $request->validate([
            'photo' => 'required|image',
        ]);

        if ($request->photo) {
            $user->photo = FileUploadHelper::uploadFile($request->photo, $user->photo);
        }

        $user->update();

        return redirect()->route('adminProfile')->with('message', 'You changed your photo successfully.');
    }


    /**
     * Admin Change Password view
     */
    public function adminChangePassword()
    {
        return view('admin.profile.change-password');
    }


    /**
     * Admin change password functionality
     */
    public function adminUpdatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate all fields
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $request->validate(
            [
                'current_password' => 'required',
                'password' => 'required|confirmed|min:6|different:current_password',
            ],
            [
                'password.different' => 'New password must be different from current password.',
            ]
        );

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->update();

        // Clear all session data
        Session::flush();

        // Logout the user
        Auth::logout();

        // Invalidate current session & regenerate CSRF token
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('loginForm')->with('message', 'You changed your password successfully. Please login again.');
    }

}
