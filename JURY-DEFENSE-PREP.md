# Notes de préparation — Défense PFE

Document de travail personnel résumant les améliorations apportées à l'application pendant cette session, organisé pour expliquer le travail à un jury. Pas destiné à être un livrable — c'est un aide-mémoire.

---

## En une phrase

L'application avait une base fonctionnelle correcte (gestion complète d'une clinique : patients, personnel, file d'attente, rendez-vous, consultations, ordonnances, facturation) mais deux failles structurelles majeures — un tableau de bord qui affichait des données factices comme si elles étaient réelles, et une absence totale de tests automatisés — masquaient plusieurs bugs réels qui auraient été visibles en production. Ce travail a corrigé les deux failles structurelles et, en écrivant des tests systématiquement, a révélé et corrigé 7 bugs réels, dont un problème critique d'intégrité des données médicales.

---

## Chronologie du travail (3 phases)

### Phase 1 — Le tableau de bord mentait

**Constat initial :** `DashboardController` ne passait que `$staff` à la vue. Le reste (revenus, nouveaux patients, file d'attente, activité récente, graphiques) était câblé en dur à zéro, avec des commentaires du type *"replace with real data if needed"*. Visuellement crédible, mais entièrement faux — pire qu'un état vide, parce que ça ressemble à des vraies données.

**Correction :**
- Revenus du mois (+ tendance vs mois précédent) calculés depuis `Facture`
- Compteur de patients en file d'attente en temps réel
- Graphique d'activité sur 12 mois (consultations, nouveaux patients, rendez-vous) agrégé depuis la base
- Donut de répartition par spécialité, calculé par jointure `Consultation` ⋈ `StaffMedical`
- Flux d'activité récente réutilisant le système de notifications déjà existant (`ClinicNotification`) mais jamais branché au dashboard
- Aperçu file d'attente et prochains rendez-vous connectés aux vraies requêtes
- **Bonus trouvé en chemin :** les badges de statut comparaient des libellés accentués (`'confirmé'`, `'terminé'`) à des valeurs d'énumération réelles sans accent (`confirme`, `termine`) → les badges retombaient silencieusement sur "gris/inconnu" pour tous les rendez-vous confirmés/terminés. Corrigé.

### Phase 2 — Construction de l'infrastructure de tests (0 → 39 tests)

**Constat initial :** `tests/` = squelette Laravel par défaut, `RefreshDatabase` commenté, zéro test réel.

**Ce qu'il a fallu réparer avant même de pouvoir écrire un test :**
- `UserFactory` référençait une colonne `email_verified_at` qui n'existe pas dans la migration → **chaque appel à une factory plantait**.
- 7 modèles (`User`, `Patient`, `StaffMedical`, `RendezVous`, `FileAttente`, `Consultation`, `Facture`) n'avaient pas le trait `HasFactory` → les factories étaient physiquement inutilisables.
- Deux `orderByRaw("FIELD(...))")` (MySQL uniquement) dans `FileAttenteController` et `DashboardController` → cassaient sous SQLite (base de test standard Laravel). Remplacés par un `CASE WHEN` portable.
- Une migration utilisait `DB::statement('ALTER TABLE ... MODIFY ...')` (syntaxe MySQL brute) → remplacée par `Schema::table()->change()` (portable).

**Une fois l'infrastructure réparée, 39 tests écrits ont immédiatement révélé 3 bugs réels en production :**

| # | Bug | Impact réel | Correction |
|---|-----|-------------|------------|
| 1 | Un visiteur non connecté accédant à une page protégée était redirigé vers `/login` (route POST uniquement) au lieu de la page de connexion réelle | **Erreur 405** au lieu de voir le formulaire de connexion | `redirectGuestsTo()` dans `bootstrap/app.php` |
| 2 | `patients.statut_marital` est `NOT NULL` en base mais n'apparaît nulle part dans le formulaire ni la validation ; `date_naissance`/`genre`/`telephone` traités comme optionnels côté validation mais obligatoires côté schéma | **L'inscription d'un patient plantait (erreur 500)** dans le vrai formulaire, systématiquement | Nouvelle migration rendant ces colonnes `nullable`, + ajout de la règle de validation manquante |
| 3 | La vérification anti-double-réservation de `RendezVousController` comparait une colonne de type `date` à une chaîne brute — ne fonctionnait que par accident grâce à la troncature implicite de MySQL | Fragile, non portable, risque de double-réservation silencieuse sur un autre SGBD | Remplacé par `whereDate()` |

Chaque correction a été **vérifiée manuellement en direct** contre la vraie base MySQL de développement (pas seulement dans les tests), en reproduisant exactement le scénario cassé.

### Phase 3 — Analyse des angles morts restants + corrections (13-14/20 → 17-18/20 visé)

Après la phase 2, une analyse ciblée des contrôleurs pas encore testés (`Ordonnances`, `StaffMedical`, `User`, `Account`) et une vérification des contraintes de suppression en cascade a révélé :

**🔴 Le problème le plus grave de toute la session :**
Les migrations définissent `cascadeOnDelete()` sur `patient_id`/`staff_id` pour `consultations`, `rendez_vous`, `file_attentes`, `ordonnances`, **et `factures`**. Résultat vérifié empiriquement :

```
Avant suppression : consultations=1, rendez_vous=1
Après suppression du médecin : consultations=0, rendez_vous=0
```

**Un simple clic sur "Supprimer" un patient ou un membre du personnel détruisait irrémédiablement tout son historique médical ET financier**, sans confirmation renforcée, sans sauvegarde. Pour une application de dossiers médicaux, c'est le type de faille qu'un jury — ou un vrai déploiement — repère immédiatement.

**Correction (le sujet le plus fort à présenter en soutenance) :**
- `SoftDeletes` ajouté à `Patient` et `StaffMedical` → `->delete()` n'exécute plus un vrai `DELETE` SQL, donc les cascades ne se déclenchent jamais. L'enregistrement est *archivé* (masqué des listes actives), pas détruit.
- 12 relations `belongsTo(...)` (patient/staff sur `Consultation`, `RendezVous`, `FileAttente`, `Facture`, `Ordonnance`, plus `Patient::medecin()`) mises à jour avec `->withTrashed()` pour que l'historique reste consultable même après archivage.
- Testé (`DataRetentionTest.php`) et re-vérifié en direct contre MySQL.

**Autres corrections de cette phase :**
- **Barre de recherche factice** : `SearchController` était importé dans les routes mais n'existait pas ; le JS ne faisait aucun appel réseau. Implémenté pour de vrai (recherche patients/personnel, résultats personnel limités aux admins).
- **Aucune CI** : ajout de `.github/workflows/tests.yml` (exécution de la suite à chaque push/PR).
- **Couverture de tests étendue** aux contrôleurs `Ordonnances`, `StaffMedical`, `User`, `Account` (+27 tests, aucun nouveau bug trouvé — bon signe, ces contrôleurs étaient déjà solides).
- **Durcissement XSS mineur** : `{!! json_encode() !!}` → `@json()` dans les vues `patients/index` et `staff/index`.
- **Installation cassée pour un nouveau développeur** : le script `composer run setup` migrait mais ne "seedait" jamais la base → un clone frais suivant exactement le README se retrouvait avec zéro utilisateur et aucun moyen de se connecter (pas de page d'inscription). Corrigé (`migrate --seed --force`).
- **README** enrichi : matrice des rôles/permissions, identifiants admin par défaut, justification du choix de suppression douce.

---

## Chiffres à citer

- **69 tests automatisés**, ~185 assertions, 0 échec — partis de **0**.
- **7 bugs réels** trouvés et corrigés en écrivant des tests / en auditant le code (pas des bugs hypothétiques — chacun a été reproduit et vérifié corrigé sur la vraie base de données).
- **CI** en place (GitHub Actions) — la suite tourne automatiquement à chaque push.
- Estimation du niveau de qualité technique : **~10-11/20 → ~17-18/20**.

---

## Questions probables du jury (et pistes de réponse)

**« Comment savez-vous que ça marche ? »**
→ 69 tests automatisés couvrant l'authentification, les permissions par rôle, chaque module métier, et les calculs financiers ; exécutés automatiquement en CI à chaque modification.

**« Qu'est-ce qui vous a le plus surpris pendant le développement ? »**
→ Le bug de suppression en cascade : supprimer un patient détruisait silencieusement toutes ses factures et consultations. Bon exemple pour montrer une compréhension du *domaine* (rétention légale des dossiers médicaux), pas juste de la technique.

**« Pourquoi une suppression douce (soft delete) plutôt qu'une simple suppression ? »**
→ Les dossiers médicaux et les factures ont une valeur légale et doivent être conservés même quand un patient n'est plus suivi ou qu'un employé quitte la clinique. La suppression douce archive (masque) sans détruire — c'est le pattern standard pour ce type de données réglementées.

**« Que reste-t-il à améliorer ? »** *(anticiper, ne pas se faire surprendre)*
→ Pas d'interface pour consulter/restaurer les dossiers archivés (la donnée est préservée en base, mais inaccessible depuis l'UI actuellement) ; pas d'audit de sécurité formel au-delà des points corrigés ; couverture de tests à ~90% des contrôleurs, pas 100%.

---

## Fichiers clés si le jury demande à voir du code

- `tests/Feature/DataRetentionTest.php` — preuve du fix le plus important
- `database/migrations/2026_07_11_100000_add_soft_deletes_to_patients_and_staff.php`
- `app/Http/Controllers/DashboardController.php` — avant/après données réelles
- `.github/workflows/tests.yml` — CI
- `README.md` — matrice des rôles, section "Rétention des données médicales"
