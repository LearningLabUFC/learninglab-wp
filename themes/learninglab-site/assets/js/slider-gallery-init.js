/**
 * Slider Gallery — Inicializador do Swiper no Frontend
 *
 * Suporta múltiplas instâncias na mesma página com configurações individuais.
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
			// fallback para padrão se json der erro
			config = {};
		}

		var prevEl = sliderEl.querySelector('.swiper-button-prev');
		var nextEl = sliderEl.querySelector('.swiper-button-next');
		var paginationEl = sliderEl.querySelector('.swiper-pagination');

		var swiperOptions = {
			slidesPerView: 1,
			spaceBetween: 16,
			grabCursor: true,
			loop: typeof config.loop !== 'undefined' ? config.loop : true,
			keyboard: {
				enabled: true,
			},
			navigation: {
				prevEl: prevEl,
				nextEl: nextEl,
			},
			pagination: {
				el: paginationEl,
				clickable: true,
			},
		};

		if (config.autoplay) {
			swiperOptions.autoplay = config.autoplay;
		}

		new Swiper(sliderEl, swiperOptions);
	});
});
