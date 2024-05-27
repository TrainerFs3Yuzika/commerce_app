<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
//use Image;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->image;

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path() . '/temp', $newName);

            // Generate thumbnail
            $sourcePath = public_path() . '/temp/' . $newName;
            $destPath = public_path() . '/temp/thumb/' . $newName;
            $image = Image::make($sourcePath);
            $image->fit(300, 275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/' . $newName),
                'message' => 'foto berhasil di upload',
            ]);
        }
    }
    //     $image = $request->image; ini ga ada di kamu

    //     if (!empty($request->$image)) {
    //         $image = $request->image;
    //         $extenstion = $image->getClienOriginalExtension();
    //         $newFileName = time().'.'.$extenstion;

    //         $tempImage = new TempImage();
    //         $tempImage->name = $newFileName;
    //         $tempImage->save();

    //         $image->move(public_path() . '/temp', $newFileName);

    //         //Generate thumbnail
    //         $sourcePath = public_path().'/temp/'.$newFileName;
    //         $destPath = public_path().'/temp/thumb'.$newFileName;
    //         $image = Image::make($sourcePath);
    //         $image ->fit(300,275);
    //         $image ->save($destPath);

    //         return response()->json([
    //             'status'=>true,
    //             'name'=>$newFileName,
    //             'id'=>$tempImage->id
    //             // 'status' => true,
    //             // 'image_id' => $tempImage->id,
    //             // 'ImagePath' => asset('/temp/thumb/'.$newName),
    //             // 'message' => 'foto berhasil di upload',
    //         ]);
    //     }
    // }

}

