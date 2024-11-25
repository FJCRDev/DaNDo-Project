document.addEventListener('DOMContentLoaded', function () {
    // Creamo las constantes a usar para lo del panel
    const toggleButton = document.querySelector('.sessions-toggle button');
    const sessionsPanel = document.querySelector('.sessions-panel');


    // Añadimos un event listener para que cuando al botón de las sesiones se ponga encima, ponga todo el left que tiene
    // en el css a 0, es decir que aparezca 
    toggleButton.addEventListener('mouseover', function () {
        sessionsPanel.style.left = '0';
    });

    // Y que cuando el ratón se vaya del panel de sesion (QUE NO del botón ya que al superponerse el panel siempre "se iria")
    // se vuelva a ocultar el panel
    sessionsPanel.addEventListener('mouseleave', function () {
        sessionsPanel.style.left = '-100%';
    });
});
