<?php

/*
 |--------------------------------------------------------------------------
 | GoBiz vCard SaaS
 |--------------------------------------------------------------------------
 | Developed by NativeCode © 2021 - https://nativecode.in
 | All rights reserved
 | Unauthorized distribution is prohibited
 |--------------------------------------------------------------------------
*/

namespace App\Http\Controllers\User;

use App\Medias;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    // All user media
    public function media(Request $request)
    {
        $active_plan = json_decode(Auth::user()->plan_details);

        if ($active_plan != null) {
            if ($request->ajax()) {
                $media = Medias::where('user_id', Auth::user()->user_id)
                    ->orderBy('id', 'desc')
                    ->paginate(8);

                // Format the created_at date
                $media->getCollection()->transform(function ($item, $key) {
                    $item->formatted_created_at = \Carbon\Carbon::parse($item->created_at)->diffForHumans();
                    $item->size = $this->formatBytes($item->size);
                    $item->base_url = asset('');
                    return $item;
                });

                return response()->json(['media' => $media]);
            }

            // Total storage
            $totalStorage = $active_plan->storage;

            // Used storage
            $usedStorage = Medias::where('user_id', Auth::user()->user_id)->sum('size');

            $usedStorage = round($usedStorage / 1024 / 1024, 2);

            // Used storage in percentage
            $usedStoragePercentage = round(($usedStorage / $totalStorage) * 100, 2);

            // Available storage
            $availableStorage = $totalStorage - $usedStorage;

            // Available storage in percentage
            $availableStoragePercentage = round(($availableStorage / $totalStorage) * 100, 2);

            $settings = Setting::where('status', 1)->first();

            return view('user.pages.media.index', compact('settings', 'totalStorage', 'usedStorage', 'usedStoragePercentage', 'availableStorage', 'availableStoragePercentage'));
        } else {
            return redirect()->route('user.plans');
        }
    }

    // Add media
    public function addMedia()
    {
        // Queries
        $settings = Setting::where('status', 1)->first();

        return view('user.pages.media.add', compact('settings'));
    }

    // Upload media
    public function uploadMedia(Request $request)
    {
        // Parameters
        $image = $request->file('file');

        // Validate file presence and type
        if (!$image || !in_array($image->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
            return response()->json(['status' => 'error', 'message' => trans('Invalid file format.')], 400);
        }

        // Total customer used storage
        $totalUsedStorage = Medias::where('user_id', Auth::user()->user_id)->sum('size');

        // Unique ID
        $uniqid = uniqid();

        // Get size before move
        $imageSize = $image->getSize();

        // Check user storage limit
        $active_plan = json_decode(Auth::user()->plan_details);

        // Total
        $totalStorage = (float) $totalUsedStorage + (float) $imageSize;

        $totalStorage = round($totalStorage / 1024 / 1024, 2);

        if ($active_plan != null) {
            if ($active_plan->storage != null) {
                if ($totalStorage > $active_plan->storage) {
                    return response()->json(['status' => 'error', 'message' => trans('You have reached your storage limit.')], 200);
                }
            }
        }

        // Set filename and path
        $imageName = Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
        $media_url = "images/" . $imageName;

        // Move file to public/images
        $image->move(public_path('images'), $imageName);

        // Save record
        $card = new Medias();
        $card->media_id = $uniqid;
        $card->user_id = Auth::user()->user_id;
        $card->media_name = $image->getClientOriginalName();
        $card->media_url = $media_url;
        $card->size = $imageSize;
        $card->save();

        return response()->json(['status' => 'success', 'message' => trans('Uploaded!')]);
    }

    public function deleteMedia(Request $request)
    {
        // Queries
        $media_data = Medias::where('user_id', Auth::user()->user_id)->where('media_id', $request->query('id'))->first();

        // Check media
        if ($media_data != null) {

            // Delete media image
            Medias::where('user_id', Auth::user()->user_id)->where('media_id', $request->query('id'))->delete();

            return redirect()->route('user.media')->with('success', trans('Removed!'));
        }
    }

    public function multipleImages(Request $request)
    {
        // Parameters
        $image = $request->file('file');

        // Validate file presence and type
        if (!$image || !in_array($image->extension(), ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
            return response()->json(['status' => 'error', 'message' => trans('Invalid file format.')], 400);
        }

        // Total customer used storage
        $totalUsedStorage = Medias::where('user_id', Auth::user()->user_id)->sum('size');

        // Unique ID
        $uniqid = uniqid();

        // Get image size before moving
        $imageSize = $image->getSize();

        // Check user storage limit
        $active_plan = json_decode(Auth::user()->plan_details);

        // Total
        $totalStorage = (float) $totalUsedStorage + (float) $imageSize;

        $totalStorage = round($totalStorage / 1024 / 1024, 2);

        if ($active_plan != null) {
            if ($active_plan->storage != null) {
                if ($totalStorage > $active_plan->storage) {
                    return response()->json(['status' => 'error', 'message' => trans('You have reached your storage limit.')], 200);
                }
            }
        }

        // Set filename and path
        $imageName = Auth::user()->user_id . '-' . $uniqid . '.' . $image->extension();
        $media_url = "images/" . $imageName;

        // Move file to public/images
        $image->move(public_path('images'), $imageName);

        // Save
        $card = new Medias();
        $card->media_id = $uniqid;
        $card->user_id = Auth::user()->user_id;
        $card->media_name = $image->getClientOriginalName();
        $card->media_url = $media_url;
        $card->size = $imageSize;
        $card->save();

        return response()->json(['status' => 'success', 'message' => trans('Uploaded!'), 'image_url' => $imageName]);
    }

    // vCard and Store media upload
    public function getMediaData(Request $request)
    {
        if ($request->ajax()) {
            $media = Medias::where('user_id', Auth::user()->user_id)
                ->orderBy('id', 'desc');

            return DataTables::of($media)->make(true);
        }

        return view('media.index');
    }

    // Convert to MB
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        if ($bytes <= 0) return '0 B';
        $pow = floor(log($bytes, 1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }
}
