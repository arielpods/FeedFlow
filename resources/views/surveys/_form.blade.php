@csrf

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(isset($organizations) && $organizations->count())
<div class="mb-3">
    <label for="organization_id">Organisation</label>
    <select name="organization_id" id="organization_id" class="form-control @error('organization_id') is-invalid @enderror" required>
        <option value="">-- Choisir une organisation --</option>
        @foreach($organizations as $org)
            <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>
                {{ $org->name }}
            </option>
        @endforeach
    </select>
    @error('organization_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
</div>
@endif

<div class="mb-3">
    <label>Titre</label>
    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $survey->title ?? '') }}">
    @error('title') <span class="invalid-feedback">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label>Description</label>
    <textarea name="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $survey->description ?? '') }}</textarea>
    @error('description') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label>Date début</label>
    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', $survey->start_date ?? '') }}">
    @error('start_date') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label>Date fin</label>
    <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', $survey->end_date ?? '') }}">
    @error('end_date') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
</div>

<div class="mb-3">
    <label>Anonyme ?</label>
    <input type="checkbox" name="is_anonymous" value="1" {{ old('is_anonymous', $survey->is_anonymous ?? false) ? 'checked' : '' }}>
</div>

<button type="submit" class="btn btn-success">Valider</button>
