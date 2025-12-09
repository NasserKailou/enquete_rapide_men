<!-- SECTION 1: IDENTIFICATION DU JE -->
<div class="form-section active" data-section="1">
    <h2 class="section-title">
        <i class="fas fa-id-card"></i> I. Identification du Jardin d'Enfants
    </h2>
    
    <div class="form-grid">
        <div class="form-group col-2">
            <label for="nom_je">Nom du JE <span class="required">*</span></label>
            <input type="text" id="nom_je" name="nom_etablissement" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="code_je">Code <span class="required">*</span></label>
            <input type="text" id="code_je" name="code_etablissement" required class="form-control" 
                   pattern="[A-Z0-9\-]+" placeholder="PRE-XXX-XXXXXX-XXXX">
            <small>Format: PRE-XXX-XXXXXX-XXXX (généré automatiquement si vide)</small>
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
            <select id="departement" name="departement" required class="form-control" disabled>
                <option value="">-- Sélectionnez d'abord une région --</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="commune">Commune <span class="required">*</span></label>
            <input type="text" id="commune" name="commune" required class="form-control">
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
            <label>1.9 Type d'enseignement <span class="required">*</span></label>
            <div class="checkbox-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Traditionnel"> Traditionnel
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Médersa"> Médersa
                </label>
                <label class="checkbox-label">
                    <input type="checkbox" name="type_enseignement[]" value="Coranique"> Coranique
                </label>
            </div>
        </div>
    </div>

    <div class="form-grid">
        <div class="form-group">
            <label>1.10 Est-ce que votre JE a fonctionné l'année précédente ? <span class="required">*</span></label>
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
            <label>1.11 Votre JE est-il adossé à une école primaire ?</label>
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
        <i class="fas fa-users"></i> II. Structures Pédagogiques et Effectifs d'Enfants
    </h2>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th rowspan="2">Section</th>
                    <th colspan="2">Nombre de groupes pédagogiques</th>
                    <th colspan="3">Nombre total d'enfants</th>
                </tr>
                <tr>
                    <th>Unique</th>
                    <th>Jumelés</th>
                    <th>Garçons</th>
                    <th>Filles</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="row-header">Section 1</td>
                    <td><input type="number" name="effectifs[section1][groupes_unique]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section1][groupes_jumeles]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section1][garcons]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section1][filles]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td class="total-cell" id="total_section1">0</td>
                </tr>
                <tr>
                    <td class="row-header">Section 2</td>
                    <td><input type="number" name="effectifs[section2][groupes_unique]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section2][groupes_jumeles]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section2][garcons]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td><input type="number" name="effectifs[section2][filles]" min="0" value="0" class="form-control-sm" onchange="calculateTotal()"></td>
                    <td class="total-cell" id="total_section2">0</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td id="total_groupes_unique">0</td>
                    <td id="total_groupes_jumeles">0</td>
                    <td id="total_garcons">0</td>
                    <td id="total_filles">0</td>
                    <td id="total_general">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-box">
        <i class="fas fa-info-circle"></i>
        <p>Les totaux sont calculés automatiquement. Assurez-vous que les chiffres saisis correspondent aux registres officiels.</p>
    </div>
</div>

