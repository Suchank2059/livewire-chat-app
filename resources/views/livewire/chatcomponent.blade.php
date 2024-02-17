<?php

use App\Models\Message;
use App\Events\NewMessageEvent;
use function Livewire\Volt\{state, mount, on};
state(['messages' => []]);
state(['message' => '']);

mount(function () {
    $this->messages = Message::all();
});

$sendMessage = function () {
    $sentMessage = Message::create([
        'message' => $this->message,
        'user_id' => Auth::user()->id,
    ]);
    $this->reset('message');
    NewMessageEvent::dispatch($sentMessage);
};

on([
    'echo-private:newMessage,NewMessageEvent' => function () {
        $this->messages = Message::all();
    },
]);

?>

<div>
    <ul class="p-4 space-y-5">
        @foreach ($messages as $message)
            @if ($message->user_id != Auth::user()->id)
                <li class="max-w-lg flex gap-x-2 sm:gap-x-4">
                    <div
                        class="bg-white border border-gray-200 rounded-2xl p-4 space-y-3 dark:bg-slate-900 dark:border-gray-700">
                        <h2 class="font-medium text-gray-800 dark:text-white">
                            {{ $message->user->name }}
                        </h2>
                        <div class="space-y-1.5">
                            <p class="mb-1.5 text-sm text-gray-800 dark:text-white">
                                {{ $message->message }}
                            </p>
                        </div>
                    </div>
                </li>
            @else
                <li class="max-w-lg ms-auto flex justify-end gap-x-2 sm:gap-x-4">
                    <div class="grow text-end space-y-3">
                        <!-- Card -->
                        <div class="inline-block bg-blue-600 rounded-2xl p-4 shadow-sm">
                            <h2 class="font-medium text-white dark:text-white">
                                {{ $message->user->name }}
                            </h2>
                            <div class="space-y-1.5 mt-2">
                                <p class="mb-1.5 text-sm text-white dark:text-white">
                                    {{ $message->message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>
            @endif
        @endforeach
    </ul>
    <div class="p-4">
        <label for="hs-trailing-button-add-on" class="sr-only">Label</label>
        <div class="flex rounded-lg shadow-sm">
            <input wire:model="message" type="text" id="hs-trailing-button-add-on" name="hs-trailing-button-add-on"
                class="py-3 px-4 block w-full border-gray-200 shadow-sm rounded-s-lg text-sm focus:z-10 focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600">
            <button wire:click="sendMessage" type="button"
                class="py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-e-md border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                Send
            </button>
        </div>
    </div>
</div>
