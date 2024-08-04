<?php

namespace App\Http\Controllers;

use App\Http\Requests\NoteRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function create(NoteRequest $request)
    {

        try {
            // check first that no have this title before for this user 
            $user_id = $request->user_id;
            $title = $request->title;
            $note = Note::where('user_id', $user_id)->where('title', $title)->first();
            if ($note) {
                return response()->json([
                    'message' => 'Note with this title already exists'
                ], 400);
            }
            $note = Note::create($request->only('title', 'text', 'user_id'));
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in create note',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function index(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $notes = Note::where('user_id', $user_id)->get();
            if ($notes->isEmpty()) {
                return response()->json([
                    'message' => 'Notes not found'
                ], 404);
            }
            return response()->json($notes);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in get notes',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user_id = $request->user_id;
            $note = Note::where('user_id', $user_id)->where('id', $id)->first();
            if (!$note) {
                return response()->json([
                    'message' => 'Note not found'
                ], 404);
            }
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in get note',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function update(NoteUpdateRequest $request, $id)
    {
        try {
            $user_id = $request->user_id;
            $note = Note::where('user_id', $user_id)->where('id', $id)->first();
            if (!$note) {
                return response()->json([
                    'message' => 'Note not found'
                ], 404);
            }
            $note->update($request->only('title', 'text'));
            return response()->json($note);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in update note',
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function destroy(Request $request, $id)
    {
        try {
            $user_id = $request->user_id;
            $note = Note::where('user_id', $user_id)->where('id', $id)->first();
            if (!$note) {
                return response()->json([
                    'message' => 'Note not found'
                ], 404);
            }
            $note->delete();
            return response()->json([
                'message' => 'Note deleted'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error in delete note',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