<!-- SECTION 3: PERSONNEL EDUCATEURS -->
<div class="form-section" data-section="3">
    <h2 class="section-title">
        <i class="fas fa-chalkboard-teacher"></i> III. Personnel Éducateurs
    </h2>
    
    <div class="form-grid">
        <div class="form-group">
            <label for="nb_educateurs_total">Nombre total d'éducateurs <span class="required">*</span></label>
            <input type="number" id="nb_educateurs_total" name="nb_educateurs_total" min="0" value="0" required class="form-control">
        </div>
        
        <div class="form-group">
            <label for="nb_educateurs_besoin">Besoins en éducateurs</label>
            <input type="number" id="nb_educateurs_besoin" name="nb_educateurs_besoin" min="0" value="0" class="form-control">
        </div>
    </div>

    <h3 class="subsection-title">Répartition des éducateurs par catégorie</h3>
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
                $categories_prescolaire = [
                    'Fonctionnaire I' => 'fonctionnaire_i',
                    'Fonctionnaire IA' => 'fonctionnaire_ia',
                    'Contractuel public I' => 'contractuel_public_i',
                    'Contractuel public IA' => 'contractuel_public_ia',
                    'Maître Communautaire I' => 'maitre_communautaire_i',
                    'Maître Communautaire IA' => 'maitre_communautaire_ia',
                    'Fonctionnaire au privé I' => 'fonctionnaire_prive_i',
                    'Fonctionnaire au privé IA' => 'fonctionnaire_prive_ia',
                    'Enseignant du privé I' => 'enseignant_prive_i',
                    'Enseignant du privé IA' => 'enseignant_prive_ia',
                    'Autre' => 'autre',
                ];
                
                foreach ($categories_prescolaire as $label => $name):
                ?>
                <tr>
                    <td class="row-header"><?php echo $label; ?></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][hommes]" min="0" value="0" class="form-control-sm personnel-input" data-cat="<?php echo $name; ?>" data-genre="hommes"></td>
                    <td><input type="number" name="personnel[<?php echo $name; ?>][femmes]" min="0" value="0" class="form-control-sm personnel-input" data-cat="<?php echo $name; ?>" data-genre="femmes"></td>
                    <td class="total-cell" id="total_<?php echo $name; ?>">0</td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td id="total_personnel_hommes">0</td>
                    <td id="total_personnel_femmes">0</td>
                    <td id="total_personnel_general">0</td>
                </tr>
            </tbody>
        </table>
    </div>

    <h3 class="subsection-title">Éducateurs Stagiaires</h3>
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

<!-- SECTION 4: SALLES DE CLASSES ET MOBILIERS -->
<div class="form-section" data-section="4">
    <h2 class="section-title">
        <i class="fas fa-building"></i> IV. Salles de Classes et Mobiliers
    </h2>
    
    <h3 class="subsection-title">Salles de Classes</h3>
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
            <input type="number" id="salles_dur" name="salles[dur]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_semi_dur">Semi dur</label>
            <input type="number" id="salles_semi_dur" name="salles[semi_dur]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_banco">Banco/Terre stabilisée</label>
            <input type="number" id="salles_banco" name="salles[banco_terre_stabilisee]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_evolutive">Structure Évolutive</label>
            <input type="number" id="salles_evolutive" name="salles[structure_evolutive]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_prefab">Préfabriquées</label>
            <input type="number" id="salles_prefab" name="salles[prefabriquees]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_paillotes_amel">Paillotes améliorées</label>
            <input type="number" id="salles_paillotes_amel" name="salles[paillotes_ameliorees]" min="0" value="0" class="form-control salle-input">
        </div>
        
        <div class="form-group">
            <label for="salles_paillotes_ord">Paillotes ordinaires</label>
            <input type="number" id="salles_paillotes_ord" name="salles[paillotes_ordinaire]" min="0" value="0" class="form-control salle-input">
        </div>
    </div>

    <h3 class="subsection-title">V. Mobiliers</h3>
    <div class="form-grid">
        <div class="form-group">
            <label for="tables_adaptables">Tables adaptables</label>
            <input type="number" id="tables_adaptables" name="mobiliers[tables_adaptables]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_adaptees">Tables bancs adaptées</label>
            <input type="number" id="tables_bancs_adaptees" name="mobiliers[tables_bancs_adaptees]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_ordinaires">Tables bancs ordinaires</label>
            <input type="number" id="tables_bancs_ordinaires" name="mobiliers[tables_bancs_ordinaires]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="chaises_ordinaires">Chaises ordinaires/Tabourets</label>
            <input type="number" id="chaises_ordinaires" name="mobiliers[chaises_ordinaires_tabourets]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="chaises_adaptees">Chaises adaptées</label>
            <input type="number" id="chaises_adaptees" name="mobiliers[chaises_adaptees]" min="0" value="0" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="tables_bancs_handicapes">Tables bancs adaptés aux handicapés</label>
            <input type="number" id="tables_bancs_handicapes" name="mobiliers[tables_bancs_handicapes]" min="0" value="0" class="form-control">
        </div>
    </div>
</div>

