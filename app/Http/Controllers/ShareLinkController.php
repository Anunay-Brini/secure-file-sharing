<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\ShareLink;
use App\Models\AccessLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ShareLinkController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file_id' => 'required|exists:files,id',
            'expires_at' => 'nullable|date',
            'download_limit' => 'nullable|integer|min:1',
        ]);

        $file = File::where('id', $request->file_id)->where('user_id', auth()->id())->firstOrFail();

        $shareLink = ShareLink::create([
            'file_id' => $file->id,
            'uuid' => Str::uuid()->toString(),
            'expires_at' => $request->expires_at ? now()->addHours($request->expires_at) : null,
            'download_limit' => $request->download_limit,
        ]);

        return response()->json(['share_link' => $shareLink]);
    }

    public function show($uuid)
    {
        $shareLink = ShareLink::with('file')->where('uuid', $uuid)->firstOrFail();

        if ($shareLink->is_revoked) {
            abort(404, 'This link has been revoked.');
        }

        if ($shareLink->expires_at && $shareLink->expires_at->isPast()) {
            abort(404, 'This link has expired.');
        }

        if ($shareLink->download_limit && $shareLink->download_count >= $shareLink->download_limit) {
            abort(404, 'Download limit reached.');
        }

        return view('share.show', compact('shareLink'));
    }

    public function download(Request $request, $uuid)
    {
        $shareLink = ShareLink::with('file')->where('uuid', $uuid)->firstOrFail();

        // Security / Validity checks
        if ($shareLink->is_revoked || 
           ($shareLink->expires_at && $shareLink->expires_at->isPast()) || 
           ($shareLink->download_limit && $shareLink->download_count >= $shareLink->download_limit)) {
            
            AccessLog::create([
                'share_link_id' => $shareLink->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'denied'
            ]);
            abort(403, 'Link invalid or expired.');
        }

        // Increment limit and log
        $shareLink->increment('download_count');
        AccessLog::create([
            'share_link_id' => $shareLink->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success'
        ]);

        return Storage::disk('local')->download('files/' . $shareLink->file->stored_path, $shareLink->file->id . '.enc');
    }

    public function revoke(ShareLink $shareLink)
    {
        if ($shareLink->file->user_id !== auth()->id()) {
            abort(403);
        }

        $shareLink->update(['is_revoked' => true]);

        return redirect()->back()->with('status', 'link-revoked');
    }
}
