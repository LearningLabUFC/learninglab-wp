document.addEventListener('DOMContentLoaded', function () {
    const secao = document.querySelector('.home-softwares');

    if (!secao || typeof Swiper === 'undefined') return;

    const carrossel = secao.querySelector('.softwares-swiper');
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
            delay: 4500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        } : false,

        navigation: {
            nextEl: secao.querySelector('.softwares-next'),
            prevEl: secao.querySelector('.softwares-prev')
        },

        pagination: {
            el: secao.querySelector('.softwares-pagination'),
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
