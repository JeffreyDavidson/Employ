<div class="form-group">
    <label>Name:</label>
    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" placeholder="Enter name" value="{{ $company->name ?? old('name') }}">
    @error('name')
        <div id="name-error" class="error invalid-feedback">{{ $errors->first('name') }}</div>
    @enderror
</div>
<div class="form-group">
    <label>Email:</label>
    <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" placeholder="Enter email" value="{{ $company->email ?? old('email') }}">
    @error('email')
        <div id="email-error" class="error invalid-feedback">{{ $errors->first('email') }}</div>
    @enderror
</div>
<div class="form-group">
    <label>Website:</label>
    <input type="text" class="form-control {{ $errors->has('website') ? 'is-invalid' : '' }}" name="website" placeholder="Enter website" value="{{ $company->website ?? old('website') }}">
    @error('website')
        <div id="website-error" class="error invalid-feedback">{{ $errors->first('website') }}</div>
    @enderror
</div>
<div class="form-group">
    <label>Logo:</label>
    <input type="file" class="form-control {{ $errors->has('logo') ? 'is-invalid' : '' }}" name="logo">
    @error('logo')
        <div id="logo-error" class="error invalid-feedback">{{ $errors->first('logo') }}</div>
    @enderror
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
</div>
