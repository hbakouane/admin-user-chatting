<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $users;
    public $messages = '';
    public $sender;
    public $message;
    public $file;
    public $not_seen;

    public function render()
    {
        return view('livewire.show', [
            'users' => $this->users,
            'messages' => $this->messages,
            'sender' => $this->sender
        ]);
    }

    public function mount() {
        if (auth()->user()->is_admin == false) {
            $this->messages = \App\Models\Message::where('user_id', auth()->id())
                                                    ->orWhere('receiver', auth()->id())
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
        } else {
            $this->messages = \App\Models\Message::where('user_id', $this->sender->id)
                                                    ->orWhere('receiver', $this->sender->id)
                                                    ->orderBy('id', 'DESC')
                                                    ->get();
        }
        $not_seen = \App\Models\Message::where('user_id', $this->sender->id)->where('receiver', auth()->id());
        $not_seen->update(['is_seen' => true]);
    }

    public function SendMessage() {
        $new_message = new \App\Models\Message();
        $new_message->message = $this->message;
        $new_message->user_id = auth()->id();
        $new_message->receiver = $this->sender->id;

        // Deal with the file if uploaded
        if ($this->file) {
            $uploaded = $this->uploadFile();
            $new_message->file = $uploaded[0];
            $new_message->file_name = $uploaded[1];
        }
        
        $new_message->save();
        // Clear the message after it's sent
        $this->reset('message');
        $this->file = '';
    }

    public function resetFile() 
    {
        $this->reset('file');
    }

    public function uploadFile() {
        $file = $this->file->store('public/files');
        $path = url(Storage::url($file));
        $file_name = $this->file->getClientOriginalName();
        return [$path, $file_name];
    }

}