<!-- SECTION 5: INFRASTRUCTURES ET INFORMATIONS DE CONTACT -->
<div class="form-section" data-section="5">
    <h2 class="section-title">
        <i class="fas fa-tools"></i> VI. Infrastructures
    </h2>
    
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
// Calcul automatique des totaux pour les effectifs
function calculateTotal() {
    // Section 1
    const s1_garcons = parseInt(document.querySelector('[name="effectifs[section1][garcons]"]').value) || 0;
    const s1_filles = parseInt(document.querySelector('[name="effectifs[section1][filles]"]').value) || 0;
    document.getElementById('total_section1').textContent = s1_garcons + s1_filles;
    
    // Section 2
    const s2_garcons = parseInt(document.querySelector('[name="effectifs[section2][garcons]"]').value) || 0;
    const s2_filles = parseInt(document.querySelector('[name="effectifs[section2][filles]"]').value) || 0;
    document.getElementById('total_section2').textContent = s2_garcons + s2_filles;
    
    // Groupes uniques
    const s1_unique = parseInt(document.querySelector('[name="effectifs[section1][groupes_unique]"]').value) || 0;
    const s2_unique = parseInt(document.querySelector('[name="effectifs[section2][groupes_unique]"]').value) || 0;
    document.getElementById('total_groupes_unique').textContent = s1_unique + s2_unique;
    
    // Groupes jumelés
    const s1_jumeles = parseInt(document.querySelector('[name="effectifs[section1][groupes_jumeles]"]').value) || 0;
    const s2_jumeles = parseInt(document.querySelector('[name="effectifs[section2][groupes_jumeles]"]').value) || 0;
    document.getElementById('total_groupes_jumeles').textContent = s1_jumeles + s2_jumeles;
    
    // Totaux généraux
    document.getElementById('total_garcons').textContent = s1_garcons + s2_garcons;
    document.getElementById('total_filles').textContent = s1_filles + s2_filles;
    document.getElementById('total_general').textContent = s1_garcons + s1_filles + s2_garcons + s2_filles;
}

// Calcul automatique du personnel
document.addEventListener('DOMContentLoaded', function() {
    const personnelInputs = document.querySelectorAll('.personnel-input');
    personnelInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cat = this.dataset.cat;
            const hommes = parseInt(document.querySelector(`[name="personnel[${cat}][hommes]"]`).value) || 0;
            const femmes = parseInt(document.querySelector(`[name="personnel[${cat}][femmes]"]`).value) || 0;
            document.getElementById(`total_${cat}`).textContent = hommes + femmes;
            
            // Total général
            let totalHommes = 0, totalFemmes = 0;
            document.querySelectorAll('.personnel-input[data-genre="hommes"]').forEach(el => {
                totalHommes += parseInt(el.value) || 0;
            });
            document.querySelectorAll('.personnel-input[data-genre="femmes"]').forEach(el => {
                totalFemmes += parseInt(el.value) || 0;
            });
            
            document.getElementById('total_personnel_hommes').textContent = totalHommes;
            document.getElementById('total_personnel_femmes').textContent = totalFemmes;
            document.getElementById('total_personnel_general').textContent = totalHommes + totalFemmes;
        });
    });
    
    // Stagiaires
    document.getElementById('stagiaires_hommes').addEventListener('change', updateStagiaires);
    document.getElementById('stagiaires_femmes').addEventListener('change', updateStagiaires);
    
    function updateStagiaires() {
        const hommes = parseInt(document.getElementById('stagiaires_hommes').value) || 0;
        const femmes = parseInt(document.getElementById('stagiaires_femmes').value) || 0;
        document.getElementById('stagiaires_total').value = hommes + femmes;
    }
});

// Charger les départements
const departementsData = <?php echo json_encode($departements_par_region); ?>;

function loadDepartements(region) {
    const depSelect = document.getElementById('departement');
    depSelect.innerHTML = '<option value="">-- Sélectionnez --</option>';
    
    if (region && departementsData[region]) {
        depSelect.disabled = false;
        departementsData[region].forEach(dep => {
            const option = document.createElement('option');
            option.value = dep;
            option.textContent = dep;
            depSelect.appendChild(option);
        });
    } else {
        depSelect.disabled = true;
    }
}
</script>
