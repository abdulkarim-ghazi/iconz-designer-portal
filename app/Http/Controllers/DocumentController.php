<?php

namespace App\Http\Controllers;

use App\Models\DesignerRecord;
use App\Models\RecordDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function store(Request $request, DesignerRecord $record): RedirectResponse
    {
        $this->ensureAccess($request, $record);

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'documents' => ['required', 'array'],
            'documents.*' => ['file', 'max:20480', 'mimes:jpg,jpeg,png,webp,pdf,txt,doc,docx,xls,xlsx'],
        ]);

        foreach ($request->file('documents', []) as $file) {
            $path = $file->store("records/{$record->id}", 'public');
            $record->documents()->create([
                'uploaded_by' => $request->user()->id,
                'title' => $data['title'] ?: $file->getClientOriginalName(),
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }

        return back()->with('status', 'تم رفع الوثائق.');
    }

    public function show(Request $request, RecordDocument $document): StreamedResponse
    {
        $this->ensureAccess($request, $document->record);

        return Storage::disk('public')->response($document->path, $document->original_name);
    }

    public function destroy(Request $request, RecordDocument $document): RedirectResponse
    {
        $this->ensureAccess($request, $document->record);
        Storage::disk('public')->delete($document->path);
        $document->delete();

        return back()->with('status', 'تم حذف الوثيقة.');
    }

    private function ensureAccess(Request $request, DesignerRecord $record): void
    {
        abort_unless($request->user()->isAdmin() || $record->designer_id === $request->user()->id, 403);
    }
}
