<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

use App\Models\Set;

class ImagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function images(Request $request):Renderable
    {
        $user = Auth::user();
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', $user->id)
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        $storagePath = 'public/images/' . Auth::id() . '/' . $set['id'];
        $imagesPaths = Storage::disk('local')->files($storagePath);
        $imagesUrls = [];
        foreach ($imagesPaths as $imagePath) {
            $imagesUrls[] = URL::to(Storage::url($imagePath));
        }

        return view('pages.images', [
            'token' => $user->api_token,
            'dangerous_actions_key' => $user->dangerous_actions_key,
            'set' => $set,
            'images' => $imagesUrls,
        ]);
    }

    public function add(Request $request):RedirectResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        $imagePath = 'public/images/' . Auth::id() . '/' . $set['id'];
        Storage::disk('local')->put($imagePath, $request->image);

        return redirect()->route('images', (int)$set['id']);
    }

    public function delete(Request $request):RedirectResponse
    {
        // TODO: delete file
        return redirect()->route('images', (int)$set['id'])->with('status', 'Success: The data item was deleted.');
    }
}
