<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Show extends Component
{

    public $users;
    public $messages;
    public $sender;
    public $message;

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
            $this->messages = \App\Models\Message::where('user_id', auth()->id())->orWhere('receiver', auth()->id())->orderBy('id', 'DESC')->get();
        } else {
            $this->messages = \App\Models\Message::where('user_id', $this->sender->id)->orWhere('receiver', $this->sender->id)->orderBy('id', 'DESC')->get();
        }
    }

    public function SendMessage() {
        $new_message = new \App\Models\Message();
        $new_message->message = $this->message;
        $new_message->user_id = auth()->id();
        $new_message->receiver = $this->sender->id;
        $new_message->save();
    }

}
