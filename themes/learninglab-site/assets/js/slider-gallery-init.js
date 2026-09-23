/**
 * Slider Gallery — Inicializador do Swiper no Frontend
 *
 * Suporta múltiplas instâncias com setas laterais externas e paginação inferior.
 */

document.addEventListener('DOMContentLoaded', function () {
	var sliders = document.querySelectorAll('.ll-slider-gallery-block .swiper');

	if (!sliders.length) {
		return;
	}

	sliders.forEach(function (sliderEl) {
		var config = {};
		try {
			var rawData = sliderEl.getAttribute('data-swiper');
			if (rawData) {
				config = JSON.parse(rawData);
			}
		} catch (e) {
			config = {};
		}

		var block = sliderEl.closest('.ll-slider-gallery-block') || sliderEl.parentElement;
		var prevEl = block ? block.querySelector('.swiper-button-prev') : null;
		var nextEl = block ? block.querySelector('.swiper-button-next') : null;
		var paginationEl = block ? block.querySelector('.swiper-pagination') : null;

		var swiperOptions = {
			slidesPerView: 1,
			spaceBetween: 16,
			grabCursor: true,
			loop: typeof config.loop !== 'undefined' ? config.loop : true,
			keyboard: {
				enabled: true,
			},
		};

		// Navegação por setas (fora da imagem nas laterais)
		if (prevEl && nextEl) {
			swiperOptions.navigation = {
				prevEl: prevEl,
				nextEl: nextEl,
			};
		}

		// Paginação por pontos (fora e logo abaixo da imagem)
		if (paginationEl) {
			swiperOptions.pagination = {
				el: paginationEl,
				clickable: true,
			};
		}

		// Autoplay
		if (config.autoplay) {
			swiperOptions.autoplay = config.autoplay;
		}

		new Swiper(sliderEl, swiperOptions);
	});
});
