<!-- SECTION 1: IDENTIFICATION DE L'ÉCOLE -->
<div class="form-section active" data-section="1">
    <h2 class="section-title">
        <i class="fas fa-id-card"></i> I. Identification de l'École
    </h2>
    
    <div class="form-grid">
        <div class="form-group col-2">
            <label for="nom_ecole">Nom de l'école <span class="required">*</span></label>
            <input type="text" id="nom_ecole" name="nom_etablissement" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="code_ecole">Code <span class="required">*</span></label>
            <input type="text" id="code_ecole" name="code_etablissement" required class="form-control" 
                   pattern="[A-Z0-9\-]+" placeholder="PRI-XXX-XXXXXX-XXXX">
            <small>Format: PRI-XXX-XXXXXX-XXXX (généré automatiquement si vide)</small>
        </div>
        
        <div class="form-group">
            <label for="date_creation">Date de création <span class="required">*</span></label>
            <input type="date" id="date_creation" name="date_creation" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="date_ouverture">Date d'ouverture <span class="required">*</span></label>
            <input type="date" id="date_ouverture" name="date_ouverture" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">1.5 Localisation Administrative</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="region">Région <span class="required">*</span></label>
            <select id="region" name="region" required class="form-control" onchange="loadDepartements(this.value)">
                <option value="">-- Sélectionnez --</option>
                <?php foreach($regions_niger as $region): ?>
                    <option value="<?php echo $region; ?>"><?php echo $region; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="departement">Département <span class="required">*</span></label>
            <select id="departement" name="departement" required class="form-control"  onchange="loadCommunes(this.value)" disabled>
                <option value="">-- Sélectionnez d'abord une région --</option>
            </select>
        </div>

         <div class="form-group">
            <label for="commune">Commune <span class="required">*</span></label>
            <select id="commune" name="commune" required class="form-control" disabled>
                <option value="">-- Sélectionnez d'abord un departement --</option>
            </select>
        </div>
        
       
        
        <div class="form-group">
            <label for="village_quartier">Village/Quartier <span class="required">*</span></label>
            <input type="text" id="village_quartier" name="village_quartier" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">1.6 Localisation Scolaire</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="dren_apl">DREN/A/PL <span class="required">*</span></label>
            <input type="text" id="dren_apl" name="dren_apl" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="iden_apl">IDEN/A/PL <span class="required">*</span></label>
            <input type="text" id="iden_apl" name="iden_apl" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="inspection">Inspection <span class="required">*</span></label>
            <input type="text" id="inspection" name="inspection" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="secteur_pedagogique">Secteur pédagogique <span class="required">*</span></label>
            <input type="text" id="secteur_pedagogique" name="secteur_pedagogique" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Caractéristiques</h3>
    <div class="form-grid">
        <div class="form-group">
            <label>1.7 Zone <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="zone" value="Rurale" required> Rurale
                </label>
                <label class="radio-label">
                    <input type="radio" name="zone" value="Urbaine" required> Urbaine
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.8 Statut <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="statut" value="Public" required> Public
                </label>
                <label class="radio-label">
                    <input type="radio" name="statut" value="Privé" required> Privé
                </label>
                <label class="radio-label">
                    <input type="radio" name="statut" value="Communautaire" required> Communautaire
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.9 Type d'enseignement</label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Traditionnel"> Traditionnel
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Médersa"> Médersa
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Spécialisé"> Spécialisé
                </label>
            </div>
        </div>
    </div>

    <div class="form-grid">
        <div class="form-group">
            <label>1.10 Est-ce que votre établissement a fonctionné l'année précédente ? <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="fonction_annee_precedente" value="1" required> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="fonction_annee_precedente" value="0" required> Non
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>1.11 Y'a-t-il un Jardin d'Enfants (JE) dans votre établissement ?</label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="jardin_enfants_adosse" value="1"> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="jardin_enfants_adosse" value="0"> Non
                </label>
            </div>
        </div>
    </div>
</div>

