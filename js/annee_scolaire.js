/**
 * Gestion de l'affichage de l'année scolaire active
 * Ce script charge automatiquement l'année scolaire active depuis l'API
 * et met à jour tous les éléments avec la classe 'annee-scolaire-display'
 */

// Variable globale pour stocker l'année active
let anneeScolaireActive = null;

/**
 * Charge l'année scolaire active depuis l'API
 */
async function loadAnneeScolaireActive() {
    try {
        const response = await fetch('api/annees_scolaires.php?action=active');
        const result = await response.json();
        
        if (result.success && result.data) {
            anneeScolaireActive = result.data;
            updateAnneeScolaireDisplay();
            return result.data;
        } else {
            // Si aucune année n'est active, utiliser une valeur par défaut
            anneeScolaireActive = {
                id: null,
                libelle: '2025-2026',
                active: 0,
                collecte_ouverte: 1
            };
            updateAnneeScolaireDisplay();
            return anneeScolaireActive;
        }
    } catch (error) {
        console.error('Erreur lors du chargement de l\'année scolaire:', error);
        // En cas d'erreur, utiliser une valeur par défaut
        anneeScolaireActive = {
            id: null,
            libelle: '2025-2026',
            active: 0,
            collecte_ouverte: 1
        };
        updateAnneeScolaireDisplay();
        return anneeScolaireActive;
    }
}

/**
 * Met à jour tous les éléments qui affichent l'année scolaire
 */
function updateAnneeScolaireDisplay() {
    if (!anneeScolaireActive) return;
    
    // Sélectionner tous les éléments avec la classe 'annee-scolaire-display'
    const elements = document.querySelectorAll('.annee-scolaire-display');
    
    elements.forEach(element => {
        // Vérifier si l'élément contient une icône
        const hasIcon = element.querySelector('i.fa-calendar-alt');
        
        if (hasIcon) {
            // Si l'icône existe, remplacer tout le contenu en gardant l'icône
            element.innerHTML = `<i class="fas fa-calendar-alt"></i> Année Scolaire ${anneeScolaireActive.libelle}`;
        } else {
            // Sinon, remplacer juste le texte
            element.textContent = `Année Scolaire ${anneeScolaireActive.libelle}`;
        }
        
        // Ajouter des attributs de données pour faciliter l'accès aux infos
        element.setAttribute('data-annee-id', anneeScolaireActive.id || '');
        element.setAttribute('data-annee-libelle', anneeScolaireActive.libelle);
        element.setAttribute('data-annee-active', anneeScolaireActive.active);
        element.setAttribute('data-collecte-ouverte', anneeScolaireActive.collecte_ouverte);
        
        // Ajouter une classe pour indiquer que les données sont chargées
        element.classList.add('loaded');
        
        // Ajouter un tooltip si l'année est active
        if (anneeScolaireActive.active == 1) {
            element.title = 'Année scolaire active';
            
            // Ajouter un badge "ACTIVE" si demandé
            if (element.classList.contains('with-badge')) {
                const badge = document.createElement('span');
                badge.className = 'badge-annee-active';
                badge.innerHTML = '<i class="fas fa-check-circle"></i> ACTIVE';
                badge.style.cssText = `
                    background: #4caf50;
                    color: white;
                    padding: 0.2rem 0.6rem;
                    border-radius: 12px;
                    font-size: 0.7rem;
                    margin-left: 0.5rem;
                    font-weight: 600;
                `;
                element.appendChild(badge);
            }
        }
        
        // Ajouter un indicateur de collecte si demandé
        if (element.classList.contains('with-collecte-status')) {
            const status = document.createElement('span');
            status.className = 'collecte-status';
            
            if (anneeScolaireActive.collecte_ouverte == 1) {
                status.innerHTML = '<i class="fas fa-unlock"></i> Collecte ouverte';
                status.style.cssText = `
                    background: #e3f2fd;
                    color: #1976d2;
                    padding: 0.2rem 0.6rem;
                    border-radius: 12px;
                    font-size: 0.7rem;
                    margin-left: 0.5rem;
                    font-weight: 600;
                `;
            } else {
                status.innerHTML = '<i class="fas fa-lock"></i> Collecte fermée';
                status.style.cssText = `
                    background: #ffebee;
                    color: #f44336;
                    padding: 0.2rem 0.6rem;
                    border-radius: 12px;
                    font-size: 0.7rem;
                    margin-left: 0.5rem;
                    font-weight: 600;
                `;
            }
            
            element.appendChild(status);
        }
    });
}

/**
 * Obtenir l'année scolaire active (depuis le cache ou l'API)
 */
async function getAnneeScolaireActive() {
    if (anneeScolaireActive) {
        return anneeScolaireActive;
    }
    return await loadAnneeScolaireActive();
}

/**
 * Vérifier si la collecte est ouverte
 */
function isCollecteOuverte() {
    if (!anneeScolaireActive) {
        return true; // Par défaut, autoriser si on ne sait pas
    }
    return anneeScolaireActive.collecte_ouverte == 1;
}

/**
 * Afficher un message si la collecte est fermée
 */
function showCollecteFermeeMessage() {
    if (!isCollecteOuverte()) {
        const message = document.createElement('div');
        message.className = 'alert-collecte-fermee';
        message.innerHTML = `
            <i class="fas fa-lock"></i>
            <strong>Collecte fermée</strong><br>
            La collecte de données pour l'année ${anneeScolaireActive.libelle} est actuellement fermée.
            Veuillez contacter l'administrateur pour plus d'informations.
        `;
        message.style.cssText = `
            background: #fff3cd;
            color: #856404;
            padding: 1rem;
            border-radius: 10px;
            margin: 1rem 0;
            border-left: 4px solid #ffc107;
            text-align: center;
        `;
        
        // Insérer le message au début du container principal
        const container = document.querySelector('.container') || document.body;
        container.insertBefore(message, container.firstChild);
    }
}

/**
 * Initialiser au chargement de la page
 */
if (typeof window !== 'undefined') {
    // Charger l'année scolaire dès que possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadAnneeScolaireActive);
    } else {
        // DOM déjà chargé
        loadAnneeScolaireActive();
    }
}

// Exposer les fonctions globalement pour faciliter l'utilisation
window.loadAnneeScolaireActive = loadAnneeScolaireActive;
window.getAnneeScolaireActive = getAnneeScolaireActive;
window.isCollecteOuverte = isCollecteOuverte;
window.showCollecteFermeeMessage = showCollecteFermeeMessage;
