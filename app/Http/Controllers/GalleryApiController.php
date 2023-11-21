<?php

/**
 * @OA\OpenApi(
 *      @OA\Info(
 *          description="Apivira",
 *          version="0.0.1",
 *          title="Contoh API documentation (pertemuan 12)",
 *          termsOfService="http://swagger.io/terms/",
 *          @OA\Contact(
 *              email="queenranamra@gmail.com"
 *          ),
 *          @OA\License(
 *              name="Apache 2.0",
 *              url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *          )
 *      )
 * )
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;
use App\Models\Post;


class GalleryApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/getgalleryapi",
     *     tags={"data gallery"},
     *     summary="Retrieve gallery data",
     *     description="Check API Gallery",
     *     operationId="GetGalleryApi",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */

    public function getGalleryApi()
    {
        $post = Post::all();
        return response()->json(["data" => $post]);
    }


    public function index()
    {
        $response = Http::get('http://127.0.0.1:9000/api/gallery');
        $data = $response->object()->data;

        return view('auth_custom.index', compact('data'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth_custom.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/postgallery",
     *     tags={"upload gallery images"},
     *     summary="Upload gallery images",
     *     description="Endpoint for uploading images",
     *     operationId="storeGallery",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Data for uploading images",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     description="Title",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     description="Image Description",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="picture",
     *                     description="Image Files",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Successful operation"
     *     )
     * )
     */

    public function storeGallery(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999',
        ]);

        // Membuat folder 'posts_image' jika belum ada
        $folderPath = public_path('storage/posts_image');
        if (!File::isDirectory($folderPath)) {
            File::makeDirectory($folderPath, 0777, true, true);
        }

        $filenameSimpan = 'noimage.png';

        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $filenameSimpan = "{$basename}.{$extension}";

            $savepath = public_path('storage/posts_image/' . $filenameSimpan);

            $image = Image::make($request->file('picture'))
                ->fit(375, 235)
                ->save($savepath);
        }

        $post = new Post;
        $post->picture = $filenameSimpan;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();

        return redirect()->route('GetGalleryApi')->with('success', 'Berhasil memperbarui data');

    }
}