<!-- SECTION 2: STRUCTURES PEDAGOGIQUES ET EFFECTIFS -->
<div class="form-section" data-section="2">
    <h2 class="section-title">
        <i class="fas fa-users"></i> II. Structures Pédagogiques et Effectifs d'Élèves
    </h2>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">Niveau</th>
                    <th colspan="2">Nombre de groupes pédagogiques</th>
                    <th colspan="3">Nombre total d'élèves</th>
                    <th colspan="3">Nombre total de redoublants</th>
                </tr>
                <tr>
                    <th>Unique</th>
                    <th>Jumelés</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $niveaux = ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2'];
                foreach($niveaux as $niveau): 
                ?>
                <tr>
                    <td class="row-header"><?php echo $niveau; ?></td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][groupes_unique]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][groupes_jumeles]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][garcons]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][filles]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td class="total-cell" id="total_eleves_<?php echo strtolower($niveau); ?>">0</td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][redoublants_garcons]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td><input type="number" name="effectifs[<?php echo strtolower($niveau); ?>][redoublants_filles]" min="0" value="0" class="form-control-sm" onchange="calculateTotalPrimaire()"></td>
                    <td class="total-cell" id="total_redoublants_<?php echo strtolower($niveau); ?>">0</td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td id="total_groupes_unique_prim">0</td>
                    <td id="total_groupes_jumeles_prim">0</td>
                    <td id="total_garcons_prim">0</td>
                    <td id="total_filles_prim">0</td>
                    <td id="total_eleves_prim">0</td>
                    <td id="total_redoublants_garcons_prim">0</td>
                    <td id="total_redoublants_filles_prim">0</td>
                    <td id="total_redoublants_prim">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Les totaux sont calculés automatiquement. Assurez-vous que les chiffres saisis correspondent aux registres officiels.</p>
    </div>
</div>

<!-- SECTION 3: PERSONNEL ENSEIGNANT -->
<div class="form-section" data-section="3">
    <h2 class="section-title">
        <i class="fas fa-chalkboard-teacher"></i> III. Personnel Enseignant
    </h2>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="nb_enseignants_total">Nombre total d'enseignants craie en main <span class="required">*</span></label>
            <input type="number" id="nb_enseignants_total" name="nb_enseignants_total" min="0" value="0" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="nb_enseignants_besoin">Besoins en enseignants</label>
            <input type="number" id="nb_enseignants_besoin" name="nb_enseignants_besoin" min="0" value="0" class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Répartition des enseignants craie en main</h3>
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Catégorie</th>
                    <th>Hommes</th>
                    <th>Femmes</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $categories_primaire = [
                    'Fonctionnaire I' => 'fonctionnaire_i',
                    'Fonctionnaire IA' => 'fonctionnaire_ia',
                    'Contractuel public I' => 'contractuel_public_i',
                    'Contractuel public IA' => 'contractuel_public_ia',
                    'Maître Communautaire I' => 'maitre_communautaire_i',
                    'Maître Communautaire IA' => 'maitre_communautaire_ia',
                    'Fonctionnaire au privé I' => 'fonctionnaire_prive_i',
                    'Fonctionnaire au privé IA' => 'fonctionnaire_prive_ia',
                    'Contractuel de l\'état au privé I' => 'contractuel_etat_prive_i',
                    'Contractuel de l\'état au privé IA' => 'contractuel_etat_prive_ia',
                    'Enseignant du privé I' => 'enseignant_prive_i',
                    'Enseignant du privé IA' => 'enseignant_prive_ia',
                    'Autre' => 'autre',
                ];
                
                foreach ($categories_primaire as $label => $name):
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][hommes]" min="0" value="0" class="form-control-sm personnel-input-prim" data-cat="<?php echo $name; ?>" data-genre="hommes"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][femmes]" min="0" value="0" class="form-control-sm personnel-input-prim" data-cat="<?php echo $name; ?>" data-genre="femmes"></td>
                    <td class="total-cell" id="total_<?php echo $name; ?>">0</td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td id="total_personnel_hommes_prim">0</td>
                    <td id="total_personnel_femmes_prim">0</td>
                    <td id="total_personnel_general_prim">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="subsection-title">Enseignants Stagiaires craie en main</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="stagiaires_hommes">Hommes</label>
            <input type="number" id="stagiaires_hommes" name="stagiaires_hommes" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="stagiaires_femmes">Femmes</label>
            <input type="number" id="stagiaires_femmes" name="stagiaires_femmes" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Total Stagiaires</label>
            <input type="number" id="stagiaires_total" readonly class="form-control" value="0">
        </div>
    </div>
</div>

<!-- SECTION 4: SALLES DE CLASSES -->
<div class="form-section" data-section="4">
    <h2 class="section-title">
        <i class="fas fa-building"></i> IV. Salles de Classes
    </h2>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="total_salles">Total salles dans l'école <span class="required">*</span></label>
            <input type="number" id="total_salles" name="total_salles" min="0" value="0" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_utilisees_total">Total salles utilisées <span class="required">*</span></label>
            <input type="number" id="salles_utilisees_total" name="salles_utilisees" min="0" value="0" required class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Répartition des salles utilisées par type</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="salles_dur">Dur</label>
            <input type="number" id="salles_dur" name="salles[dur]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_semi_dur">Semi dur</label>
            <input type="number" id="salles_semi_dur" name="salles[semi_dur]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_banco">Banco/Terre stabilisée</label>
            <input type="number" id="salles_banco" name="salles[banco_terre_stabilisee]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_evolutive">Structure Évolutive</label>
            <input type="number" id="salles_evolutive" name="salles[structure_evolutive]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_prefab">Préfabriquées</label>
            <input type="number" id="salles_prefab" name="salles[prefabriquees]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_paillotes_amel">Paillotes améliorées</label>
            <input type="number" id="salles_paillotes_amel" name="salles[paillotes_ameliorees]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="salles_paillotes_ord">Paillotes ordinaires</label>
            <input type="number" id="salles_paillotes_ord" name="salles[paillotes_ordinaire]" min="0" value="0" class="form-control">
        </div>
    </div>
