<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expense;
use App\Models\Project;
use App\Models\User;
use App\Models\Church;
use Illuminate\Support\Facades\Auth;

class TestExpenseSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:expense-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tester le système de dépenses avec et sans projet';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Test du système de dépenses...');
        
        try {
            // 1. Vérifier qu'il y a des utilisateurs et des églises
            $this->checkPrerequisites();
            
            // 2. Tester la création d'une dépense sans projet
            $this->testExpenseWithoutProject();
            
            // 3. Tester la création d'une dépense avec projet
            $this->testExpenseWithProject();
            
            // 4. Vérifier les relations
            $this->testRelations();
            
            $this->info('✅ Tous les tests sont passés !');
            
        } catch (\Exception $e) {
            $this->error('❌ Erreur lors des tests: ' . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    /**
     * Vérifier les prérequis
     */
    private function checkPrerequisites()
    {
        $this->info('📋 Vérification des prérequis...');
        
        $churchCount = Church::count();
        $userCount = User::count();
        $projectCount = Project::count();
        
        $this->info("   - Églises: $churchCount");
        $this->info("   - Utilisateurs: $userCount");
        $this->info("   - Projets: $projectCount");
        
        if ($churchCount === 0) {
            throw new \Exception('Aucune église trouvée');
        }
        
        if ($userCount === 0) {
            throw new \Exception('Aucun utilisateur trouvé');
        }
    }
    
    /**
     * Tester une dépense sans projet
     */
    private function testExpenseWithoutProject()
    {
        $this->info('💰 Test d\'une dépense sans projet...');
        
        $church = Church::first();
        $user = User::where('church_id', $church->id)->first();
        
        if (!$user) {
            throw new \Exception('Aucun utilisateur trouvé pour cette église');
        }
        
        $expense = Expense::create([
            'church_id' => $church->id,
            'project_id' => null, // Pas de projet
            'paid_at' => now(),
            'description' => 'Test dépense générale',
            'amount' => 50000,
            'payment_method' => 'cash',
            'reference' => null,
            'notes' => 'Test automatique',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        
        $this->info("   ✅ Dépense créée: ID {$expense->id}");
        $this->info("   - Montant: {$expense->amount} FCFA");
        $this->info("   - Projet: " . ($expense->project ? $expense->project->name : 'Aucun'));
        $this->info("   - Description: {$expense->description}");
        
        // Nettoyer
        $expense->delete();
        $this->info('   🗑️ Dépense de test supprimée');
    }
    
    /**
     * Tester une dépense avec projet
     */
    private function testExpenseWithProject()
    {
        $this->info('🏗️ Test d\'une dépense avec projet...');
        
        $church = Church::first();
        $user = User::where('church_id', $church->id)->first();
        
        // Créer un projet de test s'il n'y en a pas
        $project = Project::where('church_id', $church->id)->first();
        if (!$project) {
            $project = Project::create([
                'church_id' => $church->id,
                'name' => 'Projet Test',
                'description' => 'Projet de test automatique',
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'budget' => 1000000,
                'status' => 'active',
            ]);
            $this->info("   📁 Projet de test créé: {$project->name}");
        }
        
        $expense = Expense::create([
            'church_id' => $church->id,
            'project_id' => $project->id,
            'paid_at' => now(),
            'description' => 'Test dépense liée au projet',
            'amount' => 75000,
            'payment_method' => 'bank',
            'reference' => 'REF123456',
            'notes' => 'Test automatique avec projet',
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);
        
        $this->info("   ✅ Dépense créée: ID {$expense->id}");
        $this->info("   - Montant: {$expense->amount} FCFA");
        $this->info("   - Projet: " . ($expense->project ? $expense->project->name : 'Aucun'));
        $this->info("   - Description: {$expense->description}");
        
        // Nettoyer
        $expense->delete();
        if ($project->name === 'Projet Test') {
            $project->delete();
            $this->info('   🗑️ Projet de test supprimé');
        }
        $this->info('   🗑️ Dépense de test supprimée');
    }
    
    /**
     * Tester les relations
     */
    private function testRelations()
    {
        $this->info('🔗 Test des relations...');
        
        $church = Church::first();
        $project = Project::where('church_id', $church->id)->first();
        
        if ($project) {
            // Tester la relation Project -> Expenses
            $expenseCount = $project->expenses()->count();
            $this->info("   - Projet '{$project->name}' a $expenseCount dépense(s)");
            
            // Tester la relation Expense -> Project
            $expense = Expense::where('church_id', $church->id)->first();
            if ($expense) {
                $projectName = $expense->project ? $expense->project->name : 'Aucun';
                $this->info("   - Dépense ID {$expense->id} liée au projet: $projectName");
            }
        }
        
        // Tester les dépenses sans projet
        $expensesWithoutProject = Expense::where('church_id', $church->id)
            ->whereNull('project_id')
            ->count();
        $this->info("   - Dépenses sans projet: $expensesWithoutProject");
        
        // Tester les dépenses avec projet
        $expensesWithProject = Expense::where('church_id', $church->id)
            ->whereNotNull('project_id')
            ->count();
        $this->info("   - Dépenses avec projet: $expensesWithProject");
    }
}
