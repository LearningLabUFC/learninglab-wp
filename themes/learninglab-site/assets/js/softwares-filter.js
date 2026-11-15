/**
 * Filtros por categoria da listagem de softwares (page-softwares.php).
 *
 * - Cada card recebe data-tipos="slug-a slug-b" (um software pode ter mais de um tipo).
 * - A filtragem é client-side: apenas alterna a classe .is-hidden dos cards.
 * - O botão ativo recebe .is-active e aria-selected="true".
 * - Se nenhum card corresponder, o bloco .softwares-empty é exibido.
 */
(function () {
    'use strict';

    var barra = document.querySelector('.softwares-filtros');
    if (!barra) {
        return;
    }

    var grid = document.getElementById('softwares-grid') || document.querySelector('.page-softwares .cards-grid');
    if (!grid) {
        return;
    }

    var cards = Array.prototype.slice.call(grid.querySelectorAll('.card'));
    var botoes = Array.prototype.slice.call(barra.querySelectorAll('[data-filtro]'));
    var vazio = document.querySelector('.softwares-empty');
    var filtroAtual = '*';

    function tiposDoCard(card) {
        return (card.getAttribute('data-tipos') || '')
            .split(/\s+/)
            .filter(Boolean);
    }

    function aplicarFiltro(slug) {
        filtroAtual = slug;

        var visiveis = 0;

        cards.forEach(function (card) {
            var corresponde = slug === '*' || tiposDoCard(card).indexOf(slug) !== -1;

            card.classList.toggle('is-hidden', !corresponde);

            if (corresponde) {
                visiveis++;
            }
        });

        botoes.forEach(function (botao) {
            var ativo = botao.getAttribute('data-filtro') === slug;

            botao.classList.toggle('is-active', ativo);
            botao.setAttribute('aria-selected', ativo ? 'true' : 'false');
            botao.setAttribute('tabindex', ativo ? '0' : '-1');
        });

        if (vazio) {
            vazio.hidden = visiveis !== 0;
        }

        // Mantém o filtro na URL para permitir compartilhar/recarregar mantendo a seleção.
        if (window.history && window.history.replaceState) {
            var url = new URL(window.location.href);

            if (slug === '*') {
                url.searchParams.delete('tipo');
            } else {
                url.searchParams.set('tipo', slug);
            }

            window.history.replaceState(null, '', url.toString());
        }
    }

    barra.addEventListener('click', function (evento) {
        var botao = evento.target.closest('[data-filtro]');

        if (!botao || !barra.contains(botao)) {
            return;
        }

        aplicarFiltro(botao.getAttribute('data-filtro'));
    });

    // Navegação por teclado entre as pílulas (setas esquerda/direita).
    barra.addEventListener('keydown', function (evento) {
        if (evento.key !== 'ArrowRight' && evento.key !== 'ArrowLeft') {
            return;
        }

        var indice = botoes.indexOf(document.activeElement);

        if (indice === -1) {
            return;
        }

        evento.preventDefault();

        var passo = evento.key === 'ArrowRight' ? 1 : -1;
        var proximo = (indice + passo + botoes.length) % botoes.length;

        botoes[proximo].focus();
    });

    // Estado inicial: respeita ?tipo=... da URL, se existir e for válido.
    var inicial = '*';
    var params = new URL(window.location.href).searchParams;
    var tipoUrl = params.get('tipo');

    if (tipoUrl && botoes.some(function (botao) {
        return botao.getAttribute('data-filtro') === tipoUrl;
    })) {
        inicial = tipoUrl;
    }

    aplicarFiltro(inicial);
})();