</div>

<!-- SECTION 5: MOBILIERS ET INFRASTRUCTURES -->
<div class="form-section" data-section="5">
    <h2 class="section-title">
        <i class="fas fa-chair"></i> V. Mobiliers et Infrastructures Scolaires
    </h2>
    
    <h3 class="subsection-title">Mobiliers</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="total_tables_bancs">Nombre total de tables-bancs</label>
            <input type="number" id="total_tables_bancs" name="mobiliers[total_tables_bancs]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_a_reparer">Dont à réparer</label>
            <input type="number" id="tables_bancs_a_reparer" name="mobiliers[tables_bancs_a_reparer]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_a_completer">Tables-bancs à compléter</label>
            <input type="number" id="tables_bancs_a_completer" name="mobiliers[tables_bancs_a_completer]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_handicapes">Tables bancs adaptés aux handicapés</label>
            <input type="number" id="tables_bancs_handicapes" name="mobiliers[tables_bancs_handicapes]" min="0" value="0" class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Infrastructures</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="latrines_total">Total latrines</label>
            <input type="number" id="latrines_total" name="infrastructures[total_latrines]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="latrines_fonctionnelles">Dont fonctionnelles</label>
            <input type="number" id="latrines_fonctionnelles" name="infrastructures[latrines_fonctionnelles]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label>Point d'eau <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[point_eau]" value="1" required> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[point_eau]" value="0" required> Non
                </label>
            </div>
        </div>
        
        <div class="form-group">
            <label>Électricité <span class="required">*</span></label>
            <div class="radio-group">
                <label class="radio-label">
                    <input type="radio" name="infrastructures[electricite]" value="1" required> Oui
                </label>
                <label class="radio-label">
                    <input type="radio" name="infrastructures[electricite]" value="0" required> Non
                </label>
            </div>
        </div>
    </div>

    <h2 class="section-title" style="margin-top: 2rem;">
        <i class="fas fa-signature"></i> Informations de Contact et Validation
    </h2>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="date_remplissage">Date de remplissage <span class="required">*</span></label>
            <input type="date" id="date_remplissage" name="date_remplissage" required class="form-control" value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label for="telephone_directeur">Numéro Tél. directeur <span class="required">*</span></label>
            <input type="tel" id="telephone_directeur" name="telephone_directeur" required class="form-control" placeholder="+227 XX XX XX XX">
        </div>
        
        <div class="form-group col-2">
            <label for="nom_signature_directeur">Nom et signature du directeur <span class="required">*</span></label>
            <input type="text" id="nom_signature_directeur" name="nom_signature_directeur" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="telephone_chef_secteur">Numéro téléphone du Chef secteur</label>
            <input type="tel" id="telephone_chef_secteur" name="telephone_chef_secteur" class="form-control" placeholder="+227 XX XX XX XX">
        </div>
        
        <div class="form-group">
            <label for="visa_chef_secteur">Visa du Chef secteur pédagogique</label>
            <input type="text" id="visa_chef_secteur" name="visa_chef_secteur" class="form-control">
        </div>
    </div>

    <div class="success-box">
        <i class="fas fa-check-circle"></i>
        <p><strong>Dernière étape !</strong> Vérifiez vos informations et soumettez le formulaire.</p>
    </div>
</div>

