<?php
// ============================================================================
// CONFIGURAZIONE BASE
// ============================================================================

// Simula la tua logica per SITE_URL (percorsi relativi/assoluti)
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/';
$basePath = rtrim(dirname($scriptName), '/\\');
$baseUrl = ($basePath === '' || $basePath === '.') ? '/' : $basePath . '/';

// Definisce le costanti
define('SITE_URL', $baseUrl);
define('SITE_NAME', 'Il Mio Sito PHP');
define('SITE_DESCRIPTION', 'Descrizione del mio fantastico sito.');
define('SITE_AUTHOR', 'Il Tuo Nome');

// ============================================================================
// FUNZIONI UTILITY
// ============================================================================

/** Funzione per sanitizzare e validare l'output HTML */
function sanitize($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/** Funzione per costruire URL relativi al sito */
function url($path = '') {
    return SITE_URL . ltrim($path, '/');
}

/** Funzione per impostare e mostrare il titolo della pagina */
function page_title($title = '') {
    static $stored_title = '';
    
    if ($title !== '') {
        $stored_title = $title;
    }
    
    // Aggiunge il nome del sito al titolo
    return $stored_title ? $stored_title . ' - ' . SITE_NAME : SITE_NAME;
}

/** Funzione per impostare e recuperare la descrizione della pagina */
function page_description($desc = '') {
    static $stored_desc = '';
    
    if ($desc !== '') {
        $stored_desc = $desc;
    }
    
    // Usa la costante se non è stata impostata una descrizione specifica
    return $stored_desc ?: SITE_DESCRIPTION;
}
?>
