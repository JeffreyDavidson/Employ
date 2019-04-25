<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            <label>First Name:</label>
            <input type="text" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" name="first_name" placeholder="Enter first name" value="{{ $employee->first_name ?? old('first_name') }}" required>
            @error('first_name')
                <div id="first-name-error" class="error invalid-feedback">{{ $errors->first('first_name') }}</div>
            @enderror
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label>Last Name:</label>
            <input type="text" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" name="last_name" placeholder="Enter last name" value="{{ $employee->last_name ?? old('last_name') }}" required>
            @error('last_name')
                <div id="last-name-error" class="error invalid-feedback">{{ $errors->first('last_name') }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="form-group">
    <label>Email:</label>
    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" placeholder="Enter email" value="{{ $employee->email ?? old('email') }}">
    @error('email')
        <div id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
    @enderror
</div>
<div class="form-group">
    <label>Telephone:</label>
    <input type="text" class="form-control {{ $errors->has('telephone') ? 'is-invalid' : '' }}" name="telephone" placeholder="Enter telephone" value="{{ $employee->telephone ?? old('telephone') }}">
    @error('telephone')
        <div id="telephone-error" class="error invalid-feedback">{{ $errors->first('telephone') }}</div>
    @enderror
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
