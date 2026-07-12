<?php

namespace Database\Seeders;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\FileAttente;
use App\Models\Ordonnance;
use App\Models\OrdonnanceMedicament;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Fills all seven clinic modules with plausible, internally-consistent sample
 * data so a fresh `migrate --seed` doesn't show blank screens everywhere.
 * Only runs when the tables are still empty, so it's safe to re-run.
 */
class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (StaffMedical::count() > 0) {
            return;
        }

        $staff = $this->seedStaff();
        $doctors = $staff->whereIn('role', ['medecin', 'infirmier'])->values();

        $patients = $this->seedPatients($doctors);

        // Tracks (staff_id => [date => [heure, ...]]) to avoid double-booking a doctor.
        $bookedSlots = [];
        $appointments = $this->seedRendezVous($patients, $doctors, $bookedSlots);

        $this->seedFileAttente($patients, $doctors, $appointments);

        $consultations = $this->seedConsultations($patients, $doctors);

        $this->seedOrdonnances($consultations);

        $this->seedFactures($patients, $staff);
    }

    private function seedStaff()
    {
        $doctorSpecs = [
            ['nom' => 'Bennani', 'prenom' => 'Youssef', 'specialite' => 'Cardiologie'],
            ['nom' => 'El Amrani', 'prenom' => 'Salma', 'specialite' => 'Pédiatrie'],
            ['nom' => 'Tazi', 'prenom' => 'Karim', 'specialite' => 'Médecine Générale'],
        ];

        foreach ($doctorSpecs as $spec) {
            StaffMedical::factory()->create($spec + ['role' => 'medecin']);
        }

        StaffMedical::factory()->create([
            'nom' => 'Idrissi', 'prenom' => 'Nadia', 'role' => 'infirmier', 'specialite' => null,
        ]);
        StaffMedical::factory()->create([
            'nom' => 'Fassi', 'prenom' => 'Amine', 'role' => 'secretaire', 'specialite' => null,
        ]);
        StaffMedical::factory()->create([
            'nom' => 'Chraibi', 'prenom' => 'Imane', 'role' => 'secretaire', 'specialite' => null,
        ]);
        StaffMedical::factory()->create([
            'nom' => 'Ouazzani', 'prenom' => 'Hamid', 'role' => 'technicien', 'specialite' => null,
        ]);

        return StaffMedical::all();
    }

    private function seedPatients($doctors)
    {
        $bloodTypes = ['O+', 'O-', 'A+', 'A-', 'B+', 'AB+'];
        $allergies = [null, null, 'Pénicilline', 'Arachides', 'Aspirine', null];

        $patients = collect();

        for ($i = 0; $i < 18; $i++) {
            $patients->push(Patient::factory()->create([
                'medecin_id' => $doctors->random()->id,
                'email' => fake()->unique()->safeEmail(),
                'groupe_sanguin' => fake()->randomElement($bloodTypes),
                'allergies' => fake()->randomElement($allergies),
                'antecedents' => fake()->boolean(40) ? fake()->sentence(6) : null,
                'assurance_type' => fake()->randomElement(['CNSS', 'CNOPS', 'Privée', null]),
                'assurance_numero' => fake()->boolean(60) ? fake()->numerify('ASS-######') : null,
                'contact_urgence_nom' => fake()->name(),
                'contact_urgence_tel' => fake()->numerify('06########'),
                'lien_urgence' => fake()->randomElement(['Conjoint(e)', 'Parent', 'Enfant', 'Ami(e)']),
                'color' => fake()->randomElement(['teal', 'blue', 'purple', 'amber', 'rose']),
            ]));
        }

        return $patients;
    }

    private function seedRendezVous($patients, $doctors, array &$bookedSlots)
    {
        $motifs = ['Consultation de routine', 'Douleurs abdominales', 'Suivi tension artérielle', 'Bilan annuel', 'Fièvre persistante', 'Contrôle post-opératoire'];
        $times = ['09:00', '09:30', '10:00', '10:30', '11:00', '14:00', '14:30', '15:00', '15:30', '16:00'];

        $pickSlot = function (Carbon $date) use (&$bookedSlots, $doctors, $times) {
            $staff = $doctors->random();
            $dateKey = $date->toDateString();

            $available = array_diff($times, $bookedSlots[$staff->id][$dateKey] ?? []);
            if (empty($available)) {
                $available = $times; // ran out of unique slots for this doctor/day, accept a stale one
            }
            $heure = fake()->randomElement($available);

            $bookedSlots[$staff->id][$dateKey][] = $heure;

            return [$staff, $heure];
        };

        $appointments = collect();

        // Future appointments (still "programme")
        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::today()->addDays(fake()->numberBetween(1, 15));
            [$staff, $heure] = $pickSlot($date);

            $appointments->push(RendezVous::create([
                'date' => $date->toDateString(),
                'heure' => $heure,
                'statut' => 'programme',
                'motif' => fake()->randomElement($motifs),
                'type_consultation' => 'standard',
                'duree' => 30,
                'priorite' => fake()->randomElement(['normale', 'normale', 'normale', 'haute']),
                'patient_id' => $patients->random()->id,
                'staff_id' => $staff->id,
            ]));
        }

        // Past appointments, closed out
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::today()->subDays(fake()->numberBetween(1, 30));
            [$staff, $heure] = $pickSlot($date);

            $appointments->push(RendezVous::create([
                'date' => $date->toDateString(),
                'heure' => $heure,
                'statut' => 'termine',
                'motif' => fake()->randomElement($motifs),
                'type_consultation' => fake()->randomElement(['standard', 'suivi']),
                'duree' => 30,
                'priorite' => 'normale',
                'patient_id' => $patients->random()->id,
                'staff_id' => $staff->id,
            ]));
        }

        // A cancelled one
        $date = Carbon::today()->addDays(3);
        [$staff, $heure] = $pickSlot($date);
        $appointments->push(RendezVous::create([
            'date' => $date->toDateString(),
            'heure' => $heure,
            'statut' => 'annule',
            'motif' => fake()->randomElement($motifs),
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
            'patient_id' => $patients->random()->id,
            'staff_id' => $staff->id,
        ]));

        // Today, already booked for later this afternoon — used below to demo check-in.
        $confirmedToday = RendezVous::create([
            'date' => Carbon::today()->toDateString(),
            'heure' => '16:30',
            'statut' => 'programme',
            'motif' => 'Consultation de suivi',
            'type_consultation' => 'suivi',
            'duree' => 30,
            'priorite' => 'normale',
            'patient_id' => $patients->random()->id,
            'staff_id' => $doctors->random()->id,
        ]);
        $appointments->put('confirmedToday', $confirmedToday);

        // Today, scheduled earlier this morning and never checked in — demos the
        // "patients non présentés" no-show detection on the File d'attente page.
        $noShowToday = RendezVous::create([
            'date' => Carbon::today()->toDateString(),
            'heure' => '08:30',
            'statut' => 'programme',
            'motif' => 'Consultation de routine',
            'type_consultation' => 'standard',
            'duree' => 30,
            'priorite' => 'normale',
            'patient_id' => $patients->random()->id,
            'staff_id' => $doctors->random()->id,
        ]);
        $appointments->put('noShowToday', $noShowToday);

        return $appointments;
    }

    private function seedFileAttente($patients, $doctors, $appointments)
    {
        $motifs = ['Douleur thoracique légère', 'Toux persistante', 'Renouvellement ordonnance', 'Contrôle de routine', 'Maux de tête'];

        $entries = [
            ['statut' => 'termine', 'priorite' => 'normale', 'minutesAgo' => 90],
            ['statut' => 'termine', 'priorite' => 'haute', 'minutesAgo' => 60],
            ['statut' => 'en_cours', 'priorite' => 'normale', 'minutesAgo' => 25],
            ['statut' => 'en_attente', 'priorite' => 'urgente', 'minutesAgo' => 10],
            ['statut' => 'en_attente', 'priorite' => 'normale', 'minutesAgo' => 5],
        ];

        foreach ($entries as $position => $entry) {
            FileAttente::create([
                'arrived_at' => Carbon::now()->subMinutes($entry['minutesAgo']),
                'position' => $position + 1,
                'statut' => $entry['statut'],
                'priorite' => $entry['priorite'],
                'type_visite' => 'sans_rdv',
                'motif' => fake()->randomElement($motifs),
                'patient_id' => $patients->random()->id,
                'staff_id' => $doctors->random()->id,
            ]);
        }

        // Check the "confirmedToday" appointment in, mirroring what
        // FileAttenteController::store() does when linking a rendez-vous.
        $confirmedToday = $appointments->get('confirmedToday');
        FileAttente::create([
            'arrived_at' => Carbon::now()->subMinutes(2),
            'position' => count($entries) + 1,
            'statut' => 'en_attente',
            'priorite' => 'normale',
            'type_visite' => 'avec_rdv',
            'motif' => $confirmedToday->motif,
            'rendez_vous_id' => $confirmedToday->id,
            'patient_id' => $confirmedToday->patient_id,
            'staff_id' => $confirmedToday->staff_id,
        ]);
        $confirmedToday->update(['statut' => 'confirme']);
    }

    private function seedConsultations($patients, $doctors)
    {
        $motifs = ['Consultation de routine', 'Douleur persistante', 'Suivi traitement', 'Bilan de santé', 'Contrôle tension'];
        $diagnostics = ['Rhinopharyngite aiguë', 'Hypertension légère stabilisée', 'Gastrite', 'Lombalgie mécanique', 'Bilan normal, RAS'];
        $traitements = ['Paracétamol 1g x3/j pendant 5 jours', 'Poursuite du traitement actuel', 'Repos et hydratation', 'Anti-inflammatoire local', 'Aucun traitement nécessaire'];

        $consultations = collect();

        for ($i = 0; $i < 15; $i++) {
            $wantsOrdonnance = $i < 8; // first 8 will get a linked prescription

            $consultations->push(Consultation::create([
                'date_consultation' => Carbon::now()->subDays(fake()->numberBetween(1, 45)),
                'motif' => fake()->randomElement($motifs),
                'type_consultation' => fake()->randomElement(['standard', 'suivi']),
                'tension_systolique' => fake()->numberBetween(105, 140),
                'tension_diastolique' => fake()->numberBetween(65, 90),
                'frequence_cardiaque' => fake()->numberBetween(60, 100),
                'temperature' => fake()->randomFloat(1, 36.2, 38.3),
                'spo2' => fake()->numberBetween(95, 100),
                'poids' => fake()->randomFloat(1, 50, 95),
                'taille' => fake()->numberBetween(150, 190),
                'diagnostic' => fake()->randomElement($diagnostics),
                'traitement' => fake()->randomElement($traitements),
                'notes' => fake()->boolean(50) ? fake()->sentence(8) : null,
                'ordonnance_demandee' => $wantsOrdonnance,
                'scanner_demande' => fake()->boolean(10),
                'analyse_demandee' => fake()->boolean(15),
                'kine_demandee' => fake()->boolean(10),
                'patient_id' => $patients->random()->id,
                'staff_id' => $doctors->random()->id,
            ]));
        }

        return $consultations;
    }

    private function seedOrdonnances($consultations)
    {
        $medicaments = [
            ['nom' => 'Paracétamol 1000mg', 'posologie' => '1 comprimé 3x/jour', 'duree' => '5 jours'],
            ['nom' => 'Amoxicilline 500mg', 'posologie' => '1 gélule 2x/jour', 'duree' => '7 jours'],
            ['nom' => 'Ibuprofène 400mg', 'posologie' => '1 comprimé si douleur', 'duree' => '5 jours'],
            ['nom' => 'Oméprazole 20mg', 'posologie' => '1 gélule le matin à jeun', 'duree' => '14 jours'],
            ['nom' => 'Cétirizine 10mg', 'posologie' => '1 comprimé le soir', 'duree' => '10 jours'],
        ];

        $eligible = $consultations->filter(fn(Consultation $c) => $c->ordonnance_demandee)->values();

        foreach ($eligible as $consultation) {
            $lines = fake()->randomElements($medicaments, fake()->numberBetween(1, 3));

            $ordonnance = Ordonnance::create([
                'consultation_id' => $consultation->id,
                'patient_id' => $consultation->patient_id,
                'staff_id' => $consultation->staff_id,
                'date_prescription' => $consultation->date_consultation->toDateString(),
                'duree_validite' => '30 jours',
                'diagnostic_associe' => $consultation->diagnostic,
                'instructions' => 'À prendre pendant ou après les repas.',
                'renouvelable' => fake()->numberBetween(0, 2),
                'substitution_autorisee' => fake()->boolean(70),
                'contenu' => collect($lines)->map(fn($m) => "{$m['nom']} — {$m['posologie']}, {$m['duree']}")->implode("\n"),
            ]);

            foreach ($lines as $line) {
                OrdonnanceMedicament::create([
                    'ordonnance_id' => $ordonnance->id,
                    'nom' => $line['nom'],
                    'posologie' => $line['posologie'],
                    'duree' => $line['duree'],
                ]);
            }
        }
    }

    private function seedFactures($patients, $staff)
    {
        $services = [
            ['designation' => 'Consultation générale', 'prix' => 200],
            ['designation' => 'Consultation spécialisée', 'prix' => 400],
            ['designation' => 'Analyse sanguine', 'prix' => 150],
            ['designation' => 'Radiographie', 'prix' => 300],
            ['designation' => 'Échographie', 'prix' => 350],
            ['designation' => 'Vaccination', 'prix' => 120],
            ['designation' => 'Pansement / soins', 'prix' => 80],
        ];

        $secretaries = $staff->where('role', 'secretaire')->values();
        $year = (int) date('Y');

        for ($i = 1; $i <= 10; $i++) {
            $lines = fake()->randomElements($services, fake()->numberBetween(1, 3));
            $sousTotal = 0;
            foreach ($lines as &$line) {
                $line['qty'] = fake()->numberBetween(1, 2);
                $sousTotal += $line['qty'] * $line['prix'];
            }
            unset($line);

            $remise = fake()->randomElement([0, 0, 10, 20]);
            $tva = fake()->randomElement([0, 20]);
            $totalTtc = round($sousTotal * (1 - $remise / 100) * (1 + $tva / 100), 2);

            $isPaid = $i <= 6; // 6 paid, 4 unpaid (some of those overdue)
            $billedAt = Carbon::now()->subDays(fake()->numberBetween(1, 40));
            $dueAt = $isPaid ? $billedAt->copy()->addDays(15) : Carbon::now()->addDays(fake()->numberBetween(-10, 15));

            $facture = Facture::create([
                'numero' => sprintf('FAC-%d-%04d', $year, $i),
                'patient_id' => $patients->random()->id,
                'staff_id' => $secretaries->isNotEmpty() ? $secretaries->random()->id : null,
                'date_facturation' => $billedAt->toDateString(),
                'date_echeance' => $dueAt->toDateString(),
                'mode_paiement' => fake()->randomElement(['especes', 'carte', 'cheque', 'virement', 'assurance']),
                'statut' => $isPaid ? 'paye' : 'en_attente',
                'paid_at' => $isPaid ? $billedAt->copy()->addDays(fake()->numberBetween(0, 5)) : null,
                'remise' => $remise,
                'tva' => $tva,
                'sous_total' => $sousTotal,
                'total_ttc' => $totalTtc,
            ]);

            foreach ($lines as $line) {
                FactureLigne::create([
                    'facture_id' => $facture->id,
                    'designation' => $line['designation'],
                    'quantite' => $line['qty'],
                    'prix_unitaire' => $line['prix'],
                ]);
            }
        }
    }
}
