<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $files = $request->user()->files()->latest()->get();
        return view('dashboard', compact('files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'encrypted_file' => 'required|file',
            'encrypted_name' => 'required|string',
            'encrypted_mime_type' => 'required|string',
            'file_iv' => 'required|string',
        ]);

        $uploadedFile = $request->file('encrypted_file');
        
        // Calculate hash of the encrypted content for integrity
        $hash = hash_file('sha256', $uploadedFile->getRealPath());

        $storedPath = Str::uuid()->toString() . '.enc';
        
        // Store the file securely (local disk for MVP)
        $uploadedFile->storeAs('files', $storedPath, 'local');

        $fileRecord = File::create([
            'user_id' => $request->user()->id,
            'name' => $request->input('encrypted_name'),
            'stored_path' => $storedPath,
            'mime_type' => $request->input('encrypted_mime_type'),
            'iv' => $request->input('file_iv'),
            'size' => $uploadedFile->getSize(),
            'hash' => $hash,
        ]);

        return response()->json(['message' => 'File uploaded successfully', 'file' => $fileRecord]);
    }

    public function show(File $file)
    {
        // Simple authorization: only owner or through a valid share link
        // Share links will have their own controller/logic, so here we only allow owner
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        return Storage::disk('local')->download('files/' . $file->stored_path, $file->id . '.enc');
    }

    public function destroy(File $file)
    {
        if ($file->user_id !== auth()->id()) {
            abort(403);
        }

        Storage::disk('local')->delete('files/' . $file->stored_path);
        $file->delete();

        return redirect()->back()->with('status', 'file-deleted');
    }
}
