<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Church;
use App\Services\FirebaseStorageService;
use App\Services\ChurchLinkService;
use App\Services\ChurchIdEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->get('q'));
        $query = Member::where('church_id', Auth::user()->church_id);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $members = $query->latest()->paginate(12)->withQueryString();

        // Stats - filtrées par église
        $now = now();
        $churchId = Auth::user()->church_id;
        $stats = [
            'total' => Member::where('church_id', $churchId)->count(),
            'active' => Member::where('church_id', $churchId)->where('status','active')->count(),
            'inactive' => Member::where('church_id', $churchId)->where('status','inactive')->count(),
            'male' => Member::where('church_id', $churchId)->where('gender','male')->count(),
            'female' => Member::where('church_id', $churchId)->where('gender','female')->count(),
            'other' => Member::where('church_id', $churchId)->where('gender','other')->count(),
            'children' => Member::where('church_id', $churchId)->whereNotNull('birth_date')->where('birth_date','>', $now->copy()->subYears(18))->count(),
        ];

        return view('members.index', compact('members', 'search','stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'email' => ['nullable','email','max:150','unique:members,email'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:255'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'marital_status' => ['nullable', Rule::in(['single','married','divorced','widowed'])],
            'birth_date' => ['nullable','date'],
            'baptized_at' => ['nullable','date'],
            'baptism_responsible' => ['nullable','string','max:150'],
            'status' => ['required', Rule::in(['active','inactive'])],
            'joined_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        // Upload photo if provided
        if ($request->hasFile('profile_photo')) {
            $firebaseService = new FirebaseStorageService();
            $photoUrl = $firebaseService->uploadFileDirect($request->file('profile_photo'));
            
            if ($photoUrl) {
                // Upload réussi, stocker l'URL dans la BD
                $validated['photo_url'] = $photoUrl;
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
            } else {
                // En cas d'échec, stocker localement seulement
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
                $validated['photo_url'] = asset('storage/' . $validated['profile_photo']);
            }
        }

        // Ajouter l'ID de l'église
        $validated['church_id'] = Auth::user()->church_id;
        $validated['created_by'] = Auth::id();
        
        $member = Member::create($validated);
        return redirect()->route('members.index')->with('success', 'Membre enregistré.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        // Vérifier que le membre appartient à l'église de l'utilisateur
        if ($member->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $member->load('tithes');
        
        // Données pour le graphique des dîmes sur l'année
        $currentYear = now()->year;
        $driver = \Illuminate\Support\Facades\DB::getDriverName();
        
        // Expression SQL pour extraire le mois selon le driver de base de données
        $monthExpr = match ($driver) {
            'mysql', 'mariadb' => "DATE_FORMAT(paid_at, '%m')",
            'pgsql' => "to_char(paid_at, 'MM')",
            default => "strftime('%m', paid_at)",
        };
        
        // Récupérer les données des dîmes par mois pour l'année courante
        $monthlyTithes = \Illuminate\Support\Facades\DB::table('tithes')
            ->where('member_id', $member->id)
            ->whereYear('paid_at', $currentYear)
            ->selectRaw("{$monthExpr} as month, SUM(amount) as total")
            ->groupBy('month')
            ->pluck('total', 'month');
        
        // Créer les données du graphique pour les 12 mois
        $chartData = [];
        $labels = [];
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);
            $chartData[] = (float) ($monthlyTithes[$month] ?? 0);
            $labels[] = $month;
        }
        
        $chart = [
            'labels' => $labels,
            'data' => $chartData,
            'year' => $currentYear,
            'labels_numeric' => range(1, 12)
        ];
        
        return view('members.show', compact('member', 'chart'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        // Vérifier que le membre appartient à l'église de l'utilisateur
        if ($member->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        // Vérifier que le membre appartient à l'église de l'utilisateur
        if ($member->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $validated = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'email' => ['nullable','email','max:150', Rule::unique('members','email')->ignore($member->id)],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:255'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'marital_status' => ['nullable', Rule::in(['single','married','divorced','widowed'])],
            'birth_date' => ['nullable','date'],
            'baptized_at' => ['nullable','date'],
            'baptism_responsible' => ['nullable','string','max:150'],
            'status' => ['required', Rule::in(['active','inactive'])],
            'joined_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        // Upload photo if provided
        if ($request->hasFile('profile_photo')) {
            $firebaseService = new FirebaseStorageService();
            $photoUrl = $firebaseService->uploadFileDirect($request->file('profile_photo'));
            
            if ($photoUrl) {
                // Upload réussi, stocker l'URL dans la BD
                $validated['photo_url'] = $photoUrl;
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
            } else {
                // En cas d'échec, stocker localement seulement
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
                $validated['photo_url'] = asset('storage/' . $validated['profile_photo']);
            }
        }

        $validated['updated_by'] = Auth::id();
        $member->update($validated);
        return redirect()->route('members.index')->with('success', 'Mise à jour effectuée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // Vérifier que le membre appartient à l'église de l'utilisateur
        if ($member->church_id !== Auth::user()->church_id) {
            abort(403, 'Accès non autorisé');
        }
        
        $member->delete();
        return redirect()->route('members.index')->with('success', 'Membre supprimé.');
    }
    
    /**
     * Génère un lien unique pour l'inscription individuelle
     */
    public function generateRegistrationLink(Request $request)
    {
        $church = Church::find(Auth::user()->church_id);
        
        if (!$church) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Église non trouvée.'
                ]);
            }
            return redirect()->route('members.index')->with('error', 'Église non trouvée.');
        }
        
        // Utiliser le service de chiffrement simple
        $registrationLink = ChurchIdEncryptionService::generateRegistrationLink($church->id);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Lien généré avec succès',
                'registration_link' => $registrationLink
            ]);
        }
        
        return redirect()->route('members.index')->with([
            'success' => 'Lien copié dans le presse-papier !',
            'registration_link' => $registrationLink,
            'auto_copy' => true
        ]);
    }
    
    /**
     * Affiche le formulaire d'inscription via lien unique
     */
    public function showRegistrationForm(Request $request)
    {
        $token = $request->get('token');
        
        if (!$token) {
            abort(404, 'Lien invalide');
        }
        
        $linkService = new ChurchLinkService();
        $decryptedData = $linkService->decryptRegistrationLink($token);
        
        if (!$decryptedData) {
            abort(404, 'Lien invalide ou expiré');
        }
        
        $church = $decryptedData['church'];
        
        return view('members.register', compact('church', 'token'));
    }
    
    /**
     * Traite l'inscription via lien unique
     */
    public function processRegistration(Request $request)
    {
        $token = $request->get('token');
        
        if (!$token) {
            return redirect()->back()->with('error', 'Token manquant');
        }
        
        $linkService = new ChurchLinkService();
        $decryptedData = $linkService->decryptRegistrationLink($token);
        
        if (!$decryptedData) {
            return redirect()->back()->with('error', 'Lien invalide ou expiré');
        }
        
        $church = $decryptedData['church'];
        
        $validated = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'email' => ['nullable','email','max:150','unique:members,email'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:255'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'marital_status' => ['nullable', Rule::in(['single','married','divorced','widowed'])],
            'birth_date' => ['nullable','date'],
            'baptized_at' => ['nullable','date'],
            'baptism_responsible' => ['nullable','string','max:150'],
            'status' => ['required', Rule::in(['active','inactive'])],
            'joined_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        // Upload photo to Firebase if provided
        if ($request->hasFile('profile_photo')) {
            $firebaseService = new FirebaseStorageService();
            $photoUrl = $firebaseService->uploadFileDirect($request->file('profile_photo'));
            
            if ($photoUrl) {
                $validated['photo_url'] = $photoUrl;
            }
            
            $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
        }

        // Ajouter l'ID de l'église et les métadonnées
        $validated['church_id'] = $church->id;
        $validated['created_by'] = null; // Auto-inscription
        $validated['status'] = 'active'; // Actif par défaut pour les auto-inscriptions
        
        $member = Member::create($validated);
        
        return redirect()->route('members.register.success', ['church' => $church->slug])
            ->with('success', 'Inscription réussie ! Bienvenue dans l\'église ' . $church->name);
    }
    
    /**
     * Page de succès après inscription
     */
    public function registrationSuccess(Church $church)
    {
        return view('members.register-success', compact('church'));
    }
    
    /**
     * Génère un lien d'inscription sécurisé pour une église
     */
    public function generateSecureRegistrationLink(Church $church)
    {
        if (!$church->is_active) {
            return null;
        }
        
        return ChurchIdEncryptionService::generateRegistrationLink($church->id);
    }
    
    /**
     * Affiche le formulaire d'inscription publique avec l'ID de l'église chiffré
     */
    public function showPublicRegistrationForm($church_id)
    {
        try {
            $churchId = ChurchIdEncryptionService::validateAndDecrypt($church_id);
            $church = Church::findOrFail($churchId);
        } catch (\Exception $e) {
            abort(404, 'Lien d\'inscription invalide');
        }
        
        if (!$church->is_active) {
            abort(404, 'Cette église n\'est pas active');
        }
        
        return view('members.public-create', compact('church', 'church_id'));
    }
    
    /**
     * Traite l'inscription publique avec l'ID de l'église chiffré
     */
    public function processPublicRegistration(Request $request, $church_id)
    {
        try {
            $churchId = ChurchIdEncryptionService::validateAndDecrypt($church_id);
            $church = Church::findOrFail($churchId);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Lien d\'inscription invalide');
        }
        
        if (!$church->is_active) {
            return redirect()->back()->with('error', 'Cette église n\'est pas active');
        }
        
        $validated = $request->validate([
            'first_name' => ['required','string','max:100'],
            'last_name' => ['required','string','max:100'],
            'email' => ['nullable','email','max:150','unique:members,email'],
            'phone' => ['nullable','string','max:50'],
            'address' => ['nullable','string','max:255'],
            'gender' => ['nullable', Rule::in(['male','female','other'])],
            'marital_status' => ['nullable', Rule::in(['single','married','divorced','widowed'])],
            'birth_date' => ['nullable','date'],
            'baptized_at' => ['nullable','date'],
            'baptism_responsible' => ['nullable','string','max:150'],
            'joined_at' => ['nullable','date'],
            'notes' => ['nullable','string'],
            'profile_photo' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:4096'],
        ]);

        // Upload photo if provided
        if ($request->hasFile('profile_photo')) {
            $firebaseService = new FirebaseStorageService();
            $photoUrl = $firebaseService->uploadFileDirect($request->file('profile_photo'));
            
            if ($photoUrl) {
                // Upload réussi, stocker l'URL dans la BD
                $validated['photo_url'] = $photoUrl;
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
            } else {
                // En cas d'échec, stocker localement seulement
                $validated['profile_photo'] = $request->file('profile_photo')->store('members', 'public');
                $validated['photo_url'] = asset('storage/' . $validated['profile_photo']);
            }
        }

        // Ajouter l'ID de l'église et les métadonnées
        $validated['church_id'] = $church->id;
        $validated['created_by'] = null; // Auto-inscription
        $validated['status'] = 'active'; // Actif par défaut pour les auto-inscriptions
        
        $member = Member::create($validated);
        
        return redirect()->route('members.public.success', ['church' => $church->slug])
            ->with('success', 'Inscription réussie ! Bienvenue dans l\'église ' . $church->name);
    }
    
    /**
     * Page de succès après inscription publique
     */
    public function publicRegistrationSuccess(Church $church)
    {
        return view('members.public-success', compact('church'));
    }
}
