<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use App\Services\Operations;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\TextUI\XmlConfiguration\UpdateSchemaLocation;

class MainController extends Controller
{
    public function index()
    {
        //load users notes
        $id = session('user.id');
        $notes = User::find($id)->notes()->get()->toArray();


        ///show home view
        return view('home', ['notes' => $notes]);
    }

    public function newNote()
    {
        return view('new_note');
    }

    public function newNoteSubmit(Request $request)
    {
        //validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            //errors messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',
                'text_note.required' => 'A nota é obrigatória',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );

        //get user id
        $id = session('user.id');

        //create new note
        $note = new Note();
        $note->user_id = $id;
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();

        //redirect to home
        return redirect()->route('home');
    }

    public function editNote($id)
    {
        $id = Operations::decryptId($id);
        $note = Note::find($id);

        return view('edit_note', compact('note'));
    }

    public function editNoteSubmit(Request $request)
    {
        //validate request
        $request->validate(
            [
                'text_title' => 'required|min:3|max:200',
                'text_note' => 'required|min:3|max:3000'
            ],
            //errors messages
            [
                'text_title.required' => 'O título é obrigatório',
                'text_title.min' => 'O título deve ter pelo menos :min caracteres',
                'text_title.max' => 'O título deve ter no máximo :max caracteres',
                'text_note.required' => 'A nota é obrigatória',
                'text_note.min' => 'A nota deve ter pelo menos :min caracteres',
                'text_note.max' => 'A nota deve ter no máximo :max caracteres',
            ]
        );

        //check if note_id exists
        if ($request->note_id == null) {
            return redirect()->route('home');
        }
        //decrypt note id
        $id = Operations::decryptId($request->note_id);
        //load note
        $note = Note::find($id);
        //update note
        $note->title = $request->text_title;
        $note->text = $request->text_note;
        $note->save();
        //redirect
        return redirect()->route('home');
    }

    public function deleteNote($id)
    {
        $id = Operations::decryptId($id);

        echo "$id";
    }
}
