/**
 * ==============================================================================
 * Aggiunge classi CSS "slug-based" a Categorie e Tag (Versione Rigorosa)
 * ==============================================================================
 * 1. Carica le categorie
 * 2. Ignora esplicitamente i Titoli (h1, h2, h3...)
 * 3. Usa una comparazione URL esatta per evitare falsi positivi
 */
function astra_universal_slug_class_strict() {
    
    // 1. Recuperiamo TUTTE le categorie popolate
    $terms = get_terms( array(
        'taxonomy'   => 'category',
        'hide_empty' => true,
    ) );

    // 2. Se siamo in un post singolo, aggiungiamo anche i TAG di quel post
    if ( is_single() ) {
        $post_tags = get_the_tags();
        if ( $post_tags ) {
            $terms = array_merge( $terms, $post_tags );
        }
    }

    if ( empty( $terms ) || is_wp_error( $terms ) ) return;

    // 3. Mappa URL -> Classe
    $links_data = array();
    foreach ( $terms as $term ) {
        $link = get_term_link( $term );
        if ( ! is_wp_error( $link ) ) {
            $prefix = ( $term->taxonomy == 'post_tag' ) ? 'tag-custom-' : 'category-custom-';
            // Rimuoviamo slash finali per normalizzare
            $clean_link = rtrim( $link, '/' ); 
            $links_data[ $clean_link ] = $prefix . $term->slug;
        }
    }
    
    if ( empty( $links_data ) ) return;
    ?>
    
    <script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        // Mappa dei link (senza slash finale)
        var slugMap = <?php echo json_encode( $links_data ); ?>;
        var links = document.getElementsByTagName('a');

        for (var i = 0; i < links.length; i++) {
            var anchor = links[i];
            
            // A. SICUREZZA: Se il link è dentro un titolo (h1, h2, h3...), saltalo.
            if ( anchor.closest('h1, h2, h3, h4, h5, h6') ) {
                continue;
            }
            
            // B. PULIZIA: Rimuovi slash finale dall'href trovato nella pagina per confronto equo
            // Rimuove anche eventuali ancore (#) o parametri (?) per sicurezza
            var currentHref = anchor.href.split('#')[0].split('?')[0].replace(/\/$/, "");

            // C. CONTROLLO ESATTO: Verifica se l'URL combacia perfettamente con una categoria
            if ( slugMap[currentHref] ) {
                anchor.classList.add( slugMap[currentHref] );
            }
        }
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'astra_universal_slug_class_strict', 99 );
