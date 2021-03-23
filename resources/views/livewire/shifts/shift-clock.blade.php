<div>
    @if(! $clockedIn)
        <div class="d-flex justify-content-center">
            <a href="#" wire:click.prevent="clockIn()"
               class="btn btn-primary btn-lg">Clock In</a>
        </div>
    @elseif ($clockedIn && !$clockedOut)
        <div class="d-flex justify-content-center">
            <a href="#" wire:click.prevent="clockOut()"
               class="btn btn-primary btn-lg mb-4">Clock Out</a>
        </div>

        <div class=" d-block">
            <div class="mb-2">
                Shift Started:<strong> {{ $shiftStarted->format('H:i / d-m-Y')  }}</strong>
            </div>

            @if($break)
                <div class="d-flex align-items-center mb-2">
                    Break End:
                    <a href="#" wire:click.prevent="breakPause()"><i class="fas fa-pause fa-2x ml-3" style="color: #1cc88a"></i></a>
                </div>
            @else
                <div class="d-flex align-items-center mb-2">
                    Break Start:
                    <a href="#" wire:click.prevent="breakStart()"><i class="fas fa-play fa-2x ml-3" style="color: #1cc88a"></i></a>
                </div>
            @endif
            {{--Keep break count live--}}
            <div wire:poll.1s="breakTotal()"></div>

            <div>
                Break Started: <strong>{{ $breakStarted ? $breakStarted->format('H:i') : '' }}</strong>
            </div>
            <div>
                Break Total: <strong>{{ Carbon\Carbon::createFromTimestamp($breakTotal)->format('H:i:s')  }}</strong>
            </div>


        </div>
    @endif
</div>
