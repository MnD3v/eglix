{{-- resources/views/journal/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Nouvelle entrée</h1>
    <a href="{{ route('journal.index') }}" class="btn btn-outline-secondary">Retour</a>
  </div>

  <form id="journalForm" action="{{ route('journal.store') }}" method="POST" class="card card-soft p-3" style="position:relative;">
    @csrf
    <div class="row g-3">
      <div class="col-12 col-md-8">
        <label class="form-label">Titre</label>
        <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
      </div>

      <div class="col-12 col-md-4">
        <label class="form-label">Catégorie</label>
        <select name="category" class="form-select" required>
          @foreach($categories as $key => $label)
            <option value="{{ $key }}" @selected(old('category')===$key)>{{ $label }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-4">
        <label class="form-label">Date</label>
        <input type="date" name="occurred_at" class="form-control" required value="{{ old('occurred_at', now()->format('Y-m-d')) }}">
      </div>

      <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" rows="6" class="form-control" placeholder="Détails...">{{ old('description') }}</textarea>
      </div>

      <div class="col-12">
        <label class="form-label">Images</label>
        <input id="imagesInput" type="file" accept="image/*" multiple class="form-control">
        <div class="form-text">Les fichiers sont envoyés vers Firebase Storage ; seules les URLs sont stockées en base.</div>

        <div id="uploadProgress" class="small text-muted mt-2"></div>
        <div class="progress mt-2 d-none" id="uploadBarWrap" style="height:8px;">
          <div class="progress-bar bg-primary" id="uploadBar" style="width:0%"></div>
        </div>
      </div>
    </div>

    <div class="mt-3 d-flex gap-2">
      <button class="btn btn-primary" id="submitBtn">Enregistrer</button>
      <a class="btn btn-outline-secondary" href="{{ route('journal.index') }}">Annuler</a>
    </div>

    <!-- Overlay loader -->
    <div id="uploadOverlay" class="d-none" style="position:absolute; inset:0; background:rgba(255,255,255,.7); display:flex; align-items:center; justify-content:center; border-radius:12px;">
      <div class="text-center">
        <div class="spinner-border text-primary" role="status" aria-hidden="true"></div>
        <div class="mt-2 small text-muted">Téléversement en cours...</div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
{{-- Firebase v9 (modular) --}}
<script type="module">
import { initializeApp } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-app.js";
import { getStorage, ref, uploadBytesResumable, getDownloadURL } from "https://www.gstatic.com/firebasejs/9.23.0/firebase-storage.js";

// ⚠️ Renseigne ta config Firebase
const firebaseConfig = {
  apiKey: "xxxx",
  authDomain: "xxxx.firebaseapp.com",
  projectId: "xxxx",
  storageBucket: "xxxx.appspot.com",
  messagingSenderId: "xxxx",
  appId: "xxxx"
};

const app = initializeApp(firebaseConfig);
const storage = getStorage(app);

document.addEventListener('DOMContentLoaded', () => {
  const input     = document.getElementById('imagesInput');
  const form      = document.getElementById('journalForm');
  const progress  = document.getElementById('uploadProgress');
  const overlay   = document.getElementById('uploadOverlay');
  const barWrap   = document.getElementById('uploadBarWrap');
  const bar       = document.getElementById('uploadBar');
  const submitBtn = document.getElementById('submitBtn');

  form.addEventListener('submit', async (e) => {
    // Si aucun fichier, on poste directement
    if (!input.files || input.files.length === 0) return;

    e.preventDefault();
    const files = Array.from(input.files);
    const urls  = [];

    // UI: état chargement
    overlay.classList.remove('d-none');
    barWrap.classList.remove('d-none');
    submitBtn.disabled = true;
    progress.textContent = 'Préparation des fichiers...';

    // Calcul progress global = somme des bytes transférés / somme des bytes totaux
    const totalBytes = files.reduce((sum, f) => sum + f.size, 0);
    let transferred  = 0;

    try {
      for (let i = 0; i < files.length; i++) {
        const file   = files[i];
        const safe   = file.name.replace(/[^a-zA-Z0-9_.-]/g, '_');
        const path   = `journal/${Date.now()}_${i}_${safe}`;
        const storageRef = ref(storage, path);
        const task = uploadBytesResumable(storageRef, file);

        await new Promise((resolve, reject) => {
          task.on('state_changed', (snap) => {
            // met à jour la barre de progression globale
            const delta = snap.bytesTransferred - (task._lastTransferred || 0);
            task._lastTransferred = snap.bytesTransferred;
            transferred += Math.max(0, delta);
            const pct = Math.round((transferred / totalBytes) * 100);
            bar.style.width = pct + '%';
            progress.textContent = `Téléversement ${pct}% (${i+1}/${files.length})`;
          }, reject, async () => {
            const url = await getDownloadURL(task.snapshot.ref);
            urls.push(url);
            resolve();
          });
        });
      }

      // injecte les URL dans le formulaire
      urls.forEach((url) => {
        const hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.name = 'image_urls[]';
        hidden.value = url;
        form.appendChild(hidden);
      });

      // Nettoie l'input file (on ne veut pas envoyer les binaires au serveur)
      input.value = '';

      // Envoie final vers Laravel
      form.submit();
    } catch (err) {
      console.error(err);
      progress.textContent = 'Erreur de téléversement : ' + (err?.message || err);
      overlay.classList.add('d-none');
      submitBtn.disabled = false;
    }
  });
});
</script>
@endpush
