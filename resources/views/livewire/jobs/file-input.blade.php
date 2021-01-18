<div>
    @foreach($requestedInputs as $input)
        {!! $input !!}
    @endforeach

    <hr>
    <a wire:click.prevent="addOrRemoveWage()"
       class="btn btn-sm btn-outline-{{ $wage ? 'danger' : 'info' }}">
        {{ $wage ? 'Close Wage' : 'Wage' }}
    </a>
    <a wire:click.prevent="addOrRemoveOvertime()"
       class="btn btn-sm btn-outline-{{ $overtime ? 'danger': 'info' }}">
        {{ $overtime ? 'Close Overtime': 'Overtime' }}
    </a>
    <a wire:click.prevent="addOrRemoveShiftDifferential()"
       class="btn btn-sm btn-outline-{{ $shiftDifferential ? 'danger' : 'info' }}">
        {{ $shiftDifferential ? 'Close Shift Differential' : 'Shift Differential' }}
    </a>
    <a wire:click.prevent="addOrRemoveExtraTracking()"
       class="btn btn-sm btn-outline-{{ $extraTracking ? 'danger' : 'info' }}">
        {{ $extraTracking ? 'Close Extra Tracking' : 'Extra Tracking' }}
    </a>
</div>
