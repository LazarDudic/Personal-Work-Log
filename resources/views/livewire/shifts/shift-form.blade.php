<div>
    <hr>

    <form wire:submit.prevent="saveShift" method="POST">
        @csrf
        @include('partials.messages')
        @if($clockedOut)
            <div class="form-group">
                <label for="">Shift Start:</label>
                <input class="form-control" wire:model="started_at" type="datetime-local" value="{{ $started_at }}" required>
            </div>
            <div class="form-group">
                <label for="">Shift End:</label>
                <input class="form-control" wire:model="finished_at" type="datetime-local" value="{{ $finished_at}}" required>
            </div>
            <div class="form-group">
                <label for="">Total Unpaid Break(minutes):</label>
                <input class="form-control" wire:model="break_minutes" type="number" value="{{ $break_minutes }}">
            </div>
            @if($job->tracking->tips)
                <div class="form-group">
                    <label for="">Total Tips:</label>
                    <input class="form-control" wire:model="tips" type="number" min="0" step="any">
                </div>
            @endif
            @if($job->tracking->bonuses)
                <div class="form-group">
                    <label for="">Total Bonuses:</label>
                    <input class="form-control" wire:model="bonuses" type="number"  min="0" step="any">
                </div>
            @endif
            @if($job->tracking->expenses)
                <div class="form-group">
                    <label for="">Total Expenses:</label>
                    <input class="form-control" wire:model="expenses" type="number" min="0" step="any">
                </div>
            @endif

            <button type="submit" class="btn  btn-primary float-right">Save</button>
        @else
            {{--Clock in/out component--}}
            <livewire:shifts.shift-clock />
        @endif

    </form>
</div>
