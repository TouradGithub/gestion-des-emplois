@csrf

<div class="form-group">
    <label>Nom <span class="text-danger">*</span></label>
    <input type="text" name="nom" value="{{ old('nom', $niveau->nom ?? '') }}" class="form-control" required>
</div>

<div class="form-group">
    <label>Ordre</label>
    <input type="text" name="ordre" value="{{ old('ordre', $niveau->ordre ?? '') }}" class="form-control">
</div>

<div class="form-group">
    <label>Formation</label>
    <select name="formation_id" class="form-control">
        <option value="">Choisir...</option>
        @foreach($formations as $formation)
            <option value="{{ $formation->id }}" {{ old('formation_id', $niveau->formation_id ?? '') == $formation->id ? 'selected' : '' }}>
                {{ $formation->nom }}
            </option>
        @endforeach
    </select>
</div>

<button type="submit" class="btn btn-theme">Enregistrer</button>
