@extends('layouts.app')
@push('vite')
    
    @vite(['resources/js/absenceDec.js'])
@endpush

@section('content')

<div class="container">
    <div class="d-flex justify-content-between my-3 row">
        <h2 class="col">
            <b class="border-bottom border-black border-2 no-break"><em>{{__('Déclaration d\'absence')}} </em></b>
        </h2>
        <div class="col d-flex justify-content-end align-items-center">
            <div id="clock">
                <p class="date no-break"></p>
                <p class="time no-break"></p>
            </div>
        </div>
    </div>

    <div class="overflow-x-scroll">
        <div class="card text-center">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    @if (Auth::user()->isResponsable())
                        @foreach($shifts as $shift)
                        <li class="nav-item">
                            <a class="shift nav-link text-black fw-bolder @if($loop->first) active @endif" href="#" data-shift-id="{{ $shift->id }}">{{ $shift->name }}</a>
                        </li>
                        @endforeach
                    @endif
                    <li class="nav-item">
                        <a class="shift nav-link text-black fw-bolder @if (Auth::user()->isRH()) active @endif " href="#" data-shift-id="result">Résultat</a>
                    </li>
                </ul>
            </div>
            @if (Auth::user()->isResponsable())
                @foreach($shifts as $shift)
                <div class="card-body p-0 pb-2 shift-card-body @if(!$loop->first) d-none @endif" id="shift-card-body-{{ $shift->id }}">
                    <form action="{{ route('absenceDec.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="shift_id" value="{{ $shift->id }}">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employés</th>
                                    <th>Nom & Prénom</th>
                                    <th>Présence</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr id="user-row-{{ $user->id }}">
                                        <td class="d-flex justify-content-center h-100">
                                            <label class="containerCheck">
                                                <input type="checkbox" name="employee_ids[]" value="{{ $user->id }}" 
                                                @if($attendances->contains(function ($attendance) use ($user, $shift) { return $attendance->user_id == $user->id && $attendance->shift_id == $shift->id; })) checked @endif>
                                                <div class="checkmark"></div>
                                            </label>
                                        </td>
                                        <td>{{ $user->nom . ' ' . $user->prénom }}</td>
                                        <td class="d-flex justify-content-center">
                                            <div class="attendance-buttons" id="attendance-buttons-{{ $user->id }}">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-secondary btn-status présent @if($attendances->contains(function ($attendance) use ($user, $shift) { return $attendance->user_id == $user->id && $attendance->shift_id == $shift->id && $attendance->status == 'Présent'; })) active @endif" data-status="Présent" title="Présent">P</button>
                                                    <input type="hidden" name="status_{{ $user->id . '_' . $shift->id }}" id="status_{{ $user->id . '_' . $shift->id }}" value="@if($attendances->contains(function ($attendance) use ($user, $shift) { return $attendance->user_id == $user->id && $attendance->shift_id == $shift->id; })) {{ $attendances->firstWhere(function ($attendance) use ($user, $shift) { return $attendance->user_id == $user->id && $attendance->shift_id == $shift->id; })->status }} @endif">
                                                    <button type="button" class="btn btn-secondary btn-status absent @if($attendances->contains(function ($attendance) use ($user, $shift) { return $attendance->user_id == $user->id && $attendance->shift_id == $shift->id && $attendance->status == 'Absent'; })) active @endif" data-status="Absent" title="Absent">A</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" id="attendance-data" name="attendance_data" value="">
                        <button type="submit" class="btn btn-primary">{{__('Soumettre')}}</button>
                    </form>
                </div>
                @endforeach
            @endif
            <div class="card-body p-0 pb-2 shift-card-body @if (Auth::user()->isResponsable()) d-none @endif" id="shift-card-body-result">
                <!-- Result Statistics Table -->
                <table class="table table-bordered" id="result-shifts-table">
                    <thead>
                        <tr>
                            @if (Auth::user()->isRH())
                                <th>Service</th>
                            @endif
                            <th>Shift</th>
                            <th>Employés</th>
                            <th>Présence</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $serviceGroups = []; // Array to group shifts by service
                        @endphp

                        @foreach($résultatData as $result)
                            {{-- Initialize the service group if it doesn't exist --}}
                            @if (!isset($serviceGroups[$result->service]))
                                @php
                                    $serviceGroups[$result->service] = [];
                                @endphp
                            @endif

                            {{-- Initialize the shift group within the service if it doesn't exist --}}
                            @if (!isset($serviceGroups[$result->service][$result->shift_name]))
                                @php
                                    $serviceGroups[$result->service][$result->shift_name] = [];
                                @endphp
                            @endif

                            {{-- Add employee attendance to the shift group within the service --}}
                            @php
                                $serviceGroups[$result->service][$result->shift_name][] = $result;
                            @endphp
                        @endforeach

                        {{-- Loop through service groups --}}
                        @foreach ($serviceGroups as $serviceName => $shiftGroups)
                            @php
                                $serviceRowspan = 0; // Number of rows for rowspan in the service group
                                foreach ($shiftGroups as $shiftName => $shiftData) {
                                    $serviceRowspan += count($shiftData);
                                }
                            @endphp

                            {{-- Loop through shift groups within the service --}}
                            @foreach ($shiftGroups as $shiftName => $shiftData)
                                @php
                                    $shiftRowspan = count($shiftData); // Number of rows for rowspan in the shift group
                                @endphp

                                {{-- Start row for the service and shift --}}
                                <tr>
                                    {{-- Only show the service name for the first shift of the service --}}
                                    @if (Auth::user()->isRH())
                                        @if ($loop->first)
                                        <td rowspan="{{ $serviceRowspan }}" class="align-middle text-center">{{ $serviceName }}</td>
                                        @endif
                                    @endif

                                    {{-- Only show the shift name for the first employee of the shift --}}
                                    <td rowspan="{{ $shiftRowspan }}" class="align-middle text-center">{{ $shiftName }}</td>
                                    
                                    {{-- Loop through employees in the shift --}}
                                    @foreach ($shiftData as $key => $data)
                                        <td class="align-middle">{{ $data->nom }} {{ $data->prénom }}</td>
                                        <td class="align-middle">{{ $data->status }}</td>
                                    </tr>
                                    
                                    {{-- Close the row if it's not the last employee in the shift --}}
                                    @if ($key < $shiftRowspan - 1)
                                        <tr>
                                    @endif
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
                <button class="btn btn-primary" id="export-button">Export to Excel</button>
            </div>
        </div>
    </div>

</div>
<!-- Include TableExport library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/TableExport/5.2.0/js/tableexport.min.js"></script>

@endsection
