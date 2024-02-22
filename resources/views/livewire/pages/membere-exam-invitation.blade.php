<div class="text-white">
    @livewire('components.quiz-details-card', ['quiz' => $quiz])



    @if ($message)
        <div
            class=" m-2  shadow-md rounded-md p-4 @if ($type == 'success') bg-green-500 @else bg-red-500 @endif">
            <div class='text-white text-lg'>{{ $message }}</div>
        </div>
    @else
        <div class="mt-4 space-x-4 w-ful flex items-center justify-center">
            <button wire:click='acceptInvitation'
                class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Accept
            </button>
            <button wire:click='declineInvitation'
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Decline
            </button>
        </div>
    @endif

    {{-- <div class="mt-4 space-x-4 w-ful flex items-center justify-center">
        <button wire:click='acceptInvitation'
            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            Accept
        </button>
        <button wire:click='declineInvitation'
            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Decline
        </button>
    </div> --}}


</div>
