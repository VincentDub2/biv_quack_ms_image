<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{
    /**
     * Upload an image.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        // Validation de l'image
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Sauvegarder l'image dans le dossier `storage/app/public/images`
        $filePath = $request->file('image')->store('images', 'public');

        // Enregistrer les métadonnées dans la base de données
        $image = new \App\Models\Image();
        $image->file_name = basename($filePath);
        $image->file_path = $filePath;
        $image->mime_type = $request->file('image')->getClientMimeType();
        $image->file_size = $request->file('image')->getSize();
        $image->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
            'data' => $image,
        ], 201);
    }

    public function show($id)
    {
        $image = \App\Models\Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->json([
            'data' => $image,
        ]);
    }

    public function index()
    {
        $images = \App\Models\Image::paginate(10); // 10 images par page

        return response()->json([
            'data' => $images,
        ]);
    }

    public function destroy($id)
    {
        $image = \App\Models\Image::find($id);

        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        // Supprimer le fichier de stockage
        \Storage::disk('public')->delete($image->file_path);

        // Supprimer de la base de données
        $image->delete();

        return response()->json(['message' => 'Image deleted successfully']);
    }




}