<script>
// Calcul automatique des totaux pour les effectifs primaire
function calculateTotalPrimaire() {
    const niveaux = ['ci', 'cp', 'ce1', 'ce2', 'cm1', 'cm2'];
    
    let totalGroupesUnique = 0, totalGroupesJumeles = 0;
    let totalGarcons = 0, totalFilles = 0;
    let totalRedoublantsGarcons = 0, totalRedoublantsFilles = 0;
    
    niveaux.forEach(niveau => {
        const garcons = parseInt(document.querySelector(`[name="effectifs[${niveau}][garcons]"]`)?.value) || 0;
        const filles = parseInt(document.querySelector(`[name="effectifs[${niveau}][filles]"]`)?.value) || 0;
        const redoubGarcons = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_garcons]"]`)?.value) || 0;
        const redoubFilles = parseInt(document.querySelector(`[name="effectifs[${niveau}][redoublants_filles]"]`)?.value) || 0;
        const groupesUnique = parseInt(document.querySelector(`[name="effectifs[${niveau}][groupes_unique]"]`)?.value) || 0;
        const groupesJumeles = parseInt(document.querySelector(`[name="effectifs[${niveau}][groupes_jumeles]"]`)?.value) || 0;
        
        // Totaux par niveau
        const totalElevesEl = document.getElementById(`total_eleves_${niveau}`);
        const totalRedoublantsEl = document.getElementById(`total_redoublants_${niveau}`);
        if (totalElevesEl) totalElevesEl.textContent = garcons + filles;
        if (totalRedoublantsEl) totalRedoublantsEl.textContent = redoubGarcons + redoubFilles;
        
        // Accumulation
        totalGarcons += garcons;
        totalFilles += filles;
        totalRedoublantsGarcons += redoubGarcons;
        totalRedoublantsFilles += redoubFilles;
        totalGroupesUnique += groupesUnique;
        totalGroupesJumeles += groupesJumeles;
    });
    
    // Afficher les totaux généraux
    const setTotal = (id, value) => {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    };
    
    setTotal('total_groupes_unique_prim', totalGroupesUnique);
    setTotal('total_groupes_jumeles_prim', totalGroupesJumeles);
    setTotal('total_garcons_prim', totalGarcons);
    setTotal('total_filles_prim', totalFilles);
    setTotal('total_eleves_prim', totalGarcons + totalFilles);
    setTotal('total_redoublants_garcons_prim', totalRedoublantsGarcons);
    setTotal('total_redoublants_filles_prim', totalRedoublantsFilles);
    setTotal('total_redoublants_prim', totalRedoublantsGarcons + totalRedoublantsFilles);
}

// Calcul automatique du personnel primaire
document.addEventListener('DOMContentLoaded', function() {
    const personnelInputs = document.querySelectorAll('.personnel-input-prim');
    personnelInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cat = this.dataset.cat;
            const hommes = parseInt(document.querySelector(`[name="personnel[${cat}][hommes]"]`)?.value) || 0;
            const femmes = parseInt(document.querySelector(`[name="personnel[${cat}][femmes]"]`)?.value) || 0;
            const totalEl = document.getElementById(`total_${cat}`);
            if (totalEl) totalEl.textContent = hommes + femmes;
            
            // Total général
            let totalHommes = 0, totalFemmes = 0;
            document.querySelectorAll('.personnel-input-prim[data-genre="hommes"]').forEach(el => {
                totalHommes += parseInt(el.value) || 0;
            });
            document.querySelectorAll('.personnel-input-prim[data-genre="femmes"]').forEach(el => {
                totalFemmes += parseInt(el.value) || 0;
            });
            
            const setTotal = (id, value) => {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            };
            
            setTotal('total_personnel_hommes_prim', totalHommes);
            setTotal('total_personnel_femmes_prim', totalFemmes);
            setTotal('total_personnel_general_prim', totalHommes + totalFemmes);
        });
    });
    
    // Stagiaires
    const stagiairesHommes = document.getElementById('stagiaires_hommes');
    const stagiairesFemmes = document.getElementById('stagiaires_femmes');
    const stagiairesTotal = document.getElementById('stagiaires_total');
    
    if (stagiairesHommes && stagiairesFemmes && stagiairesTotal) {
        const updateStagiaires = () => {
            const hommes = parseInt(stagiairesHommes.value) || 0;
            const femmes = parseInt(stagiairesFemmes.value) || 0;
            stagiairesTotal.value = hommes + femmes;
        };
        
        stagiairesHommes.addEventListener('change', updateStagiaires);
        stagiairesFemmes.addEventListener('change', updateStagiaires);
    }
});

// Charger les départements
const departementsData = <?php echo json_encode($departements_par_region); ?>;

// Charger les communes
const communesData = <?php echo json_encode($communes_par_departement); ?>;

function loadDepartements(region) {
    const depSelect = document.getElementById('departement');
    const comSelect = document.getElementById('commune');

    if (!depSelect) return;

    // Reset département
    depSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
    depSelect.disabled = true;

    // Reset commune aussi quand on change de région
    if (comSelect) {
        comSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un département --</option>';
        comSelect.disabled = true;
    }

    if (region && departementsData[region]) {
        depSelect.disabled = false;
        departementsData[region].forEach(dep => {
            const option = document.createElement('option');
            option.value = dep;
            option.textContent = dep;
            depSelect.appendChild(option);
        });
    }
}

function loadCommunes(departement) {
    const comSelect = document.getElementById('commune');
    if (!comSelect) return;

    comSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
    comSelect.disabled = true;

    if (departement && communesData[departement]) {
        comSelect.disabled = false;
        communesData[departement].forEach(com => {
            const option = document.createElement('option');
            option.value = com;
            option.textContent = com;
            comSelect.appendChild(option);
        });
    }
}
</script>
