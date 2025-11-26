@csrf

<div class="mb-3">
    <label for="first_name" class="form-label">First Name</label>
    <input type="text" class="form-control" id="first_name" name="first_name"
        value="{{ old('first_name', $employee->first_name ?? '') }}" required>
    @error('first_name')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="last_name" class="form-label">Last Name</label>
    <input type="text" class="form-control" id="last_name" name="last_name"
        value="{{ old('last_name', $employee->last_name ?? '') }}" required>
    @error('last_name')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email</label>
    <input type="email" class="form-control" id="email" name="email"
        value="{{ old('email', $employee->email ?? '') }}" required>
    <div id="emailStatus" class="small mt-1"></div>
    @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="department_id" class="form-label">Department</label>
    <select name="department_id" id="department_id" class="form-select" required>
        <option value="">-- Select Department --</option>
        @foreach($departments as $dept)
        <option value="{{ $dept->id }}"
            {{ old('department_id', $employee->department_id ?? '') == $dept->id ? 'selected' : '' }}>
            {{ $dept->name }}
        </option>
        @endforeach
    </select>
    @error('department_id')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label">Skills</label>
    <div id="skillsWrapper">
        @php
        $selectedSkills = old('skills', isset($employee) ? $employee->skills->pluck('id')->toArray() : []);
        if (empty($selectedSkills)) {
        $selectedSkills = [null]; // at least one row
        }
        @endphp

        @foreach($selectedSkills as $index => $skillId)
        <div class="input-group mb-2 skill-row">
            <select name="skills[]" class="form-select">
                <option value="">-- Select Skill --</option>
                @foreach($skills as $skill)
                <option value="{{ $skill->id }}"
                    {{ (int)$skillId === $skill->id ? 'selected' : '' }}>
                    {{ $skill->name }}
                </option>
                @endforeach
            </select>
            <button type="button" class="btn btn-outline-danger remove-skill">Remove</button>
        </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-outline-primary mt-2" id="addSkillBtn">Add Skill</button>
    @error('skills')<div class="text-danger small">{{ $message }}</div>@enderror
    @error('skills.*')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<button type="submit" class="btn btn-success">
    {{ $submitLabel ?? 'Save' }}
</button>

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(function() {
        // Dynamic skill rows
        $('#addSkillBtn').on('click', function() {
            const template = `
            <div class="input-group mb-2 skill-row">
                <select name="skills[]" class="form-select">
                    <option value="">-- Select Skill --</option>
                    @foreach($skills as $skill)
                        <option value="{{ $skill->id }}">{{ $skill->name }}</option>
                    @endforeach
                </select>
                <button type="button" class="btn btn-outline-danger remove-skill">Remove</button>
            </div>
        `;
            $('#skillsWrapper').append(template);
        });

        $('#skillsWrapper').on('click', '.remove-skill', function() {
            $(this).closest('.skill-row').remove();
        });

        let emailTimer;
        $('#email').on('keyup change', function() {
            clearTimeout(emailTimer);
            const email = $(this).val();
            const employeeId = "{{ $employee->id ?? '' }}";

            if (!email) {
                $('#emailStatus').text('');
                return;
            }

            emailTimer = setTimeout(function() {
                $.ajax({
                    url: "{{ route('employees.checkEmail') }}",
                    data: {
                        email,
                        employee_id: employeeId
                    },
                    success: function(res) {
                        if (res.exists) {
                            $('#emailStatus').text('This email is already in use.')
                                .removeClass('text-success').addClass('text-danger');
                        } else {
                            $('#emailStatus').text('This email is available.')
                                .removeClass('text-danger').addClass('text-success');
                        }
                    }
                });
            }, 500);
        });
    });
</script>
@endpush