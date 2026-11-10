document.addEventListener('DOMContentLoaded', function () {
    const secao = document.querySelector('.container-leitura');

    if (!secao || typeof Swiper === 'undefined') return;

    const carrossel = secao.querySelector('.leitura-swiper');
    if (!carrossel) return;

    const quantidade = carrossel.querySelectorAll('.swiper-slide').length;

    new Swiper(carrossel, {
        slidesPerView: 1,
        spaceBetween: 20,
        speed: 600,
        loop: quantidade >= 6,
        rewind: quantidade > 1 && quantidade < 6,
        grabCursor: true,
        simulateTouch: true,
        watchOverflow: true,

        autoplay: quantidade > 1 ? {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        } : false,

        navigation: {
            nextEl: secao.querySelector('.leitura-next'),
            prevEl: secao.querySelector('.leitura-prev')
        },

        pagination: {
            el: secao.querySelector('.leitura-pagination'),
            clickable: true
        },

        breakpoints: {
            768: {
                slidesPerView: 2,
                spaceBetween: 24
            },
            1024: {
                slidesPerView: 3,
                spaceBetween: 30
            }
        }
    });
});
