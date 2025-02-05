@extends('layouts.app')

@push('vite')
    @vite(['resources/js/absenceDec.js'])
@endpush

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between my-3 row">
            <h2 class="col">
                <b class="border-bottom border-black border-2 no-break"><em>{{ __('Déclaration d\'absence') }} </em></b>
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
                    <div class="row d-flex justify-content-between">
                        @if ( Auth::user()->isRH())
                            {{-- <div class="col">
                                <select id="group-select" class="form-select float-start w-fc">
                                    @foreach ($shifts->groupBy('group')->sortKeysDesc() as $group => $shiftGroup)
                                        <option value="{{ $group }}" >{{ $group }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col">
                                <a class="btn btn-primary float-start" id="export-button" href="{{ route('export.shifts', ['date' => request('date')]) }}">Export to Excel</a>
                            </div>

                            <div class="col d-flex align-items-center gap-2">
                                {{-- <div class="">
                                    <button type="button" class="btn btn-sm btn-warning mt-1" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="fa fa-gear"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="btn btn-light w-100 h-100" id="add-user-button"
                                                data-bs-toggle="modal" data-bs-target="#addUserModal">Ajouter</button>
                                        </li>
                                    </ul>
                                </div> --}}
                                <div>
                                    <form action="{{ route('absenceDec.index') }}" method="GET" class="d-flex">
                                        @csrf
                                        <input type="date" name="date" value="{{ $today }}"
                                            class="form-control mt-1 me-1">
                                        <button class="btn btn-sm btn-outline-primary py-0" type="submit"> chercher
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-12">
                                <ul class="nav nav-tabs card-header-tabs w-fc float-end">
                                    @if (Auth::user()->isResponsable())
                                        {{-- @foreach ($shifts as $shift)
                                            <li class="nav-item shift-item" data-group="{{ $shift->group }}">
                                                <a class="shift nav-link text-black fw-bolder @if ($loop->first) active @endif"
                                                    href="#" data-shift-id="{{ $shift->id }}">{{ $shift->name }}</a>
                                            </li>
                                        @endforeach --}}
                                    @endif
                                    @if (Auth::user()->isRH())
                                    <li class="nav-item">
                                        <a class="shift nav-link text-black fw-bolder active"
                                            href="#" data-shift-id="resu lt">Résultat</a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>

                @if (Auth::user()->isResponsable())
    <div class="card-body p-0 pb-2 shift-card-body">
        <form action="{{ route('attendance.declare') }}" method="POST">
            @csrf
            <table class="table table-bordered shift-table">
                <thead>
                    <tr>
                        <th>Nom & Prénom</th>
                        <th>Présence</th>
                        <th>Shift</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        @php
                            $status = '';
                            $selectedShift = null; // Initialize selectedShift variable
                            
                            // Iterate through declaredAttendances to find the status and shift for the current user
                            foreach ($declaredAttendances as $attendance) {
                                if ($attendance->user_id == $user->id) {
                                    $status = $attendance->status;
                                    $selectedShift = $attendance->shift_id; // Set the selected shift for the user
                                    break;
                                }
                            }
                        @endphp
                        <tr id="user-row-{{ $user->id }}">
                            <td>{{ $user->nom . ' ' . $user->prénom }}</td>
                            <td class="d-flex justify-content-center">
                                <div class="attendance-buttons" id="attendance-buttons-{{ $user->id }}">
                                    <div class="btn-group" role="group">
                                        {{-- Attendance buttons for Présent and Absent --}}
                                        <button type="button" class="btn btn-secondary btn-status présent
                                            @if ($status === 'Présent') active @endif"
                                            data-user-id="{{ $user->id }}" data-status="Présent">P</button>

                                        <button type="button" class="btn btn-secondary btn-status absent
                                            @if ($status === 'Absent') active @endif"
                                            data-user-id="{{ $user->id }}" data-status="Absent">A</button>
                                    </div>
                                    <input type="hidden" name="status[{{ $user->id }}]" id="status-{{ $user->id }}"
                                           value="{{ $status }}">
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <select class="form-select shift-select" name="shift[{{ $user->id }}]" id="shift-{{ $user->id }}">
                                        @foreach ($shifts as $shift)
                                            <option value="{{ $shift->id }}"
                                                @if ($shift->id == $selectedShift) selected @endif>
                                                {{ $shift->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>
    </div>
@endif



<script>
    function setAttendanceStatus(userId, status) {
    const statusInput = document.getElementById(`status_${userId}`);
    statusInput.value = status;

    // Toggle the active class for the buttons
    const presentBtn = document.querySelector(`#attendance-buttons-${userId} .présent`);
    const absentBtn = document.querySelector(`#attendance-buttons-${userId} .absent`);

    if (status === 'Présent') {
        presentBtn.classList.add('active');
        absentBtn.classList.remove('active');
    } else {
        presentBtn.classList.remove('active');
        absentBtn.classList.add('active');
    }
}

</script>
                @if (Auth::user()->isRH())
    <div class="card-body p-0 pb-2 shift-card-body" id="shift-card-body-result">
        <!-- Result Statistics Table -->
        <table class="table" id="shift-table-result">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Employés</th>
                    <th>
                        <div class="d-flex align-items-center gap-3">
                            Service
                            <select id="service-filter" class="form-select">
                                <option value="">All</option>
                                @foreach ($uniqueServices as $service)
                                <option value="{{ $service->service }}">{{ $service->service }}</option>
                                @endforeach
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-flex align-items-center gap-3">
                            Présence
                            <select id="presence-filter" class="form-select">
                                <option value="">All</option>
                                <option value="Présent">Présent</option>
                                <option value="Absent">Absent</option>
                                <option value="Non déclaré">Non déclaré</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        Shift
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @php
                        $status = 'Non déclaré'; // Default status if no attendance is found
                        $userShift = 'Non déclaré'; // Default shift if no shift is declared
                        
                        // Loop through declared attendances to find status and shift_id
                        foreach ($declaredAttendances as $attendance) {
                            if ($attendance->user_id == $user->id) {
                                $status = $attendance->status;

                                // Find the shift name by matching shift_id with the shifts array
                                foreach ($shifts as $shift) {
                                    if ($shift->id == $attendance->shift_id) {
                                        $userShift = $shift->name; // Get the shift name
                                        break;
                                    }
                                }
                                break; // Break loop once the attendance for the user is found
                            }
                        }
                    @endphp
                    <tr>
                        <td class="align-middle">{{ $user->matricule }}</td>
                        <td class="align-middle">{{ $user->nom }} {{ $user->prénom }}</td>
                        <td class="align-middle">{{ $user->service }}</td>
                        <td class="align-middle">
                            <span class="p-1 rounded {{ $status == 'Présent' ? 'text-bg-success' : ($status == 'Absent' ? 'text-bg-danger' : 'text-bg-warning') }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="align-middle">{{ $userShift }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif



    <!-- Modal for Adding Users to Shift -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="add-user-form" action="{{ route('updateShift') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="addUserModalLabel">Ajouter des Employées au shift "<span id="shiftSelected" class=" text-decoration-underline"></span>"</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="selected_shift_id" id="selected_shift_id">
                        <div class="form-group">
                            <!-- Search Input -->
                            <div class="d-flex align-items-center py-0 mb-3 gap-5">
                                <div class="w-50 my-0">
                                    <input type="text" id="user-search-input" class="form-control"
                                        placeholder="Rechercher des employés...">
                                </div>
                                <div class="w-25 btn-group my-0">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Annuler</button>
                                    <button type="submit" class="btn btn-primary">Confirmer</button>
                                </div>
                            </div>
                            <div id="users-list" class="custom-users-list">
                                <h4 class="text-center"> <u> les Employé</u> ({{ $allUsers->count() }}) </h4>
                                @foreach ($allUsers as $user)
                                    <div class="form-check user-item">
                                        <label class="form-check-label containerCheck d-flex align-items-center"
                                            for="user-{{ $user->id }}">
                                            <input class="form-check-input me-2" type="checkbox" name="user_ids[]"
                                                value="{{ $user->id }}" id="user-{{ $user->id }}">
                                            <span class="user-name">{{ $user->nom . ' ' . $user->prénom }}</span>
                                            <div class="checkmark ms-auto"></div>
                                        </label>
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- teams modal --}}
    {{-- <div class="modal fade" id="UserEquipeModal" tabindex="-1" aria-labelledby="UserEquipeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="manage-team-form" action="" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title" id="UserEquipeModalLabel">Gérer équipes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="team-list-view" class="container mt-4">
                            <h2>Dashboard - Gérer équipes</h2>
                            <div class="row mb-3">
                                <button type="button" class="btn btn-success" onclick="showCreateTeamView()">Créer
                                    Nouvelle Équipe</button>
                            </div>
                            <div class="row">
                                @foreach ($equipesUsers as $equipe)
                                    <div class="col-md-4">
                                        <div class="card mb-4">
                                            <div
                                                class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <h5>{{ $equipe->nom_equipe }}</h5>
                                                <small>
                                                    @if ($equipe->users->isNotEmpty())
                                                        {{ $equipe->users->first()->shift ? $equipe->users->first()->shift->group . ' ' . $equipe->users->first()->shift->name : 'Pas de shift !' }}
                                                    @else
                                                        Aucun utilisateur!!
                                                    @endif
                                                </small>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="deleteTeam({{ $equipe->id }})"><i class="fa fa-trash"
                                                        aria-hidden="true"></i></button>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Membres de l'équipe:</strong></p>
                                                <ul>
                                                    @foreach ($equipe->users as $user)
                                                        <li>{{ $user->nom . ' ' . $user->prénom }}</li>
                                                    @endforeach
                                                </ul>
                                                <button type="button" class="btn btn-warning"
                                                    onclick="showManageTeamView({{ $equipe->id }}, '{{ $equipe->nom_equipe }}')">Gérer
                                                    cette équipe</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="manage-team-view" class="d-none">
                            <div class="form-group my-4">
                                <label for="team-name">Renommer équipe:</label>
                                <input type="text" id="team-name" class="form-control" name="team_name"
                                    placeholder="Nom de l'équipe">
                            </div>

                            <div class="row">
                                <!-- Selected Users Badges -->
                                <div class="form-group col-6">
                                    <label for="add-user-team">Ajouter utilisateurs à l'équipe:</label>
                                    <select id="add-user-team" class="form-control" name="add_user_team[]" multiple
                                        size="5">
                                        @foreach ($allUsers as $user)
                                            <option value="{{ $user->id }}">{{ $user->nom . ' ' . $user->prénom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label>Les utilisateur a ajouter: </label>
                                    <div id="selected-users" class="mt-2">

                                        <!-- Badges will be added here dynamically -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="remove-user-team">Retirer utilisateur de l'équipe:</label>
                                <select id="remove-user-team" class="form-control" name="remove_user_team">
                                    @foreach ($equipesUsers as $equipe)
                                        <optgroup label="{{ $equipe->nom_equipe }}">
                                            @foreach ($equipe->users as $user)
                                                <option value="{{ $user->id }}" data-team-id="{{ $equipe->id }}">
                                                    {{ $user->nom . ' ' . $user->prénom }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-end w-100">
                                    <div class="w-100">
                                        <label for="move-user-team">Déplacer utilisateur:</label>
                                        <select id="move-user-team" class="form-control" name="move_user_team">
                                            @foreach ($equipesUsers as $equipe)
                                                <optgroup label="{{ $equipe->nom_equipe }}">
                                                    @foreach ($equipe->users as $user)
                                                        <option value="{{ $user->id }}"
                                                            data-team-id="{{ $equipe->id }}">
                                                            {{ $user->nom . ' ' . $user->prénom }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                    </div>
                                    <i class="fa fa-arrow-circle-right fa-2x text-warning mx-4 mb-1"
                                        aria-hidden="true"></i>
                                    <div class="w-100">
                                        <label for="move-to-team">à ce équipe:</label>
                                        <select id="move-to-team" class="form-control" name="move_to_team">
                                            @foreach ($equipesUsers as $equipe)
                                                <option value="{{ $equipe->id }}">{{ $equipe->nom_equipe }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="create-team-view" class="d-none">
                            <div class="form-group my-4">
                                <label for="new-team-name">Nom de la nouvelle équipe:</label>
                                <input type="text" id="new-team-name" class="form-control" name="new_team_name"
                                    placeholder="Nom de la nouvelle équipe">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary d-none" id="confirm-button">Confirmer</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-secondary d-none" id="back-button"
                            onclick="showTeamListView()">Retour</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const moveToTeamDropdown = document.getElementById('move-to-team');
        const allMoveUserOptions = document.querySelectorAll('#move-user-team option');
        const allRemoveUserOptions = document.querySelectorAll('#remove-user-team option');


        function updateDropdownOptions(teamId, options) {
            options.forEach(option => {
                if (option.getAttribute('data-team-id') == teamId) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            });
        }


        function showManageTeamView(teamId, teamName) {
            document.getElementById('team-name').value = teamName;
            document.getElementById('manage-team-form').action = `/manage-teams/${teamId}`;
            document.getElementById('manage-team-form').method = 'POST';
            document.querySelector('input[name="_method"]').value = 'PUT';

            // Reset selected values to null
            document.getElementById('move-to-team').value = ''; // Reset to no selection
            document.getElementById('add-user-team').value = ''; // Reset to no selection
            document.getElementById('remove-user-team').value = ''; // Reset to no selection
            document.getElementById('move-user-team').selectedIndex = -1; // Reset "Déplacer utilisateur" to no selection



            updateDropdownOptions(teamId, allMoveUserOptions);
            updateDropdownOptions(teamId, allRemoveUserOptions);


            // Set the selected option for moving to a new team
            moveToTeamDropdown.value = teamId;

            document.getElementById('team-list-view').classList.add('d-none');
            document.getElementById('manage-team-view').classList.remove('d-none');
            document.getElementById('confirm-button').classList.remove('d-none');
            document.getElementById('back-button').classList.remove('d-none');
        }

        function showCreateTeamView() {
            document.getElementById('manage-team-form').action = '/create-team';
            document.getElementById('manage-team-form').method = 'POST';
            document.querySelector('input[name="_method"]').value = 'POST';

            document.getElementById('team-list-view').classList.add('d-none');
            document.getElementById('create-team-view').classList.remove('d-none');
            document.getElementById('confirm-button').classList.remove('d-none');
            document.getElementById('back-button').classList.remove('d-none');
        }

        function showTeamListView() {
            document.getElementById('team-list-view').classList.remove('d-none');
            document.getElementById('manage-team-view').classList.add('d-none');
            document.getElementById('create-team-view').classList.add('d-none');
            document.getElementById('confirm-button').classList.add('d-none');
            document.getElementById('back-button').classList.add('d-none');
        }

        function deleteTeam(teamId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette équipe?')) {
                fetch(`/delete-team/${teamId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                }).then(response => {
                    location.reload();
                });
            }
        }
    </script> --}}




    <!-- Include TableExport library -->
    <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>


@endsection
