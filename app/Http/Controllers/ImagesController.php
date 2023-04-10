<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Tinify;

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

    public function add(Request $request):RedirectResponse|Renderable
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id', 'name'])
            ->toArray()[0];

        if (!empty($request->bulk_upload)) {
            ini_set('memory_limit', '1024M');
            set_time_limit(1200);

            $storagePath = Storage::disk('local')->put('', $request->bulk_upload);
            $bulkImages = file_get_contents(Storage::path($storagePath));
            Storage::disk('local')->delete($storagePath);
            $bulkImages = preg_split("/\r\n|\n|\r/", trim($bulkImages));

            $imagePath = 'public/images/' . Auth::id() . '/' . $set['id'];
            $results = [];
            foreach ($bulkImages as $image) {
                [$code, $url] = explode('|', $image);
                $filename = 'appsFilesUpload';
                $tmpImage = tempnam(sys_get_temp_dir(), $filename);
                if ($request->get('optimize') === 'yes') {
                    Tinify\setKey(env('TINY_PNG_KEY'));
                    $imageSrc = Tinify\fromUrl($url);

                    if (!empty($request->get('width')) && !empty($request->get('height'))) {
                        $imageSrc = $imageSrc->resize([
                            "method" => "thumb",
                            "width" => (int)$request->get('width'),
                            "height" => (int)$request->get('height'),
                        ]);
                    }
                    $imageSrc->toFile($tmpImage);
                } else {
                    copy($url, $tmpImage);
                }

                $uploadedPath = Storage::disk('local')->putFile($imagePath, $tmpImage);
                unlink($tmpImage);
                $results[] = $code . '|' . URL::to(Storage::url($uploadedPath));
            }

            return view('pages.images_upload_result', [
                'set' => $set,
                'result' => implode("\n", $results)
            ]);
        }

        if ($request->get('optimize') === 'yes') {
            Tinify\setKey(env('TINY_PNG_KEY'));
            $imageSrc = Tinify\fromFile($request->image);

            if (!empty($request->get('width')) && !empty($request->get('height'))) {
                $imageSrc = $imageSrc->resize([
                    "method" => "thumb",
                    "width" => (int)$request->get('width'),
                    "height" => (int)$request->get('height'),
                ]);
            }
            $imageSrc->toFile($request->image);
        }

        $imagePath = 'public/images/' . Auth::id() . '/' . $set['id'];
        Storage::disk('local')->put($imagePath, $request->image);

        return redirect()->route('images', (int)$set['id']);
    }

    public function delete(Request $request):RedirectResponse
    {
        $set = Set
            ::where('id', $request->route('set_id'))
            ->where('user', Auth::id())
            ->limit(1)
            ->get(['id'])
            ->toArray()[0];

        // TODO: delete file
        return redirect()->route('images', (int)$set['id'])->with('status', 'Success: The data item was deleted.');
    }
}
